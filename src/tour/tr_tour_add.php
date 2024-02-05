<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'add';
$title = '新增';

// Fetch ruin_name values from tr_location table
$ruinNameList = [];
$sql = "SELECT ruin_id, ruin_name FROM tr_location";
$stmt = $pdo->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $ruinNameList[$row['ruin_id']] = $row['ruin_name'];
}
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include './../package/packageUp.php' ?>

<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<!-- navbar start -->
<div class="container-fluid">
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_list_admin.php">列表</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_add.php">新增</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<!-- navbar end -->

<div class="container-fluid">
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">新增資料</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="user_id" class="form-label">會員編號</label>
              <input type="text" class="form-control" id="user_id" name="user_id">
              <div class="form-text"></div>
            </div>
            <!-- <div class="mb-3">
              <label for="ruin_id" class="form-label">廢墟編號</label>
              <input type="text" class="form-control" id="ruin_id" name="ruin_id">
              <div class="form-text"></div>
            </div> -->
            <div class="mb-3">
              <label for="ruin_id" class="form-label">地點</label>
              <select class="form-select" id="ruin_id" name="ruin_id">
                <?php foreach ($ruinNameList as $id => $name) : ?>
                  <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="event_date" class="form-label">活動日期</label>
              <input type="date" class="form-control" id="event_date" name="event_date">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="max_groupsize" class="form-label">總人數</label>
              <input type="text" class="form-control" id="max_groupsize" name="max_groupsize">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="event_period" class="form-label">活動時長(hr)</label>
              <input type="text" class="form-control" id="event_period" name="event_period">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="level_id" class="form-label">難易度</label>
              <select class="form-select" id="level_id" name="level_id">
                <option value="1">容易</option>
                <option value="2">中等</option>
                <option value="3">困難</option>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="video_url" class="form-label">影片</label>
              <input type="text" class="form-control" id="video_url" name="video_url"></input>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="title" class="form-label">標題</label>
              <input type="text" class="form-control" id="title" name="title"></input>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">簡介</label>
              <br>
              <textarea type="text" id="description" rows=5 name="description" style="border: 1px solid #dee2e6;
              border-radius: 4px; width: 100%;padding: 14px 22px; "></textarea>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="content" class="form-label">內文</label>
              <br>
              <textarea type="text" id="content" rows=5 name="content" style="border: 1px solid #dee2e6;
              border-radius: 4px; width: 100%;padding: 14px 22px; "></textarea>
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary">新增</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">新增結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          新增成功
        </div>
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal" href="tr_tour_add.php">繼續新增</a>
        <a type="button" class="btn btn-primary" href="tr_tour_list_admin.php">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const sendForm = e => {
    e.preventDefault();
    const fd = new FormData(document.form1);

    fetch('tr_tour_add_api.php', {
        method: 'POST',
        body: fd,
      }).then(r => r.json())
      .then(result => {
        console.log({
          result
        });
        if (result.success) {
          myModal.show();
        }
      }).catch(
        e => console.log(e)
      );
  }
  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>