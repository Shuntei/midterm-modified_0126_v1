<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$sticker_inventory_id = isset($_GET['sticker_inventory_id']) ? $_GET['sticker_inventory_id'] : 0;
$sql = "SELECT * FROM live_sticker_inventory WHERE sticker_inventory_id=$sticker_inventory_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: live_sticker_inventory-list-admin.php");
  exit; #結束php程式
}
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include('./../package/packageUp.php') ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<style>
  .photo {
    width: 200px;
    height: 200px;
    overflow: hidden;
    /* border: 1px solid black; */
  }

  img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 0;
  }
</style>

<div class="container-fluid">
  <div class="row d-flex justify-content-center mt-4">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯貼圖內容</h5>
          <form name="form1" method="post" enctype="multipart/form-data" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label class="form-label">編號</label>
              <input type="text" class="form-control" disabled value="<?= $row['sticker_inventory_id'] ?>">
            </div>
            <input type="hidden" name="sticker_inventory_id" value="<?= $row['sticker_inventory_id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">名稱</label>
              <input type="text" class="form-control" id="sticker_title" name="sticker_title" value="<?= htmlentities($row['sticker_title']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="sticker_cost" class="form-label">金額</label>
              <input type="number" class="form-control" id="sticker_cost" name="sticker_cost" value="<?= $row['sticker_cost'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="text" class="form-label">更新圖片</label>
              <input type="text" name="newPictureName" id="newPictureName" hidden><br>
              <input type="file" name="sticker" id="sticker" accept="image/*">
              <div class="form-text"></div>
              <div class="photo">
                <img id="displayPhoto" src="./imgs/<?= $row['sticker_pic'] ?>" class="photo" alt="picture">
              </div>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">成功更新檔案</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          請問是否繼續編輯？
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
        <a type="button" class="btn btn-primary" href="live_sticker_inventory-list-admin.php">回去列表</a>
      </div>
    </div>
  </div>
</div>
<?php include('./../package/packageDown.php') ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const {
    sticker_title: sticker_title_f,
    sticker_cost: sticker_cost_f,
    sticker: sticker_pic_f,
  } = document.form1;

  let newPictureName = document.getElementById('newPictureName')

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
      sticker_title_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (sticker_cost_f.value === "") {
      // alert("請填寫正確的姓名");
      isPass = false;
      sticker_cost_f.style.border = '1px solid red';
      sticker_cost_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (newPictureName.value === '') {
      isPass = false;
      sticker_pic_f.style.border = '1px solid red';
      sticker_pic_f.nextElementSibling.innerHTML = "此格不許空白";
    }

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('live_sticker_inventory-edit-api.php', {
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

  // 抓圖片開始

  document.getElementById('sticker').addEventListener("change", uploadFile)

  function uploadFile() {
    const fd = new FormData(document.form1);

    fetch("live-upload-avatar.php", {

        method: "POST",
        body: fd, // enctype="multipart/form-data"
      })

      .then((r) => r.json())
      .then((data) => {
        if (data.success) {
          let displayPhoto = document.getElementById('displayPhoto')
          let newPictureName = document.getElementById('newPictureName');
          newPictureName.value = data.file;
          displayPhoto.src = "./imgs/" + data.file;
        }
      })
      .catch(e => console.log(e));
  }

  // 抓圖片結束

  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>