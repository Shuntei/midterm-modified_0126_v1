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

$t_sql = "SELECT COUNT(1) FROM gm_coupon";

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
    $sql = sprintf("SELECT * FROM gm_coupon ORDER BY coupon_id ASC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

$stmt = $pdo->query("SELECT * FROM gm_coupon LIMIT 0, 20");
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
                  <!-- page change btn start -->
                  <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-5">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="profile-tab" aria-current="page" href="#" role="tab" aria-selected="true">Coupon</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="home-tab" href="gm_user_getcoupon_list_admin.php" role="tab" aria-selected="overview">User Get Coupon</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" href="gm_user_achieved_list_admin.php" role="tab" aria-selected="overview">User Achieved</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="more-tab" href="gm_mission_list_admin.php" role="tab" aria-selected="overview">Mission</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link border-0" id="more-tab" href="gm_skin_list_admin.php" role="tab" aria-selected="overview">Skin</a>
                    </li>
                  </ul>
                  <div>
                    <div class="btn-wrapper">
                      <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                      <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-download"></i> Export</a>
                      <!-- <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a> -->
                      <a href="gm_coupon_add.php" class="btn btn-primary text-white me-0"><i class="mdi mdi-plus fw-bold"></i> Add</a>
                    </div>
                  </div>
                  </div>
                  <!-- change page btn end -->
                  <div class="col-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Coupon Table</h4>
                  <p class="card-description">
                    Add class <code>.table-hover</code>
                  </p>
                  <div class="table-responsive mb-4">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th><i class="fa-solid fa-trash-can"></i></th>
                          <th>Coupon ID</th>
                          <th>Coupon model ID</th>
                          <th>Denomition</th>
                          <th>Coupon Name</th>
                          <th><i class="fa-solid fa-pen-to-square"></i></th>
                        </tr>
                      </thead>
                      <tbody>
                            <!-- while($r = $stmt->fetch()):  -->
                        <?php foreach ($rows as $r) : ?>
                            <tr>
                                <td>
                                    <a href="javascript: delete_one(<?= $r['coupon_id'] ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                                <td><?= $r['coupon_id'] ?></td>
                                <td><?= $r['coupon_model_id'] ?></td>
                                <td><?= $r['denomination'] ?></td>
                                <td><?= $r['coupon_name'] ?></td>
                                <td>
                                    <a href="gm_coupon_edit.php?coupon_id=<?= $r['coupon_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- endwhile  -->
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- 頁數start -->
                  <nav aria-label="Page navigation Basic example" role="group" class="flex-row my-3">
                <ul class="btn-group pagination d-flex justify-content-center">
                    <li class="page-item">
                        <a class="page-link btn btn-outline-primary" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-angle-left" href="?page"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link btn btn-outline-primary" href="?page=<?= $i ?>"><?= $i ?></a>
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
            <!-- 頁數end -->
                </div>
              </div>
                  </div>
                </div>
            </div>   
        </div>
      </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->


<?php include './../package/packageDown.php' ?>          
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(coupon_id) {
        if (confirm(`是否要刪除編號為${coupon_id}的資料?`)) {
            location.href = `gm_coupon_delete.php?coupon_id=${coupon_id}`;
        }
    }
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>