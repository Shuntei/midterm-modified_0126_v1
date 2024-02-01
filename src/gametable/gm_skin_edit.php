<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$skin_id = isset($_GET['skin_id']) ? $_GET['skin_id'] : 0;
$sql = "SELECT * FROM gm_skin WHERE skin_id=$skin_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: gm_skin_list_admin.php");
  exit; #結束php程式
}
?>


<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/packageUp.php' ?>


<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="container-fluid content-wrapper">
  <div class="row">
    <div class="col-md-6 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">編輯</h4>
          <p class="card-description">Basic form layout</p>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label class="form-label">Skin ID</label>
              <input type="text" class="form-control" disabled value="<?= $row['skin_id'] ?>">
            </div>
            <input type="hidden" name="skin_id" value="<?= $row['skin_id'] ?>">
            <div class="mb-3">
              <label for="skin_name" class="form-label">Skin Name</label>
              <input type="text" class="form-control" id="skin_name" name="skin_name" value="<?= htmlentities($row['skin_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="skin_model_id" class="form-label">Skin Model ID</label>
              <input type="text" class="form-control" id="skin_model_id" name="skin_model_id" value="<?= $row['skin_model_id'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <input type="text" class="form-control" id="role" name="role" value="<?= $row['role'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="upload_file" class="form-label">Upload model</label>
              <input type="text" class="form-control" id="upload_file" name="upload_file" value="<?= $row['upload_file'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="last_update" class="form-label">Last Update</label>
              <input type="datetime-local" class="form-control" id="last_update" name="last_update"><?= $row['skin_last_update'] ?></input>
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary text-white me-0" data-bs-toggle="modal"
              data-bs-target="#exampleModal">修改</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Button trigger modal -->

  <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
  </button> -->

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a type="button" class="btn btn-primary text-white me-0" href="gm_skin_list_admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  // const {
  //   name: name_f,
  //   email: email_f,
  //   mobile: mobile_f,
  // } = document.form1;

  // function validateEmail(email) {
  //   var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  //   return re.test(email);
  // }

  // function validateMobile(mobile) {
  //   var re = /^09\d{2}-?\d{3}-?\d{3}$/;
  //   return re.test(mobile);
  // }

  // const sendForm = e => {
  //   e.preventDefault();
  //   name_f.style.border = '1px solid #CCC';
  //   name_f.nextElementSibling.innerHTML = "";
  //   email_f.style.border = '1px solid #CCC';
  //   email_f.nextElementSibling.innerHTML = "";
  //   mobile_f.style.border = '1px solid #CCC';
  //   mobile_f.nextElementSibling.innerHTML = "";

  //   // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
  //   let isPass = true;

  //   if(name_f.value.length < 2) {
  //     // alert("請填寫正確的姓名");
  //     isPass = false;
  //     name_f.style.border = '1px solid red';
  //     name_f.nextElementSibling.innerHTML = "請填寫正確的姓名";
  //   }

  //   if(email_f.value === '' || !validateEmail(email_f.value)) {
  //     isPass = false;
  //     email_f.style.border = '1px solid red';
  //     email_f.nextElementSibling.innerHTML = "請填寫正確的 Email";
  //   }

  //   if(mobile_f.value === '' || !validateMobile(mobile_f.value)) {
  //     isPass = false;
  //     mobile_f.style.border = '1px solid red';
  //     mobile_f.nextElementSibling.innerHTML = "請填寫正確的手機號碼";
  //   }

    if(isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('gm_skin_edit-api.php', {
        method: 'POST',
        body: fd,
      }).then(r => r.json())
      .then(result => {
        console.log({result});
        if(result.success) {
          myModal.show();
        }
      }).catch(
        e =>console.log(e)
      );
    }

  // const sendForm = e => {
  //   e.preventDefault();
  //   const fd = new FormData(document.form1);

  //   fetch('ca_merchandise_edit_api.php', {
  //       method: 'POST',
  //       body: fd,
  //     }).then(r => r.json())
  //     .then(result => {
  //       console.log({
  //         result
  //       });
  //       if (result.success) {
  //         myModal.show();
  //       }
  //     }).catch(
  //       e => console.log(e)
  //     );
  // }
  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>