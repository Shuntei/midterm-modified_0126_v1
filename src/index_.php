<?php $db_host = 'localhost';
$db_name = 'midterm_db';
$db_user = 'root';
$db_pass = '';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
// $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

$pdo_options = [
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];
// $stmt = $pdo->query("SELECT * FROM address_book LIMIT 2");

try {
  $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
} catch (PDOException $e) {
  echo $e->getMessage();
}

# 啟動 session 的功能
if (!isset($_SESSION)) {
  session_start();
}
$pageName = 'home';
$title = '首頁';
?>

<?php
$title = isset($title) ? $title . '-MFEE47_02' : 'MFEE47_02'
?>
<!DOCTYPE html>
<html lang="zh">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
  <style>
    .navbar-nav .nav-link.active {
      background-color: rgb(13, 110, 253);
      border-radius: 10px;
      font-weight: 800;
      color: white;
    }

    form .mb-3 .form-text {
      color: red;
    }
  </style>
</head>

<body>
  <div class="container-scroller ">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 d-flex align-items-top flex-row fixed-top">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="./index_.php">
            <img src="./assets/images/ruined.png" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
      <ul class="navbar-nav">
        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
          <h1 class="welcome-text"><?= isset($_SESSION['admin']['userName']) ? $_SESSION['admin']['userName'] : 'Hello' ?><span class="text-black fw-bold"></span></h1>
          <h3 class="welcome-sub-text"></h3>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
      </button>
      <?php if(isset($_SESSION['admin']) || isset($_SESSION['moderator'])) : ?>
      <div class="ms-auto align-items-center d-flex logout-btn">
        <a href="logout.php" class="logout-link">Logout</a>
      </div>
      <?php endif; ?>
    </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-category">Forms and Datas</li>
          <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#account-key" aria-expanded="false" aria-controls="account-key">
            <i class="menu-icon mdi mdi-account-key"></i>
            <span class="menu-title">Settings</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="account-key">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"><a class="nav-link" href="/midterm/src/settings/settings.php">Admin Settings</a></li>
            </ul>
          </div>
        </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="menu-icon mdi mdi-card-text-outline"></i>
              <span class="menu-title">Member</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="./member/member.php">User</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="./socialNetwork/posts-list-no-admin.php">論壇</a></li>
                <li class="nav-item"> <a class="nav-link" href="./socialNetwork/friends.php">好友</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="./chatroom/live_get_point-list-admin.php">點數取得紀錄</a></li>
                <li class="nav-item"> <a class="nav-link" href="./chatroom/live_sticker_inventory-list-admin.php">貼圖管理</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="./shoppingCart/ca_merchandise_list_admin.php">商品列表</a></li>
                <li class="nav-item"> <a class="nav-link" href="./shoppingCart/ca_cart_list_admin.php">購物車</a></li>
                <li class="nav-item"> <a class="nav-link" href="./shoppingCart/ca_orders_list_admin.php">訂單</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="./tour/tr_tour_list_admin.php">Tour</a></li>
                <li class="nav-item"> <a class="nav-link" href="./tour/tr_tour_comment_list_admin.php">TourComment</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel overflow-auto" style="height: 100vh;">
        <!-- 這裡引入 -->
        <?php
        if (empty($pageName)) {
          $pageName = '';
        }
        ?>

        <div class="container">
          <h1>歡迎工商置入</h1>
        </div>

        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/proBanner.js"></script>
  <!-- <script src="../../assets/js/Chart.roundedBarCharts.js"></script> -->
  <!-- End custom js for this page-->
</body>

</html>