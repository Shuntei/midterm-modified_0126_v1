<?php

require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';


$sql = sprintf("SELECT ca_cart.cart_id, ca_cart.user_id, ca_cart.item_id, ca_cart.quantity, ca_merchandise.item_name, ca_merchandise.unit_price ,(ca_cart.quantity * ca_merchandise.unit_price) AS total_price
                FROM ca_cart 
                LEFT JOIN ca_merchandise ON ca_cart.item_id = ca_merchandise.item_id 
                ORDER BY ca_cart.cart_id DESC ");

// $sql = sprintf("SELECT cart_id, user_id, item_id, quantity, ca_merchandise.item_name, ca_merchandise.unit_price FROM ca_cart LEFT JOIN ca_merchandise ON ca_cart.item_id = ca_merchandise.item_id ORDER BY cart_id DESC ");
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();



//$sql = sprintf("SELECT * FROM ca_cart ORDER BY cart_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
//$stmt = $pdo->query($sql);
//$rows = $stmt->fetchAll();
//老師寫的

//$stmt = $pdo->query("SELECT * FROM ca_cart LIMIT 0, 20");


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
        <div class="main-panel overflow-auto" style="height: 0px;">
            <!-- 這裡引入 -->

            <div class="container-fluid my-3">
                <div class="row">
                    <div class="col">
                        <!-- <?= "$totalRows, $totalPages" ?> -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Cart</h4>
                                <p class="card-description">

                                </p>
                                <div class="nav-item me-4">
                                    <button type="button" class="btn btn-outline-secondary"><a class="nav-link" href="./ca_cart_add.php">新增</a></button>
                                </div>
                                <div class="container-fluid">
                                    <!-- <nav class="navbar navbar-expand-lg bg-light rounded">
                            <div class="container-fluid">

                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        
                                        <li class="nav-item me-4">
                                            <button type="button" class="btn btn-outline-secondary"><a class="nav-link" href="./ca_cart_add.php">新增</a></button>
                                        </li>
                                        <!--  page navigation start-->
                                    <!-- <nav aria-label="Pagination example">
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
                                        </nav> -->
                                    <!-- <div class="mx-3 mb-3">
                                            <form method="get" action="ca_cart_list_admin.php">
                                                <input type="text" class="search_byname" name="user_id" id="search_byname" placeholder="請輸入user_id" aria-describedby="button-addon2" value="<?= isset($_GET['user_id']) ? $_GET['user_id'] : "" ?>">
                                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">搜尋</button>
                                            </form>
                                        </div> -->
                                    <!--  pagination end-->
                                    <!-- </ul>
                          
                                </div>
                            </div>
                        </nav>  -->

                                </div>
                                <table class="table table-hover" data-toggle="table" data-pagination="true" data-search="true" data-show-search-clear-button="true" data-show-toggle="true" data-show-columns="true" data-show-columns-toggle-all="true" data-search-highlight="true">
                                    <thead>
                                        <tr>
                                            <th><i class="fa-solid fa-trash-can"></i></th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter"> 購物車ＩＤ</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">使用者ＩＤ</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">商品ＩＤ</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">商品名稱</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">數量</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">單價</th>
                                            <th data-sortable="true" data-search-highlight-formatter="customSearchFormatter">總價</th>
                                            <th><i class="fa-solid fa-pen-to-square"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- while($r = $stmt->fetch()):  -->
                                        <?php foreach ($rows as $r) : ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkbox-cart" data-cartid="<?= $r['cart_id'] ?>">
                                                </td>
                                                </td>
                                                <td><?= $r['cart_id'] ?></td>
                                                <td><?= $r['user_id'] ?></td>
                                                <td><?= $r['item_id'] ?></td>
                                                <td><?= $r['item_name'] ?></td>
                                                <td><?= $r['quantity'] ?></td>
                                                <td><?= $r['unit_price'] ?></td>
                                                <td><?= $r['total_price'] ?></td>
                                                <!-- <td><?= htmlentities($r['address']) ?></td> -->
                                                <!-- <td><?= strip_tags($r['address']) ?></td> -->
                                                <td>
                                                    <a href="ca_cart_edit.php?cart_id=<?= $r['cart_id'] ?>">
                                                        <i class="fa-solid fa-pen-to-square  text-secondary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- endwhile  -->
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-danger" id="delete-selected-cart">刪除勾選</button>
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
            <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>

            <script>
                $("#delete-selected-cart").click(function() {
                    var selectedCarts = [];
                    $(".checkbox-cart:checked").each(function() {
                        selectedCarts.push($(this).data("cartid"));
                    });

                    if (selectedCarts.length > 0 && confirm("是否要刪除所選項目?")) {
                        // 使用 AJAX 進行刪除
                        $.ajax({
                            url: "ca_cart_delete.php",
                            method: "POST", // 或 "GET"，取決於你的後端設置
                            data: {
                                cart_ids: selectedCarts
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

                function delete_one(cart_id) {
                    if (confirm(`是否要刪除編號為${cart_id}的資料?`)) {
                        location.href = `ca_cart_delete.php?cart_id=${cart_id}`;
                    }
                }

                document.querySelector('form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    let name_value = search_byname.value;
                    location.href = "ca_cart_list_admin.php?user_id=" + name_value;
                });

                function customSearchFormatter(value, searchText) {
                    return value.toString().replace(new RegExp('(' + searchText + ')', 'gim'), '<span style="background-color: pink;border: 1px solid red;border-radius:90px;padding:4px">$1</span>')
                }
            </script>

            <?php include __DIR__ . '/parts/html-foot.php' ?>