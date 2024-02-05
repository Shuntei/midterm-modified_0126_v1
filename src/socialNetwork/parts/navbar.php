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
            <a class="nav-link <?= $pageName == 'noadd' ? 'active' : '' ?>" href="./posts-add.php">發文</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./comments-list-no-admin.php">Comments</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./comments-reply-list-no-admin.php">Comment_replies</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./posts-list-no-admin.php">Posts</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./public-board.php">Public Boards</a>
          </li>
          <li class="nav-item me-2">
            <a class="nav-link <?= $pageName == 'nolist' ? 'active' : '' ?>" href="./friends.php">Friends</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</div>