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
          <form action="gm_skin_add_api.php"  name="form1" enctype="multipart/form-data" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="skin_id" class="form-label">Skin ID</label>
              <input type="text" class="form-control" id="skin_id" name="skin_id" placeholder="Int">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="skin_name" class="form-label">Skin Name</label>
              <input type="text" class="form-control" id="skin_name" name="skin_name" placeholder="Example">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="skin_model_id" class="form-label">Skin Model ID</label>
              <input type="text" class="form-control" id="skin_model_id" name="skin_model_id"
                placeholder="Example.gltz">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <input type="text" class="form-control" id="role" name="role" placeholder="User_state">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <div style="cursor: pointer;" onclick="document.form1.model_url.click()" class="text-light">點選上傳檔案</div>
              <!-- <form name="uploadForm1" hidden> -->
              <input type="file" id="model" name="model" onchange="uploadFile()" class="bg-light rounded" />
              <!-- </form> -->
              <div style="width: 300px">
                <input type="text" name="newModelName" id='newModelName' hidden>
                <!-- <img id="myImg" src="" alt="" style="width: 100%"> -->
                  <model-viewer src="" alt="" id="myImg" name="model_url" width="100%" ar ar-modes="webxr scene-viewer quick-look" camera-controls tone-mapping="commerce" poster="poster.webp" shadow-intensity="0.94" exposure="1" shadow-softness="0.75" min-camera-orbit="auto auto 7.159m" min-field-of-view="30deg">
                    <div class="progress-bar hide" slot="progress-bar">
                    <div class="update-bar"></div>
                    </div>
                    <button slot="ar-button" id="ar-button">View in your space</button>
                  </model-viewer>
                <!-- <img src="" alt="" id="file_url" name="file_url" width="100%" /> -->
              </div>
              <!-- <label for="upload_file" class="form-label">Upload Model</label>
              <input type="file" class="form-control" id="upload_file" name="upload_file" placeholder=".gltf" name="file" accept=".gltf">
              <div class="form-text"></div> -->
            </div>
            <div class="mb-3">
              <label for="skin_last_update" class="form-label">Last Update</label>
              <input type="datetime-local" class="form-control" id="skin_last_update" name="skin_last_update"></input>
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
        <a type="button" class="btn btn-primary text-white me-0" href="gm_skin_list_admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>

  const {
    skin_id: skin_id_f,
    skin_name: skin_name_f,
    skin_model_id: skin_model_id_f,
    role: role_f,
    model_url: model_url_f,
    skin_last_update: skin_last_update_f,
  } = document.form1;

  function validateID(skin_id) {
        return skin_id.length = 4;
    }
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
    // skin_id_f.style.border = '1px solid #CCC';
    // skin_id_f.nextElementSibling.innerHTML = "";
    // skin_name_f.style.border = '1px solid #CCC';
    // skin_name_f.nextElementSibling.innerHTML = "";
    // skin_model_id_f.style.border = '1px solid #CCC';
    // skin_model_id_f.nextElementSibling.innerHTML = "";
    // role_f.style.border = '1px solid #CCC';
    // role_f.nextElementSibling.innerHTML = "";
    // model_url_f.style.border = '1px solid #CCC';
    // model_url_f.nextElementSibling.innerHTML = "";
    // skin_last_update_f.style.border = '1px solid #CCC';
    // skin_last_update_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    // if (skin_id_f.value.length != 4) {
    //   isPass = false;
    //   skin_id_f.style.border = '1px solid red';
    //   skin_id_f.nextElementSibling.innerHTML = "請填寫正確格式ID";
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

      fetch('gm_skin_add_api.php', {
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

  function uploadFile() {
    const fd = new FormData(document.form1);

    fetch("upload_gltf.php", {
        method: "POST",
        body: fd, // enctype="multipart/form-data"
      })
      .then((r) => r.json())
      .then((data) => {
        if (data.success) {
          // 將回傳的檔名放進Form1 送進資料庫
          const newModelName = document.getElementById('newModelName')
          newModelName.value=data.file;
          // 即時預覽圖片
          myImg.src = "./3dmodel/" + data.file;
        }
      });
  }

  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))


</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>