<?php

require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM live_get_point";

// $t_stmt = $pdo->query($t_sql);
// $row = $t_stmt->fetch(PDO::FETCH_NUM);
$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];


if (isset($_GET['sort'])) {
    $selectedSort = $_GET["sort"];
    switch ($selectedSort) {
        case 'id_ascend':
            $sortColumn = "user_id";
            $sortDisplay = "ASC";
            break;
        case 'time_descend':
            $sortColumn = "date_get_point";
            $sortDisplay = 'DESC';
            break;
        case 'point_descend':
            $sortColumn = "received_point";
            $sortDisplay = 'DESC';
            break;
        case 'point_ascend':
            $sortColumn = "received_point";
            $sortDisplay = 'ASC';
            break;
        default:
            $sortColumn = "get_point_id";
            $sortDisplay = 'ASC';
            break;
    }
} else {
    $sortColumn = "get_point_id";
    $sortDisplay = 'ASC';
}


if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM live_get_point ORDER BY 
    $sortColumn $sortDisplay LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts-get-point/html-head.php' ?>
<?php include('./../package/packageUp.php') ?>
<?php include __DIR__ . '/parts-get-point/navbar.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
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
                        <select name="sort" id="sort" onchange="changeUrl()">
                            <option value="" selected disabled>選取排序</option>
                            <option value="id_ascend">用戶ID小到大</option>
                            <option value="time_descend">最新入庫</option>
                            <option value="point_descend">點數大到小</option>
                            <option value="point_ascend">點數小到小</option>
                        </select>
                    </form>
                </ul>
            </nav>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                        <th>#</th>
                        <th>用戶ID</th>
                        <th>獲得點數</th>
                        <th>點數來源</th>
                        <th>獲得時間</th>
                        <th><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: delete_one(<?= $r['get_point_id'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td>
                                <?= $r['get_point_id'] ?>
                            </td>
                            <td>
                                <?= $r['user_id'] ?>
                            </td>
                            <td>
                                <?= $r['received_point'] ?>
                            </td>
                            <td>
                                <?= $r['point_source'] ?>
                            </td>
                            <td>
                                <?= $r['date_get_point'] ?>
                            </td>
                            <td>
                                <a href="live_get_point-edit.php?get_point_id=<?= $r['get_point_id'] ?>">
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
<?php include __DIR__ . '/parts-get-point/scripts.php' ?>

<script>
    function delete_one(
        get_point_id) {
        if (confirm(`是否要刪除編號為${get_point_id}的資料?`)) {
            location.href = `live_get_point-delete.php?get_point_id=${get_point_id}`;
        }
    }

    function changeUrl() {
        let sort = document.getElementById('sort')
        let value = sort.value
        window.location.href = "live_get_point-list-admin.php?sort=" + value
    }
</script>

<?php include __DIR__ . '/parts-get-point/html-foot.php' ?>