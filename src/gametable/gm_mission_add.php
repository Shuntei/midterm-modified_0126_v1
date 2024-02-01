<?php require __DIR__ . '/parts/db_connect.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include './../package/packageUp.php' ?>

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
          <h4 class="card-title">新增</h4>
          <p class="card-description">Basic form layout</p>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="mission_id" class="form-label">Mission ID</label>
              <input type="text" class="form-control" id="mission_id" name="mission_id" placeholder="Int">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="mission_name" class="form-label">Mission Name</label>
              <input type="text" class="form-control" id="mission_name" name="mission_name" placeholder="mission000">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="mission_description" class="form-label">Mission Description</label>
              <input type="text" class="form-control" id="mission_description" name="mission_description"
                placeholder="OXOX">
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary text-white me-0" data-bs-toggle="modal"
              data-bs-target="#exampleModal">新增</button>
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
        <a type="button" class="btn btn-primary text-white me-0" href="gm_mission_list_admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>

  const {
    mission_id: mission_id_f,
    mission_name: mission_name_f,
    mission_description: mission_description_f,
  } = document.form1;

  // function validateEmail(email) {
  //   var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  //   return re.test(email);
  // }

  // function validateMobile(mobile) {
  //   var re = /^09\d{2}-?\d{3}-?\d{3}$/;
  //   return re.test(mobile);
  // }

  const sendForm = e => {
    e.preventDefault();
    mission_id_f.style.border = '1px solid #CCC';
    mission_id_f.nextElementSibling.innerHTML = "";
    mission_name_f.style.border = '1px solid #CCC';
    mission_name_f.nextElementSibling.innerHTML = "";
    mission_description_f.style.border = '1px solid #CCC';
    mission_description_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    // if (mission_id_f.value.length > 11) {
    //   isPass = false;
    //   mission_id_f.style.border = '1px solid red';
    //   mission_id_f.nextElementSibling.innerHTML = "請填寫正確格式ID";
    // }

    // if(email_f.value === '' || !validateEmail(email_f.value)) {
    //   isPass = false;
    //   email_f.style.border = '1px solid red';
    //   email_f.nextElementSibling.innerHTML = "請填寫正確的 Email";
    // }

    // if(mobile_f.value === '' || !validateMobile(mobile_f.value)) {
    //   isPass = false;
    //   mobile_f.style.border = '1px solid red';
    //   mobile_f.nextElementSibling.innerHTML = "請填寫正確的手機號碼";
    // }

    if (isPass) {
      const fd = new FormData(document.form1);

      fetch('gm_mission_add_api.php', {
        method: 'POST',
        body: fd,
      }).then(r => r.json())
        .then(result => {
          console.log({ result });
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
<?php include __DIR__ . '/parts/html-foot.php' ?>