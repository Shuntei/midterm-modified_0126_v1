<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'friends';
$title = 'friends';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_friends";

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
    $sql = sprintf("SELECT * FROM sn_friends ORDER BY friendship_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<div class="container-fluid ">
    <div class="row">
        <div class="col">
        <h3 class="my-2 text-center fw-bold">Friends</h3>
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
                <ul class="d-flex align-items-center">
                    <li><i class="fa-solid fa-user-plus fs-5"></i></li>
                    <li>
                        <nav class="navbar pt-0 bg-white">
                            <div class="container-fluid">
                                <form class="d-flex" method="POST" action="friends.php?">
                                    <input class="form-control search-custom me-2" type="search" name="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-primary btn-sm py-0 border border-white" type="submit"><i class="fa-solid fa-magnifying-glass fs-5"></i></button>
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
                        <th>friendship_id</th>
                        <th>user_id</th>
                        <th>friend_id</th>
                        <th>status</th>
                        <th>friend_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: delete_one(<?= $r['friendship_id'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td><?= $r['friendship_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= $r['friend_id'] ?></td>
                            <td><?= $r['status'] ?></td>
                            <td><?= $r['friend_timestamp'] ?></td>
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
    function delete_one(friendship_id) {
        if (confirm(`是否要刪除編號為${friendship_id}的好友?`)) {
            location.href = `friends-delete.php?friendship_id=${friendship_id}`;
        }
    }
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>