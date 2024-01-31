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

$order = isset($_GET['order']) ? $_GET['order'] : '';

// 切換排序順序
$newOrder = ($order === 'asc') ? 'desc' : 'asc';

// 生成帶有新排序順序的 URL(第二種寫法,a href要帶入$toggleUrl)
// $toggleUrl = $_SERVER['PHP_SELF'] . "?order=$newOrder";

$toggle = isset($_GET['toggleImg']) ? $_GET['toggleImg'] :'';
$imgChange = ($toggle === "<i class='fa-solid fa-down-long'></i>") ? "<i class='fa-solid fa-up-long'></i>": "<i class='fa-solid fa-down-long'></i>";

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM sn_posts ORDER BY post_id $newOrder LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<div class="container-fluid overflow-auto">
    <div class="row">
        <div class="col">
            <h3 class="my-2 text-center fw-bold">Post</h3>
            <nav aria-label="Page navigation example" class="d-flex justify-content-between">
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
                <ul>
                    <li>
                        <nav class="navbar pt-0">
                            <div class="container-fluid">
                            <form class="d-flex">
                                <input class="form-control search-custom me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success btn-sm border-none" type="submit">Search</button>
                            </form>
                            </div>
                        </nav>
                    </li>
                </ul>
            </nav>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                        <th><i class="fa-solid fa-pen-to-square"></i></th>
                        <th>post_id　
                            <a href="?order=<?= $newOrder; ?>&toggleImg=<?= $imgChange; ?>" class="text-decoration-none">
                                <?= $imgChange?>
                            </a>
                        </th>
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
                                <a href="post-edit.php?post_id=<?= $r['post_id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td><?= $r['post_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td class="d-flex justify-content-between align-items-center">
                                <?= $r['content'] ?>
                                <?php
                                $postId = $r['post_id'];
                                ?>
                                <!-- modal -->
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailCm-<?= $postId ?>" id="showCm-<?= $postId ?>">
                                    查看留言
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="detailCm-<?= $postId ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">留言</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php foreach ($row_cm as $r_cm) : ?>
                                                    <?php if ($r['post_id'] === $r_cm['post_id']) : ?>
                                                        <?= $r_cm['content'] . $r_cm['post_id'] . "<br>"; ?>
                                                        <!-- 用api寫 -->
                                                        <button onclick="checkReply(<?= $r_cm['comment_id'] ?>)" style="margin-top: 5px" class="border-1">查看回覆</button>
                                                        <?= "comment id:" . $r_cm['comment_id'] ?>
                                                        <div id='showReply<?= $r_cm['comment_id'] ?>' style="margin-top: 5px;white-space: normal;"></div>
                                                        <!-- api結束 -->
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
            <h4 class="my-3 text-center fw-bold">留言總覽</h4>
            <table class="table table-bordered table-striped mt-3">
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
                            <td><?= $r_cm['content'] ?></td>
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

    const checkReply = (commentId) => {
        event.preventDefault();

        fetch(`posts-list-no-admin-api.php?comment_id=${commentId}`)
            .then(response => response.json())
            .then((replies) => {
                getReply(replies);
            }).catch((e) => {
                console.log('Error fetching parent_id:', e);
            });
    }

    const getReply = (replies) => {
        console.log('e', replies);

        let showReply = document.querySelector(`#showReply${replies[0].parent_id}`);
        showReply.innerHTML = "";

        // 將獲取的回覆添加到留言下
        replies.map((items) => {
            showReply.innerHTML += `
                    <div>cr_id: ${items.cr_id}</div>
                    <div>user_id: ${items.user_id}</div>
                    <div>post_id: ${items.post_id}</div>
                    <div>content: ${items.content}</div>
                    <div>parent_id: ${items.parent_id}</div>
                    <div>comment_timestamp: ${items.comment_timestamp}</div>
                    <hr>
                `;
        });
    }

</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>