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

$t_sql = "SELECT COUNT(1) FROM ca_merchandise";
$itemNameCondition = '';

if (isset($_GET['item_name']) && $_GET['item_name'] !== "") {
    $itemName = $_GET['item_name'];
    $itemNameCondition = " WHERE item_name LIKE :item_name";
}

$t_sql .= $itemNameCondition;

$stmt = $pdo->prepare($t_sql);

if ($itemNameCondition) {
    $stmt->bindValue(':item_name', '%' . $itemName . '%', PDO::PARAM_STR);
}

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];


if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages . '&item_name=' . urlencode($itemName));
        exit;
    }
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

switch ($sort) {
    case 'item_id_asc':
        $orderBy = " ORDER BY item_id ASC";
        break;
    case 'item_id_desc':
        $orderBy = " ORDER BY item_id DESC";
        break;
    default:
        $orderBy = " ORDER BY item_id DESC";
}

$sql = sprintf("SELECT * FROM ca_merchandise %s %s LIMIT %s, %s", $itemNameCondition, $orderBy, ($page - 1) * $perPage, $perPage);
$stmt = $pdo->prepare($sql);

if ($itemNameCondition) {
    $stmt->bindValue(':item_name', '%' . $itemName . '%', PDO::PARAM_STR);
}

$stmt->execute();
$rows = $stmt->fetchAll();


//$sql = sprintf("SELECT * FROM ca_merchandise ORDER BY item_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
//$stmt = $pdo->query($sql);
//$rows = $stmt->fetchAll();
//老師寫的

//$stmt = $pdo->query("SELECT * FROM ca_merchandise LIMIT 0, 20");


?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<?php
if (empty($pageName)) {
    $pageName = '';
}
?>

<div class="container-fluid my-3">
    <div class="row">
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Merchandise</h4>
                    <p class="card-description">

                    </p>
                    <div class="container-fluid">
                        <nav class="navbar navbar-expand-lg bg-light rounded">
                            <div class="container-fluid ">

                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <!-- <li class="nav-item">
                                            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./ca_merchandise_list_admin.php">列表</a>
                                        </li> -->
                                        <li class="nav-item me-4">
                                            <button type="button" class="btn btn-outline-secondary"><a class="nav-link" href="./ca_merchandise_add.php">新增</a></button>
                                        </li>
                                        <!--  page navigation start-->
                                        <nav aria-label="Pagination example">
                                            <ul class="pagination mx-3">
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=1">
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
                                        <div class="mx-3 mb-3">
                                            <form method="get" action="ca_merchandise_list_admin.php">
                                                <input type="text" class="search_byname" name="item_name" id="search_byname" placeholder="請輸入商品名" aria-describedby="button-addon2" value="<?= isset($_GET['item_name']) ? $_GET['item_name'] : "" ?>">
                                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">搜尋</button>
                                            </form>
                                        </div>
                                        <!--  pagination end-->
                                    </ul>
                                    <!-- <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if (isset($_SESSION['admin'])) : ?>
            <li class="nav-item">
              <!-- <a class="nav-link">暱稱</a> 
                <a class="nav-link"><?= $_SESSION['admin']['nickname'] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">登出</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'login' ? 'active' : '' ?>" href="./login.php">登入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'register' ? 'active' : '' ?>" href="./register.php">註冊</a>
                </li>
            <?php endif ?>
            </ul> -->
                                </div>
                            </div>
                        </nav>

                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fa-solid fa-trash-can"></i></th>
                                <th>#
                                    <a href="ca_merchandise_list_admin.php?sort=item_id_desc"><i class="fa fa-arrow-down"></i></a>
                                    <a href="ca_merchandise_list_admin.php?sort=item_id_asc"><i class="fa fa-arrow-up"></i></a>
                                </th>
                                <th>item_name</th>
                                <th>quantity</th>
                                <th>category_id</th>
                                <th>description</th>
                                <th>unit_price</th>
                                <th>product_img </th>

                                <th><i class="fa-solid fa-pen-to-square"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- while($r = $stmt->fetch()):  -->
                            <?php foreach ($rows as $r) : ?>
                                <tr>
                                    <td>
                                        <a href="javascript: delete_one(<?= $r['item_id'] ?>)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                    <td><?= $r['item_id'] ?></td>
                                    <td><?= $r['item_name'] ?></td>
                                    <td><?= $r['quantity'] ?></td>
                                    <td><?= $r['category_id'] ?></td>
                                    <td><?= $r['description'] ?></td>
                                    <td><?= $r['unit_price'] ?></td>
                                    <td><?= $r['product_img'] ?></td>
                                    <!-- <td><?= htmlentities($r['address']) ?></td> -->
                                    <!-- <td><?= strip_tags($r['address']) ?></td> -->
                                    <td>
                                        <a href="ca_merchandise_edit.php?item_id=<?= $r['item_id'] ?>">
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
        </div>
    </div>
    <!-- <prev><?php
                print_r($stmt->fetch());
                print_r($stmt->fetch());
                ?></prev> -->
</div>
<?php include __DIR__ . '/../parts/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(item_id) {
        if (confirm(`是否要刪除編號為${item_id}的資料?`)) {
            location.href = `ca_merchandise_delete.php?item_id=${item_id}`;
        }
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();
        let name_value = search_byname.value;
        location.href = "ca_merchandise_list_admin.php?item_name=" + name_value;
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>