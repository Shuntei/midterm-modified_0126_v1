<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'list';
$title = 'comments_reply';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_comments_reply";

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
    $sql = sprintf("SELECT * FROM sn_comments_reply ORDER BY cr_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
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
                        <th>cr_id</th>
                        <th>user_id</th>
                        <th>post_id</th>
                        <th>content</th>
                        <th>parent_id</th>
                        <th>comment_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><?= $r['cr_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= $r['post_id'] ?></td>
                            <td><?= $r['content'] ?></td>
                            <td><?= $r['parent_id'] ?></td>
                            <td><?= $r['comment_timestamp'] ?></td>
                            <!-- <td><?= htmlentities($r['address']) ?></td> -->
                            <!-- <td><?= strip_tags($r['address']) ?></td> -->
                        </tr>
                        <!-- endwhile  -->
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- <prev><?php
                print_r($stmt->fetch());
                print_r($stmt->fetch());
                ?></prev> -->
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(sid) {
        if (confirm(`是否要刪除編號為${sid}的資料?`)) {
            location.href = `delete.php?cr_id=${sid}`;
        }
    }
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>