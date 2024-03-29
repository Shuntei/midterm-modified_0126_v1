<?php


require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';


// 獲取篩選條件的價格值
// $price1 = isset($_GET['price1']) ? intval($_GET['price1']) : 0;
// $price2 = isset($_GET['price2']) ? intval($_GET['price2']) : PHP_INT_MAX; // 使用 PHP_INT_MAX 作為預設最大值

// // 修正 SQL 查詢，加入價格區間條件
// $sql = sprintf("SELECT * FROM ca_merchandise WHERE unit_price BETWEEN :price1 AND :price2 ORDER BY item_id DESC");
// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':price1', $price1, PDO::PARAM_INT);
// $stmt->bindValue(':price2', $price2, PDO::PARAM_INT);
// $stmt->execute();

// $rows = $stmt->fetchAll();
// $sql = sprintf("SELECT * FROM ca_merchandise ORDER BY item_id DESC ");
// $sql = sprintf("SELECT * FROM ca_merchandise ORDER BY item_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
// $stmt = $pdo->query($sql);
// $rows = $stmt->fetchAll();
// 老師寫的
// $stmt = $pdo->query("SELECT * FROM ca_merchandise LIMIT 0, 20");

$sql = sprintf("SELECT * FROM ca_orders ORDER BY order_id DESC ");
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();

$sql2 = sprintf("SELECT * FROM ca_order_detail ORDER BY order_detail_id DESC ");
$stmt2 = $pdo->query($sql2);
$rows2 = $stmt2->fetchAll();

?>

