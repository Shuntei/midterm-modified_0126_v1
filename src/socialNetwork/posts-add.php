<?php require __DIR__ . '/parts/db_connect.php';
$pageName = '發文';
$title = '發文';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<!-- <?php include __DIR__ . '/parts/navbar.php' ?> -->
<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="container-fluid d-flex justify-content-center" style="background-color: #6C757D; height: 100000px">
  <div class="row d-flex justify-content-center py-5 w-75">
    <div class="col-6">
      <div class="card">
        <div class="card-body bg-dark rounded">
          <div class="d-flex justify-content-between">
            <h5 class="card-title text-light">發文</h5>
            <a href="./posts-list-no-admin.php" class="ms-2"><i class="fa-solid fa-xmark fs-5 text-light"></i></a>
          </div>
          <form name="form1" method="post" onsubmit="sendForm(event)" enctype="multipart/form-data">
            <div class="mb-2">
              <label for="content" class="form-label text-light">content</label>
              <textarea type="text" id="content" name="content" style="border: 1px solid #dee2e6;
                border-radius: 4px; width: 100%;padding: 14px 22px"></textarea>
              <div class="form-text text-danger" id="showContent"></div>
            </div>
            <div class="mb-2">
              <!-- 多張
              <input type="file" id="image_url" name="image_url" multiple accept="image/*" onchange="uploadFile()" />
              <div style="cursor: pointer;background-color: #f0f0f0;border:1px solid #000;width:110px;border-radius:3px;padding:1px 6px" class="text-center mt-1" onclick="image_url.click()">選擇多個檔案</div>
              <div class="card-container w-100"></div> -->
              <!-- 單張 -->
              <div style="cursor: pointer;" onclick="document.form1.picture.click()" class="text-light">Upload_an_image</div>
              <input type="file" id="picture" name="picture" onchange="uploadFile()" class="bg-light rounded" />
              <div style="width: 300px">
                <input type="text" name="newPictureName" id='newPictureName' hidden>
                <img src="" alt="" id="image_url" width="100%" />
              </div>
            </div>
            <!-- <div class="mb-2">
              <label for="video_url" class="form-label text-light">video url</label>
              <input type="text" class="form-control border-0" id="video_url" name="video_url" value="">
              <div class="form-text"></div>
            </div> -->
            <div class="mb-2">
              <label for="location" class="form-label text-light">location</label>
              <textarea type="text" id="location" name="location" style="border: 1px solid #dee2e6;
                border-radius: 4px; width: 100%;padding: 14px 22px;padding-left:-20px;"></textarea>
              <div class="form-text"></div>
            </div>
            <div class="mb-2">
              <label for="tagged_users" class="form-label text-light">tagged_users</label>
              <input type="text" class="form-control border-0" id="tagged_users" name="tagged_users" value="">
              <div class="form-text"></div>
            </div>
            <!-- <div class="mb-3">
                <label for="posts_timestamp" class="form-label">posts_timestamp</label>
                <input type="date" class="form-control" id="posts_timestamp" name="posts_timestamp" value="posts_timestamp">
                <div class="form-text"></div>
              </div> -->
            <div class="d-flex justify-content-end mt-5 pb-0">
              <button type="submit" class="btn btn-light rounded text-dark btn-sm">
                <!-- data-bs-toggle="modal" data-bs-target="#exampleModal" -->
                發佈
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Button trigger modal -->

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">新增結果</h1>
      </div> -->
      <div class="modal-body">
        <div class="alert alert-success fs-5 text-center" role="alert">
          發佈成功✌('ω'✌ )三✌('ω')✌三( ✌'ω')✌
        </div>
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

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    if (content_f.value.length < 1) {
      // alert("請填寫正確的姓名");
      isPass = false;
      content_f.style.border = '5px solid red';
      content_f.nextElementSibling.innerHTML = "Please write some contents.";
    } else {
      alert("發佈成功✌('ω'✌ )三✌('ω')✌三( ✌'ω')✌");
      setTimeout(() => {
        location.href = "posts-list-no-admin.php";
      }, 100);
    }

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
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);
      console.log(fd);
      fetch('posts-add-api.php', {
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

  // const goBack = () => {
  //   if (isPass) {
  //     setTimeout(() => {
  //       location.href = "posts-list-no-admin.php";
  //     }, 2000);
  //   }
  // }


  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>