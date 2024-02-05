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

// 搜尋條件
$searchCondition = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchCondition = " WHERE user_id LIKE :search OR coupon_id LIKE :search";
}

$t_sql = "SELECT COUNT(1) FROM gm_user_get_coupon" . $searchCondition;
$stmt = $pdo->prepare($t_sql);

if ($searchCondition) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages . '&search=' . urlencode($search));
        exit;
    }
  }
  $sql = sprintf("SELECT * FROM gm_user_get_coupon %s ORDER BY user_get_coupon_id DESC LIMIT %s, %s", $searchCondition, ($page - 1) * $perPage, $perPage);
  $stmt = $pdo->prepare($sql);
  
  if ($searchCondition) {
      $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
  }
  
  $stmt->execute();
  $rows = $stmt->fetchAll();

// $stmt = $pdo->query("SELECT * FROM ca_merchandise LIMIT 0, 20");
// $rows = $stmt->fetchAll();

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
                      <a class="nav-link ps-0" id="home-tab" href="gm_coupon_list_admin.php" role="tab" aria-selected="overview">Coupon</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" id="profile-tab" aria-current="page" href="#" role="tab" aria-selected="true">User Get Coupon</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" href="gm_user_achieved_list_admin.php" role="tab" aria-selected="false">User Achieved</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="more-tab" href="gm_mission_list_admin.php" role="tab" aria-selected="false">Mission</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link border-0" id="more-tab" href="gm_skin_list_admin.php" role="tab" aria-selected="false">Skin</a>
                    </li>
                  </ul>
                  <div>
                    <div class="btn-wrapper">
                      <a href="#" class="btn btn-otline-dark align-items-center"id="share-btn"name="share-btn"><i class="icon-share"></i> Share</a>
                      
                      <a href="../../pages/tables/getCoupon-table.php" class="btn btn-primary text-white me-0"><i class="mdi mdi-plus fw-bold"></i> Add</a>
                    </div>
                  </div>
                </div>
                <!-- change page btn end -->
                
                <!-- get coupon table start -->
                <div class="col-10 grid-margin stretch-card" id="user_get_coupon">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <h4 class="card-title">User Get Coupon Table</h4>
                          <p class="card-description">
                            Add class <code>.table-hover</code>
                          </p>
                        </div>
                        <div>
                          <form class="search-form d-flex" action="gm_user_getcoupon_list_admin.php" method="get">
                            <input type="text" name="search" id="search_byid" class="form-control" placeholder="Search User ID" aria-describedby="btn-gogo" value="<?= isset($_GET['search']) ? $_GET['search'] : "" ?>">
                            <button type="submit" class="d-flex btn btn-inverse-primary btn-icon btn-fw align-items-center"id="btn-gogo"><i class="icon-search icon-lg"></i></button>
                          </form>
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover mb-4">
                          <thead>
                            <tr>
                              <th><i class="fa-solid fa-trash-can"></i></th>
                              <!-- <th>#</th> -->
                              <th>User Get Coupon ID</th>
                              <th>User ID</th>
                              <th>Coupon ID</th>
                              <th>Last Update</th>
                              <!-- <th>地址</th> -->
                              <th><i class="fa-solid fa-pen-to-square"></i></th>
                            </tr>
                          </thead>
                          <tbody>
                                <!-- while($r = $stmt->fetch()):  -->
                            <?php foreach ($rows as $r) : ?>
                                <tr>
                                    <td>
                                        <a href="javascript: delete_one(<?= $r['user_get_coupon_id'] ?>)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                    <td><?= $r['user_get_coupon_id'] ?></td>
                                    <td><?= $r['user_id'] ?></td>
                                    <td><?= $r['coupon_id'] ?></td>
                                    <td><?= $r['last_update'] ?></td>
                                    <td>
                                        <a href="edit.php?user_get_coupon_id=<?= $r['user_get_coupon_id'] ?>">
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

                    </div>
                  </div>
                </div>
                <!-- getCoupon-table end -->
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
      
      <!-- main-panel ends -->


<?php include './../package/packageDown.php' ?> 
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(user_get_coupon_id) {
        if (confirm(`是否要刪除${user_get_coupon_id}資料?`)) {
            location.href = `gm_user_getcoupon_list_admin.php?user_get_coupon_id=${user_get_coupon_id}`;
        }
    }
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();
        let search_value = search_byid.value;
        location.href = "gm_user_getcoupon_list_admin.php?search=" + search_value;
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shareBtn = document.getElementById('share-btn');

        shareBtn.addEventListener('click', function () {
            // 獲取當前頁面URL
            const currentURL = window.location.href;

            // 建立臨時textarea元素用存放URL
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = currentURL;

            // 將textarea加到DOM
            document.body.appendChild(tempTextArea);

            // 選中textarea的内容
            tempTextArea.select();
            tempTextArea.setSelectionRange(0, 99999); /* For mobile devices */

            try {
                document.execCommand('copy');
                alert('URL已複製');
            } catch (err) {
                console.error('複製失敗', err);
            }

            // 移除textarea
            document.body.removeChild(tempTextArea);
        });
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>