<?php include __DIR__ . '/parts/html-head.php' ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
<div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 d-flex align-items-top flex-row fixed-top">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="../index_.php">
                    <img src="../assets/images/ruined.png" alt="logo" />
                </a>

            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
            <ul class="navbar-nav">
                <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text">Why are we still here? Just to suffer? <span class="text-black fw-bold"></span></h1>
                    <h3 class="welcome-sub-text"></h3>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class=" page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-category">Forms and Datas</li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                        <i class="menu-icon mdi mdi-card-text-outline"></i>
                        <span class="menu-title">Member</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="form-elements">
                        <ul class="nav flex-column sub-menu">

                            <li class="nav-item"><a class="nav-link" href="/midterm/src/member/member.php">User</a></li>

                        </ul>
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"><a class="nav-link" href="../pages/forms/basic_elements.html">UserPermission</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                        <i class="menu-icon mdi mdi-chart-line"></i>
                        <span class="menu-title">SNS</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="charts">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/socialNetwork/public-board.php">看板分類</a></li>
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/socialNetwork/posts-list-no-admin.php">帖子</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                        <i class="menu-icon mdi mdi-message-text"></i>
                        <span class="menu-title">ChatRoom</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="tables">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/chatroom/live_sticker_inventory-list-admin.php">貼圖管理</a></li>
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/chatroom/live_get_point-list-admin.php">點數消耗紀錄</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#shoppingCart" aria-expanded="false" aria-controls="shoppingCart">
                        <i class="menu-icon mdi mdi-cart-outline"></i>
                        <span class="menu-title">ShoppingCart</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="shoppingCart">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/shoppingCart/ca_merchandise_list_admin.php">商品列表</a></li>
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/shoppingCart/ca_cart_list_admin.php">購物車</a></li>
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/shoppingCart/ca_orders_list_admin.php">訂單</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#game" aria-expanded="false" aria-controls="game">
                        <i class="menu-icon mdi mdi-cake-variant"></i>
                        <span class="menu-title">Game</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="game">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/gametable/gm_coupon_list_admin.php">Coupon</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#tour" aria-expanded="false" aria-controls="tour">
                        <i class="menu-icon mdi mdi-layers-outline"></i>
                        <span class="menu-title">Tour</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="tour">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/tour/tr_tour_list_admin.php">Tour</a></li>
                            <li class="nav-item"> <a class="nav-link" href="/midterm/src/tour/tr_tour_comment_list_admin.php">TourComment</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel overflow-auto" style="height: 0px;">
            <!-- 這裡引入 -->
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
                                <h4 class="card-title">訂單列表</h4>
                                <p class="card-description">
                                <div class="nav-item me-4">
                                    <button type="button" class="btn btn-outline-secondary"><a class="nav-link" href="./ca_category_add.php">新增</a></button>
                                </div>
                                <table class="table table-hover" data-toggle="table" data-pagination="true" data-search="true" data-show-search-clear-button="true" data-show-toggle="true" data-show-columns="true" data-show-columns-toggle-all="true">
                                    <thead>
                                        <tr>
                                            <th><i class="fa-solid fa-trash-can"></i></th>
                                            <th data-sortable="true">訂單ＩＤ</th>
                                            <th data-sortable="true">使用者ＩＤ</th>
                                            <th data-sortable="true">信箱</th>
                                            <th data-sortable="true">電話</th>
                                            <th data-sortable="true">地址</th>
                                            <th data-sortable="true">交易時間</th>
                                            <th>酷碰ＩＤ </th>
                                            <th><i class="fa-solid fa-pen-to-square"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- while($r = $stmt->fetch()):  -->
                                        <?php foreach ($rows as $r) : ?>
                                            <tr>

                                                <td><input type="checkbox" class="checkbox" data-itemid="<?= $r['order_id'] ?>"></td>
                                                <td><?= $r['order_id'] ?></td>
                                                <td><?= $r['user_id'] ?></td>
                                                <td><?= $r['email'] ?></td>
                                                <td><?= $r['phone'] ?></td>
                                                <td><?= $r['address'] ?></td>
                                                <td><?= $r['payment_date'] ?></td>
                                                <td><?= $r['coupon_id'] ?></td>
       
                                                <td>
                                                    <a href="ca_merchandise_edit.php?order_id=<?= $r['order_id'] ?>">
                                                        <i class="fa-solid fa-pen-to-square text-secondary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- endwhile  -->
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-danger" id="delete-selected">刪除勾選</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <prev><?php
                            print_r($stmt->fetch());
                            print_r($stmt->fetch());
                            ?></prev> -->
            </div>


            <div class="container-fluid my-3">
                <div class="row">
                    <div class="col">
                        <!-- <?= "$totalRows, $totalPages" ?> -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">訂單詳細</h4>
                                <p class="card-description">
                                <div class="nav-item me-4">
                                    <button type="button" class="btn btn-outline-secondary"><a class="nav-link" href="./ca_category_add.php">新增</a></button>
                                </div>
                                </p>
                                <div class="container-fluid">
                                    
                                </div>
                                <table class="table table-hover" data-toggle="table" data-pagination="true" data-search="true" data-show-search-clear-button="true" data-show-toggle="true" data-show-columns="true" data-show-columns-toggle-all="true">
                                    <thead>
                                        <tr>
                                            <th><i class="fa-solid fa-trash-can"></i></th>
                                            <th data-sortable="true">訂單詳細ＩＤ</th>
                                            <th data-sortable="true">訂單ＩＤ</th>
                                            <th data-sortable="true">商品ＩＤ</th>
                                            <th data-sortable="true">數量</th>
                                            <th data-sortable="true">使用者ＩＤ</th>
                                            <th><i class="fa-solid fa-pen-to-square"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- while($r = $stmt->fetch()):  -->
                                        <?php foreach ($rows2 as $r2) : ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkbox-category" data-categoryid="<?= $r2['order_detail_id'] ?>">
                                                </td>
                                                <td><?= $r2['order_detail_id'] ?></td>
                                                <td><?= $r2['order_id'] ?></td>
                                                <td><?= $r2['item_id'] ?></td>
                                                <td><?= $r2['quantity'] ?></td>
                                                <td><?= $r2['user_id'] ?></td>
                                                <td>
                                                    <a href="ca_order_detail_edit.php?order_detail_id=<?= $r2['order_detail_id'] ?>">
                                                        <i class="fa-solid fa-pen-to-square text-secondary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- endwhile  -->
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-danger" id="delete-selected-category">刪除勾選</button>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- <prev><?php
                            print_r($stmt->fetch());
                            print_r($stmt->fetch());
                            ?></prev> -->
            </div>
            <?php include __DIR__ . '/../package/packageDown.php' ?>
            <?php include __DIR__ . '/parts/scripts.php' ?>
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
            <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
            </body>

            </html>

            <script>
                $("#delete-selected").click(function() {
                    var selectedItems = [];
                    $(".checkbox:checked").each(function() {
                        selectedItems.push($(this).data("itemid"));
                    });

                    if (selectedItems.length > 0 && confirm("是否要刪除所選項目?")) {
                        // 使用 AJAX 進行刪除
                        $.ajax({
                            url: "ca_merchandise_delete.php",
                            method: "POST", // 或 "GET"，取決於你的後端設置
                            data: {
                                item_ids: selectedItems
                            },
                            success: function(response) {
                                // 刪除成功後的處理，可以根據需要刷新頁面或執行其他操作
                                alert("刪除成功");
                                location.reload(); // 例如刷新頁面
                            },
                            error: function(xhr, status, error) {
                                // 刪除失敗的處理
                                alert("刪除失敗: " + error);
                            }
                        });
                    }
                });

                $("#delete-selected-category").click(function() {
                    var selectedCategories = [];
                    $(".checkbox-category:checked").each(function() {
                        selectedCategories.push($(this).data("categoryid"));
                    });

                    if (selectedCategories.length > 0 && confirm("是否要刪除所選項目?")) {
                        // 使用 AJAX 進行刪除
                        $.ajax({
                            url: "ca_category_delete.php",
                            method: "POST", // 或 "GET"，取決於你的後端設置
                            data: {
                                category_ids: selectedCategories
                            },
                            success: function(response) {
                                // 刪除成功後的處理，可以根據需要刷新頁面或執行其他操作
                                alert("刪除成功");
                                location.reload(); // 例如刷新頁面
                            },
                            error: function(xhr, status, error) {
                                // 刪除失敗的處理
                                alert("刪除失敗: " + error);
                            }
                        });
                    }
                });

                function delete_category(category_id) {
                    if (confirm(`是否要刪除編號為${category_id}的資料?`)) {
                        location.href = `ca_category_delete.php?category_id=${category_id}`;
                    }
                }
            </script>

            <?php include __DIR__ . '/parts/html-foot.php' ?>
