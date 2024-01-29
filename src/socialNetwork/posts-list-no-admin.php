<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'list';
$title = 'posts';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_posts";
$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// 抓取comment內容
$sql_cm = "SELECT * FROM sn_comments";
$row_cm = $pdo->query($sql_cm)->fetchAll(PDO::FETCH_ASSOC);
// 抓取結束 

// 抓取comment_reply內容
$sql_reply = "SELECT * FROM sn_comments_reply";
$stmt = $pdo->query($sql_reply);
$row_reply = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 抓取結束 

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM sn_posts ORDER BY post_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<div class="container-fluid overflow-auto">
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= 1 ?>">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-angle-left" href="?page"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                    <?php endif;
                    endfor; ?>

                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $totalPages ?>">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                        <th><i class="fa-solid fa-pen-to-square"></i></th>
                        <th>post_id</th>
                        <th>user_id</th>
                        <th>content</th>
                        <th>image_url</th>
                        <th>video_url</th>
                        <th>location</th>
                        <th>tagged_users</th>
                        <th>posts_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>                       
                             <td>
                                <a href="javascript: delete_one(<?= $r['post_id'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td>
                                <a href="post-edit.php?post_id=<?= $r['post_id']?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td><?= $r['post_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td>
                                <?= $r['content'] ?>
                                <?php
                                $postId = $r['post_id'];
                                ?>
                                <!-- modal -->
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailCm-<?= $postId ?>" id="showCm-<?= $postId ?>">
                                    查看評論
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="detailCm-<?= $postId ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php foreach ($row_cm as $r_cm) : ?>
                                                    <?php if ($r['post_id'] === $r_cm['post_id']) : ?>
                                                        <?= $r_cm['content'] . $r_cm['post_id'] . "<br>"; ?>
                                                        
                                                        <!-- 回覆的回覆 改用api寫-->
                                                        <?php foreach ($row_cm as $r_cm) : ?>
                                                            <?php
                                                            $commentId = $r_cm['comment_id'];
                                                            ?>
                                                            <div style="display:none" id="detailCmRp-<?= $commentId ?>">
                                                                <?php foreach ($row_reply as $r_reply) : ?>
                                                                    <?php if ($r_cm['post_id'] === $r_reply['post_id']) : ?>
                                                                        <button id="showCmRp-<?= $commentId ?>">check details</button>
                                                                        <?= $r_reply['content'] . $r_reply['post_id'] . "<br>"; ?>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <!-- 回覆的回覆 -->
                                                        <?php endforeach ?>

                                                    <?php endif; ?>
                                                <?php endforeach ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- model -->
                            </td>
                            <td><?= $r['image_url'] ?></td>
                            <td><?= $r['video_url'] ?></td>
                            <td><?= $r['location'] ?></td>
                            <td><?= $r['tagged_users'] ?></td>
                            <td><?= $r['posts_timestamp'] ?></td>
                        </tr>
                        <!-- endwhile  -->
                    <?php endforeach ?>
                </tbody>
            </table>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>comment_id</th>
                        <th>user_id</th>
                        <th>post_id</th>
                        <th>content</th>
                        <th>comment_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row_cm as $r_cm) : ?>
                        <tr>
                            <td><?= $r_cm['comment_id'] ?></td>
                            <td><?= $r_cm['user_id'] ?></td>
                            <td><?= $r_cm['post_id'] ?></td>
                            <td><?= $r_cm['content'] ?>
                                <?php
                                $commentId = $r_cm['comment_id'];
                                ?>
                                <button id="showCmRp-<?= $commentId ?>">check details</button>
                                <div style="display:none" id="detailCmRp-<?= $commentId ?>">
                                    <?php foreach ($row_reply as $r_reply) {
                                        if ($r_cm['post_id'] === $r_reply['post_id']) {
                                            echo $r_reply['content'] . $r_reply['post_id'] . "<br>";
                                        }
                                    } ?>
                                </div>
                            </td>
                            <td><?= $r_cm['comment_timestamp'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(post_id) {
        if (confirm(`是否要刪除編號為${post_id}的資料?`)) {
            location.href = `post-delete.php?post_id=${post_id}`;
        }
    }
    // post開啟comment
    <?php foreach ($rows as $r) : ?>
        let showCm<?= $r['post_id'] ?> = document.querySelector('#showCm-<?= $r['post_id'] ?>');
        let detailCm<?= $r['post_id'] ?> = document.querySelector('#detailCm-<?= $r['post_id'] ?>');

        showCm<?= $r['post_id'] ?>.addEventListener('click', function() {
            if (detailCm<?= $r['post_id'] ?>.style.display === 'none') {
                detailCm<?= $r['post_id'] ?>.style.display = 'block';
            } else {
                detailCm<?= $r['post_id'] ?>.style.display = 'none';
            }
        });
    <?php endforeach ?>

    // comment開啟comment_reply
    <?php foreach ($row_cm as $r_cm) : ?>
        let showCmRp<?= $r_cm['comment_id'] ?> = document.querySelector('#showCmRp-<?= $r_cm['comment_id'] ?>');
        let detailCmRp<?= $r_cm['comment_id'] ?> = document.querySelector('#detailCmRp-<?= $r_cm['comment_id'] ?>');

        showCmRp<?= $r_cm['comment_id'] ?>.addEventListener('click', function() {
            if (detailCmRp<?= $r_cm['comment_id'] ?>.style.display === 'none') {
                detailCmRp<?= $r_cm['comment_id'] ?>.style.display = 'block';
            } else {
                detailCmRp<?= $r_cm['comment_id'] ?>.style.display = 'none';
            }
        });
    <?php endforeach ?>
</script>
<?php include __DIR__ . '/parts/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>