<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include ('./../package/packageUp.php') ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
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
          <h5 class="card-title">上架貼圖</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label for="name" class="form-label">貼圖名稱</label>
              <input type="text" class="form-control" id="sticker_title" name="sticker_title">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="number" class="form-label">貼圖費用</label>
              <input type="number" class="form-control" id="sticker_cost" name="sticker_cost">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="text" class="form-label">上傳圖片</label>
              <input type="text" class="form-control" id="sticker_pic" name="sticker_pic">
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
        <a type="button" class="btn btn-primary" href="live_sticker_inventory-list-admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include ('./../package/packageDown.php') ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const {
    sticker_title: sticker_title_f,
    sticker_cost: sticker_cost_f,
    sticker_pic: sticker_pic_f,
  } = document.form1;

  const sendForm = e => {
    e.preventDefault();
    sticker_title_f.style.border = '1px solid #CCC';
    sticker_title_f.nextElementSibling.innerHTML = "";

    sticker_cost_f.style.border = '1px solid #CCC';
    sticker_cost_f.nextElementSibling.innerHTML = "";

    sticker_pic_f.style.border = '1px solid #CCC';
    sticker_pic_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    if (sticker_title_f.value === "") {
      isPass = false;
      sticker_title_f.style.border = '1px solid red';
      sticker_title_f.nextElementSibling.innerHTML = "請正確填入資料";
    }

    if (sticker_cost_f.value === "") {
      // alert("請填寫正確的姓名");
      isPass = false;
      sticker_cost_f.style.border = '1px solid red';
      sticker_cost_f.nextElementSibling.innerHTML = "請正確填入資料";
    }

    if (sticker_pic_f.value === '') {
      isPass = false;
      sticker_pic_f.style.border = '1px solid red';
      sticker_pic_f.nextElementSibling.innerHTML = "請正確填入資料";
    }

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('live_sticker_inventory-add-api.php', {
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

<?php include __DIR__ . '/parts/html-foot.php' ?>