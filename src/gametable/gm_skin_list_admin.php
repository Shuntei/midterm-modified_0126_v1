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

$t_sql = "SELECT COUNT(1) FROM gm_skin";

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
  $sql = sprintf("SELECT * FROM gm_skin ORDER BY skin_id ASC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll();
}

$stmt = $pdo->query("SELECT * FROM gm_skin LIMIT 0, 20");
$rows = $stmt->fetchAll();

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include './../package/packageUp.php' ?>
<?php
if (empty($pageName)) {
  $pageName = '';
}
?>

<!-- partial -->

<div class="container-fluid content-wrapper">
  <div class="row">
    <div class="col-sm-12">
      <div class="home-tab">
        <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-5">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link ps-0" id="home-tab" href="gm_coupon_list_admin.php" role="tab"
                aria-selected="overview">Coupon</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" href="gm_user_getcoupon_list_admin.php" role="tab"
                aria-selected="overview">User Get Coupon</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="contact-tab" href="gm_user_achieved_list_admin.php" role="tab"
                aria-selected="overview">User Achieved</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="more-tab" href="gm_mission_list_admin.php" role="tab"
                aria-selected="overview">Mission</a>
            </li>
            <li class="nav-item">
              <a class="nav-link border-0 active" id="more-tab" aria-current="page" href="#" role="tab"
                aria-selected="true">Skin</a>
            </li>
          </ul>
          <div>
            <div class="btn-wrapper">
              <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>

              <a href="gm_skin_add.php" class="btn btn-primary text-white me-0"><i class="mdi mdi-plus fw-bold"></i>
                Add</a>
            </div>
          </div>
        </div>

        <!-- get coupon table start -->
        <div class="col-12 grid-margin stretch-card" id="user_achieved_id">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Skin Table</h4>
              <p class="card-description">
                Add class <code>.table-hover</code>
              </p>
              <div class="table-responsive">
                <table class="table table-hover mb-4">
                  <thead>
                    <tr>
                      <th><i class="fa-solid fa-trash-can"></i></th>
                      <th>Skin ID</th>
                      <th>Skin Name</th>
                      <th>Skin Model ID</th>
                      <th>Role</th>
                      <th>Upload File</th>
                      <th>Last Update</th>
                      <th><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r): ?>
                      <tr>
                        <td>
                          <a href="javascript: delete_one(<?= $r['skin_id'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                          </a>
                        </td>
                        <td>
                          <?= $r['skin_id'] ?>
                        </td>
                        <td>
                          <?= $r['skin_name'] ?>
                        </td>
                        <td>
                          <?= $r['skin_model_id'] ?>
                        </td>
                        <td>
                          <?= $r['role'] ?>
                        </td>
                        <td>
                          <?= $r['file'] ?>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                              class="mdi mdi-eye"></i>View</button>

                          <!-- Modal -->
                          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <canvas class="illo" width="300" height="300"></canvas>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <?= $r['skin_last_update'] ?>
                        </td>
                        <td>
                          <a href="gm_skin_edit.php?skin_id=<?= $r['skin_id'] ?>">
                            <i class="mdi mdi-lead-pencil"></i>
                          </a>
                        </td>
                      </tr>
                      <!-- endwhile  -->
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>


              <nav aria-label="Page navigation Basic example" role="group" class="flex-row my-3">
                <ul class="btn-group pagination d-flex justify-content-center m-3">
                  <li class="page-item">
                    <a class="page-link btn btn-outline-primary" href="?page=<?= $page - 1 ?>">
                      <i class="fa-solid fa-angle-left" href="?page"></i>
                    </a>
                  </li>
                  <?php for ($i = $page - 5; $i <= $page + 5; $i++):
                    if ($i >= 1 and $i <= $totalPages): ?>
                      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link btn btn-outline-primary" href="?page=<?= $i ?>">
                          <?= $i ?>
                        </a>
                      </li>
                    <?php endif;
                  endfor; ?>

                  <li class="page-item">
                    <a class="page-link btn btn-outline-primary" href="?page=<?= $page + 1 ?>">
                      <i class="fa-solid fa-angle-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        <!-- getCoupon-table end -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a
                href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from
              BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights
              reserved.</span>
          </div>
        </footer>
        <!-- partial -->

        <!-- main-panel ends -->


        <?php include './../package/packageDown.php' ?>
        <?php include __DIR__ . '/parts/scripts.php' ?>
        <script>
          function delete_one(skin_id) {
            if (confirm(`是否要刪除編號為${skin_id}的資料?`)) {
              location.href = `gm_skin_delete.php?skin_id=${skin_id}`;
            }
          }
        </script>
        <!-- <script src="/js/boilerplate.js"></script>
          <script src="/js/canvas-renderer.js"></script>
          <script src="/js/vector.js"></script>
          <script src="/js/anchor.js"></script>
          <script src="/js/path-command.js"></script>
          <script src="/js/shape.js"></script>
          <script src="/js/dragger.js"></script>
          <script src="/js/illustration.js"></script>
          <script src="/js/kid-kit.js"></script> -->

        <?php include __DIR__ . '/parts/html-foot.php' ?>