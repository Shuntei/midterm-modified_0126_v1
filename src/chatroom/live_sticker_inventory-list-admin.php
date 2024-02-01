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

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if (isset($_GET['sort'])) {
    $selectedSort = $_GET["sort"];
    switch ($selectedSort) {
        case 'id_descend':
            $sortColumn = "sticker_inventory_id";
            $sortDisplay = "DESC";
            break;
        case 'cost_descend':
            $sortColumn = "sticker_cost";
            $sortDisplay = 'DESC';
            break;
        case 'cost_ascend':
            $sortColumn = "sticker_cost";
            $sortDisplay = 'ASC';
            break;
        default:
            $sortColumn = "sticker_inventory_id";
            $sortDisplay = 'ASC';
            break;
    }
} else {
    $sortColumn = "sticker_inventory_id";
    $sortDisplay = 'ASC';
}

$search = isset($_GET['searchbar']) ? $_GET['searchbar'] : "";
$searching_sql = "WHERE sticker_title LIKE'%" . $search . "%'";

if (empty($search)) {
    $searching_sql = "";
}

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf("SELECT * FROM live_sticker_inventory %s ORDER BY %s %s LIMIT %s, %s", $searching_sql, $sortColumn, $sortDisplay, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include('./../package/packageUp.php') ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<style>
    .photo {
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

    .distance {
        margin-left: 10px;
    }

    ul.li distance {
        line-height: 100%;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- 功能欄位在這裡 -->
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
            <nav aria-label="Page navigation example">
                <ul class="pagination mt-2 mb-2">
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
                    <form method="GET">
                        <input type="text" id="searchbar" name="searchbar" class="searchbar distance">
                        <button type="submit" id="submit" name="submit" class="submit">搜尋</button>
                    </form>
                    <form method="GET">
                        <select name="sort" id="sort" onchange="changeUrl()">
                            <option value="" selected disabled>選取排序</option>
                            <option value="id_descend">編碼大到小</option>
                            <option value="cost_descend">金額大到小</option>
                            <option value="cost_ascend">金額小到大</option>
                        </select>
                    </form>
        </div>
        <!-- 功能欄位結束了 -->

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
<?php include('./../package/packageDown.php') ?>
<?php include __DIR__ . '/parts/scripts.php' ?>

<script>
    function delete_one(
        sticker_inventory_id) {
        if (confirm(`是否要刪除編號為${sticker_inventory_id}的資料?`)) {
            location.href = `live_sticker_inventory-delete.php?sticker_inventory_id=${sticker_inventory_id}`;
        }
    }

    let searchbar = document.querySelector('.searchbar');
    searchbar.addEventListener("submit", event => {

    })

    function changeUrl() {
        let sort = document.getElementById('sort')
        let value = sort.value
        window.location.href = "live_sticker_inventory-list-admin.php?sort=" + value
    }
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>