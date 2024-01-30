<?php
if (empty($pageName)) {
  $pageName = '';
}
?>
<div class="container-fluid px-0 mb-3">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./posts-add.php">發文</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./comments-list-no-admin.php">Comment</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./comments-reply-list-no-admin.php">Comment_reply</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./posts-list-no-admin.php">Post</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./public-board.php">Public Board</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./list.php">列表</a>
          </li>
        </ul>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if (isset($_SESSION['admin'])) : ?>
            <li class="nav-item">
              <!-- <a class="nav-link">暱稱</a> -->
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
        </ul>
      </div>
    </div>
  </nav>

</div>