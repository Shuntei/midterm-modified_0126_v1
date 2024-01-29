<?php require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts-get-point/html-head.php' ?>
<?php include __DIR__ . '/parts-get-point/packageUp.php' ?>
<?php include __DIR__ . '/parts-get-point/navbar.php' ?>
<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">點數紀錄</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="name" class="form-label">用戶ID</label>
              <input type="user_id" class="form-control" id="user_id" name="user_id">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="received_point" class="form-label">獲得點數</label>
              <input type="number" class="form-control" id="get_point_idreceived_point" name="get_point_idreceived_point">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="point_source" class="form-label">點數來源</label>
              <input type="text" class="form-control" id="point_source" name="point_source">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="date_get_point" class="form-label">獲得時間</label>
              <input type="text" class="form-control" id="date_get_point" name="date_get_point">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
        <a type="button" class="btn btn-primary" href="live_get_point-list-admin.php">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts-get-point/packageDown.php' ?>
<?php include __DIR__ . '/parts-get-point/scripts.php' ?>
<script>
  const {
    name: name_f,
    email: email_f,
    mobile: mobile_f,
  } = document.form1;

  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateMobile(mobile) {
    var re = /^09\d{2}-?\d{3}-?\d{3}$/;
    return re.test(mobile);
  }

  const sendForm = e => {
    e.preventDefault();
    name_f.style.border = '1px solid #CCC';
    name_f.nextElementSibling.innerHTML = "";
    email_f.style.border = '1px solid #CCC';
    email_f.nextElementSibling.innerHTML = "";
    mobile_f.style.border = '1px solid #CCC';
    mobile_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    if (name_f.value.length < 2) {
      // alert("請填寫正確的姓名");
      isPass = false;
      name_f.style.border = '1px solid red';
      name_f.nextElementSibling.innerHTML = "請填寫正確的姓名";
    }

    if (email_f.value === '' || !validateEmail(email_f.value)) {
      isPass = false;
      email_f.style.border = '1px solid red';
      email_f.nextElementSibling.innerHTML = "請填寫正確的 Email";
    }

    if (mobile_f.value === '' || !validateMobile(mobile_f.value)) {
      isPass = false;
      mobile_f.style.border = '1px solid red';
      mobile_f.nextElementSibling.innerHTML = "請填寫正確的手機號碼";
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