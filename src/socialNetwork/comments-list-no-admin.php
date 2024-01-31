<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'list';
$title = 'comments';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_comments";

// $t_stmt = $pdo->query($t_sql);
// $row = $t_stmt->fetch(PDO::FETCH_NUM);
$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM sn_comments ORDER BY comment_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>

<!-- 抓取reply內容 -->
<?php
$sql_reply = "SELECT * FROM sn_comments_reply";
$stmt = $pdo->query($sql_reply);
$row_reply = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo json_encode($row_reply);
?>
<!-- 抓取結束 -->
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
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
                        <th>comment_id</th>
                        <th>user_id</th>
                        <th>post_id</th>
                        <th>content</th>
                        <th>comment_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><?= $r['comment_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= $r['post_id'] ?></td>
                            <td><?= $r['content'] ?>
                                <?php
                                $commentId = $r['comment_id'];
                                ?>
                                <button id="show-<?= $commentId ?>">查看回覆</button>
                                <div style="display:none" id="detail-<?= $commentId ?>">
                                    <?php foreach ($row_reply as $r_reply) {
                                        if ($r['comment_id'] === $r_reply['parent_id']) {
                                            echo $r_reply['content'] . $r_reply['parent_id'] . "<br>";
                                        }
                                    } ?>
                                </div>
                            </td>
                            <td><?= $r['comment_timestamp'] ?></td>
                        </tr>
                        <!-- endwhile  -->
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(sid) {
        if (confirm(`是否要刪除編號為${sid}的資料?`)) {
            location.href = `delete.php?comment_id=${sid}`;
        }
    }

    <?php foreach ($rows as $r) : ?>
        let show<?= $r['comment_id'] ?> = document.querySelector('#show-<?= $r['comment_id'] ?>');
        let detail<?= $r['comment_id'] ?> = document.querySelector('#detail-<?= $r['comment_id'] ?>');

        show<?= $r['comment_id'] ?>.addEventListener('click', function() {
            if (detail<?= $r['comment_id'] ?>.style.display === 'none') {
                detail<?= $r['comment_id'] ?>.style.display = 'block';
            } else {
                detail<?= $r['comment_id'] ?>.style.display = 'none';
            }
        });
    <?php endforeach ?>
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>