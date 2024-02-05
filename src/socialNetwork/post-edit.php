<?php require __DIR__ . '/parts/db_connect.php';
$pageName = '編輯貼文';
$title = '編輯貼文';

$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
$sql = "SELECT * FROM sn_posts WHERE post_id=$post_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: posts-list-no-admin.php");
  exit; #結束php程式
}
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<!-- <?php include __DIR__ . '/parts/navbar.php' ?> -->
<div class="container-fluid d-flex justify-content-center" style="background-color: #6C757D; height: 200svh">
  <div class="row d-flex justify-content-center py-5 w-75">
    <div class="col-6">
      <div class="card">
        <div class="card-body bg-dark rounded">
          <div class="d-flex justify-content-between">
            <h5 class="card-title text-light">編輯貼文</h5>
            <a href="./posts-list-no-admin.php" class="ms-2"><i class="fa-solid fa-xmark fs-5 text-light"></i></a>
          </div>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label class="form-label text-light">post_id</label>
              <input type="text" class="form-control" disabled value="<?= $row['post_id'] ?>">
            </div>
            <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
            <div class="mb-3">
              <label for="user_id" class="form-label text-light">user_id</label>
              <input type="text" class="form-control" id="user_id" name="user_id" value="<?= htmlentities($row['user_id']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="content" class="form-label text-light">content</label>
              <textarea type="text" id="content" name="content" style="border: 1px solid #dee2e6;  
            border-radius: 4px; width: 100%;padding: 14px 22px"><?= $row['content'] ?></textarea>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="image_url" class="form-label text-light">image_url</label>
              <!-- <input type="text" class="form-control" id="image_url" name="image_url" value="<?= $row['image_url'] ?>"> -->
              <!-- 修改圖片 -->
              <div style="cursor: pointer;" onclick="document.form1.picture.click()" class="text-light">點選上傳圖片</div>
              <input type="file" id="picture" name="picture" onchange="uploadFile()" class="bg-light rounded" value="<?= $row['image_url'] ?>" />
              <div style="width: 300px">
                <input type="text" name="newPictureName" id='newPictureName' hidden>
                <img src="./upload-photos/<?= $r['image_url'] ?>" alt="" id="image_url" width="100%" />
              </div>
              <!-- 修改圖片 -->
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="video_url" class="form-label text-light">video_url</label>
              <input type="text" class="form-control" id="video_url" name="video_url" value="<?= $row['video_url'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="location" class="form-label text-light">location</label>
              <textarea type="text" id="location" name="location" style="border: 1px solid #dee2e6;border-radius: 4px; width: 100%;padding: 14px 22px"><?= $row['location'] ?></textarea>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="tagged_users" class="form-label text-light">tagged_users</label>
              <input type="text" class="form-control" id="tagged_users" name="tagged_users" value="<?= $row['tagged_users'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="posts_timestamp" class="form-label text-light">posts_timestamp</label>
              <input type="date" class="form-control bg-dark text-light" id="posts_timestamp" name="posts_timestamp" value="<?= $row['posts_timestamp'] ?>" disabled>
              <div class="form-text"></div>
            </div>
            <div class="d-flex justify-content-end mt-5 pb-0">
              <button type="submit" class="btn btn-light rounded text-dark btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">修改</button>
            </div>
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">編輯結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success fs-5" role="alert">
          修改成功✌('ω'✌ )三✌('ω')✌三( ✌'ω')✌
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
        <a type="button" class="btn btn-dark" href="posts-list-no-admin.php">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const {
    post_id: post_id_f,
    user_id: user_id_f,
    content: content_f,
    image_url: image_url_f,
    video_url: video_url_f,
    location: location_f,
    tagged_users: tagged_users_f,
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
    // name_f.style.border = '1px solid #CCC';
    // name_f.nextElementSibling.innerHTML = "";
    // email_f.style.border = '1px solid #CCC';
    // email_f.nextElementSibling.innerHTML = "";
    // mobile_f.style.border = '1px solid #CCC';
    // mobile_f.nextElementSibling.innerHTML = "";

    // // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    // if (name_f.value.length < 2) {
    //   // alert("請填寫正確的姓名");
    //   isPass = false;
    //   name_f.style.border = '1px solid red';
    //   name_f.nextElementSibling.innerHTML = "請填寫正確的姓名";
    // }

    // if (email_f.value === '' || !validateEmail(email_f.value)) {
    //   isPass = false;
    //   email_f.style.border = '1px solid red';
    //   email_f.nextElementSibling.innerHTML = "請填寫正確的 Email";
    // }

    // if (mobile_f.value === '' || !validateMobile(mobile_f.value)) {
    //   isPass = false;
    //   mobile_f.style.border = '1px solid red';
    //   mobile_f.nextElementSibling.innerHTML = "請填寫正確的手機號碼";
    // }

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('post-edit-api.php', {
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

  function uploadFile() {
    const fd = new FormData(document.form1);

    fetch("upload-photos.php", {
        method: "POST",
        body: fd, // enctype="multipart/form-data"
      })
      .then((r) => r.json())
      .then((data) => {
        if (data.success) {
          // 將回傳的檔名放進Form1 送進資料庫
          const newPictureName = document.getElementById('newPictureName')
          newPictureName.value = data.file;
          // 即時預覽圖片
          image_url.src = "./upload-photos/" + data.file;
        }
      });
  }

  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>