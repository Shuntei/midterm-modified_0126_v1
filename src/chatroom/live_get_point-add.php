<?php require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts-get-point/html-head.php' ?>
<?php include('./../package/packageUp.php') ?>
<?php

if (empty($pageName)) {
  $pageName = '';
}
?>
<div class="container-fluid">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./live_get_point-list-admin.php">列表</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./live_get_point-add.php">新增</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="container-fluid ">
  <div class="row d-flex justify-content-center mt-4">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">點數紀錄</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="id" class="form-label">用戶ID</label>
              <input type="number" class="form-control" id="user_id" name="user_id">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="point" class="form-label">獲得點數</label>
              <input type="number" class="form-control" id="received_point" name="received_point">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="source" class="form-label">點數來源</label>
              <select class="form-control" id="point_source" name="point_source">
                <option value="選擇取得方式" selected disabled>選擇取得方式</option>
                <option value="管理員撒錢">管理員撒錢</option>
                <option value="幸運中獎">幸運中獎</option>
                <option value="遊戲獲勝">遊戲獲勝</option>
                <option value="點數回饋">點數回饋</option>
                <option value="互動獎勵">互動獎勵</option>
                <option value="刷頻懲罰">刷頻懲罰</option>
                <option value="惡意攻擊">惡意攻擊</option>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="datetime" class="form-label">獲得時間</label>
              <input type="datetime-local" class="form-control" id="date_get_point" name="date_get_point">
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">已成功建檔</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          請問要繼續新增嗎？
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">再來一筆</button>
        <a type="button" class="btn btn-primary" href="./live_get_point-list-admin.php">回去列表</a>
      </div>
    </div>
  </div>
</div>
<?php include('./../package/packageDown.php') ?>
<?php include __DIR__ . '/parts-get-point/scripts.php' ?>
<script>
  const {
    user_id: user_id_f,
    received_point: received_point_f,
    point_source: point_source_f,
  } = document.form1;

  const sendForm = e => {
    e.preventDefault();
    user_id_f.style.border = '1px solid #CCC';
    user_id_f.nextElementSibling.innerHTML = "";

    received_point_f.style.border = '1px solid #CCC';
    received_point_f.nextElementSibling.innerHTML = "";

    point_source_f.style.border = '1px solid #CCC';
    point_source_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    if (user_id_f.value === "" || isNaN(user_id_f.value)) {
      isPass = false;
      user_id_f.style.border = '1px solid red';
      user_id_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (received_point_f.value === "" || isNaN(received_point_f.value)) {
      isPass = false;
      received_point_f.style.border = '1px solid red';
      received_point_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (point_source_f.value === '') {
      isPass = false;
      point_source_f.style.border = '1px solid red';
      point_source_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('live_get_point-add-api.php', {
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
  }

  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts-get-point/html-foot.php' ?>