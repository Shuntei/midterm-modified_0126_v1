<?php
require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";

$pageName = 'list';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

$t_sql = "SELECT COUNT(1) from mb_user";

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;

if ($totalRows > 1) {
  $totalPages = ceil($totalRows / $perPage);

  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages);
    exit;
  }

  $sql = sprintf(
    "SELECT * from mb_user order by user_id DESC limit %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll();
}
?>


<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <!-- pagination start -->
      <div class="row">
        <div class="col d-flex align-items-center">
          <nav aria-label="Page navigation example">
            <ul class="pagination">

              <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=1">
                  <i class="fa-solid fa-angles-left"></i>
                </a>
              </li>

              <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= isset($_GET['page']) ? $_GET['page'] - 1 : 1 ?>">
                  <i class="fa-solid fa-angle-left"></i>
                </a>
              </li>


              <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                if ($i >= 1 and $i <= $totalPages) : ?>
                  <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                  </li>
              <?php endif;
              endfor; ?>

              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= isset($_GET['page']) ? $_GET['page'] + 1 : 1 ?>">
                  <i class="fa-solid fa-angle-right"></i>
                </a>
              </li>
              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                  <i class="fa-solid fa-angles-right"></i>
                </a>
              </li>
            </ul>
          </nav>
          <a class="fs-4 ms-3 bg-light p-1 border rounded text-dark" id="accountPlus" href="add.php">
            <i class="mdi mdi-account-plus"></i>
          </a>
        </div>
      </div>
      <!-- pagination end -->
      <h4 class="card-title">User Information</h4>
      <p class="card-description">
      </p>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th><i class="fa-solid fa-trash"></i></th>
              <th>ID </th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Birthday</th>
              <th>Created at</th>
              <th>Skin id</th>
              <th><i class="fa-solid fa-file-pen"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r) : ?>
              <tr>
                <td>
                  <a href="javascript: deleteUser(<?= $r['user_id'] ?>)">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </td>
                <td><?= $r['user_id'] ?></td>
                <td><?= $r['name'] ?></td>
                <td><?= $r['email'] ?></td>
                <td><?= $r['phone'] ?></td>
                <td><?= $r['birthday'] ?></td>
                <td><?= $r['created_at'] ?></td>
                <td><?= $r['fk_skin_id'] ?></td>
                <td><a href="edit.php?userId=<?= $r['user_id'] ?>">
                    <i class="fa-solid fa-file-pen"></i>
                  </a>
                </td>
              <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include "./../package/packageDown.php";
include "./parts/scripts.php" ?>
<script>
  function deleteUser(userId) {
    if (confirm(`Do you want to delete ${userId}'s information?`)) {
      location.href = `delete.php?userId=${userId}`
    }
  }
</script>
<?php include "./parts/html-foot.php" ?>