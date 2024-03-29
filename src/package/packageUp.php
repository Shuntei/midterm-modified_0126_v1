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
          <h1 class="welcome-text">
            <?php
            if (isset($_SESSION['admin'])) {
              echo $_SESSION['admin']['role'];
            } elseif (isset($_SESSION['moderator'])) {
              echo $_SESSION['moderator']['role'];
            } elseif (isset($_SESSION['viewer'])) {
              echo $_SESSION['viewer']['role'];
            } else {
              echo 'Sign in to start';
            }
            ?>
          </h1>
          <h3 class="welcome-sub-text"></h3>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
        <span class="mdi mdi-menu"></span>
      </button>
      <?php if (isset($_SESSION['admin']) || isset($_SESSION['moderator']) || isset($_SESSION['viewer'])) : ?>
        <div class="ms-auto align-items-center d-flex logout-btn">
          <a href="/midterm/src/member/logout.php" class="logout-link">Logout</a>
        </div>
      <?php endif; ?>
    </div>

  </nav>
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item nav-category">Forms and Datas</li>
        <?php if (isset($_SESSION['admin'])) { ?>

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
        <?php
        }
        ?>
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
              <li class="nav-item"> <a class="nav-link" href="/midterm/src/socialNetwork/posts-list-no-admin.php">論壇</a></li>
              <li class="nav-item"> <a class="nav-link" href="/midterm/src/socialNetwork/friends.php">好友</a></li>
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
              <li class="nav-item"> <a class="nav-link" href="/midterm/src/chatroom/live_get_point-list-admin.php">點數取得紀錄</a></li>
              <li class="nav-item"> <a class="nav-link" href="/midterm/src/chatroom/live_sticker_inventory-list-admin.php">貼圖管理</a></li>
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