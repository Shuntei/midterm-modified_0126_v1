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
    $sql = sprintf("SELECT * FROM ca_merchandise ORDER BY item_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

// $stmt = $pdo->query("SELECT * FROM ca_merchandise LIMIT 0, 20");
// $rows = $stmt->fetchAll();

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/packageUp.php' ?>
<?php
if (empty($pageName)) {
    $pageName = '';
}
?>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./ca_merchandise_list_admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./ca_merchandise_add.php">新增</a>
                    </li>
                    <!--  page navigation start-->
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
                                <a class="page-link" href="#">
                                    <i class="fa-solid fa-angles-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>

<!--  page navigation end-->
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
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                        <th>#</th>
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
                                <a href="edit.php?item_id=<?= $r['item_id'] ?>">
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
    function delete_one(item_id) {
        if (confirm(`是否要刪除編號為${item_id}的資料?`)) {
            location.href = `ca_merchandise_delete.php?item_id=${item_id}`;
        }
    }
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>