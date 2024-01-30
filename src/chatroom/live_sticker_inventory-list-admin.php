<?php

require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM live_sticker_inventory";

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
    $sql = sprintf("SELECT * FROM live_sticker_inventory ORDER BY 
    sticker_inventory_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<style>
    .photo{
        width: 100px;
        height: 100px;
        overflow: hidden;
        /* border: 1px solid black; */
    }

    .table td img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#">
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
                                <a class="page-link" href="?page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                    <?php endif;
                    endfor; ?>

                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                        <th>#</th>
                        <th>貼圖名稱</th>
                        <th>貼圖費用</th>
                        <th>貼圖連結</th>
                        <th><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: delete_one(<?= $r['sticker_inventory_id'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td>
                                <?= $r['sticker_inventory_id'] ?>
                            </td>
                            <td>
                                <?= $r['sticker_title'] ?>
                            </td>
                            <td>
                                <?= $r['sticker_cost'] ?>
                            </td>
                            <td>
                                <div class="photo">
                                    <img src="./imgs/<?= $r['sticker_pic'] ?>">
                                </div>
                            </td>
                            <td>
                                <a href="live_sticker_inventory-edit.php?sticker_inventory_id=<?= $r['sticker_inventory_id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
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
<?php include __DIR__ . '/parts/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>

<script>
    function delete_one(
        sticker_inventory_id) {
        if (confirm(`是否要刪除編號為${sticker_inventory_id}的資料?`)) {
            location.href = `live_sticker_inventory-delete.php?sticker_inventory_id=${sticker_inventory_id}`;
        }
    }
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>