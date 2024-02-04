<?php require __DIR__ . '/parts/db_connect.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<!-- <?php include __DIR__ . '/parts/navbar.php' ?> -->
<div class="container-fluid d-flex justify-content-center" style="background-color: #6C757D; height: 100svh">
  <div class="row d-flex justify-content-center py-5 w-75">
    <div class="col-6">
      <div class="card">
        <div class="card-body bg-dark rounded">
          <div class="d-flex justify-content-between">
            <h5 class="card-title text-light">創建好友</h5>
            <a href="./friends.php" class="ms-2"><i class="fa-solid fa-xmark fs-5 text-light"></i></a>
          </div>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-2">
              <label for="friendship_id" class="form-label text-light">friendship_id</label>
              <input type="text" class="form-control border-0" id="friendship_id" name="friendship_id" value="auto" disabled>
              <div class="form-text"></div>
            </div>
            <!-- <div class="mb-2">
              <label for="user_id" class="form-label text-light">user_id</label>
              <input type="text" class="form-control border-0" id="user_id" name="user_id" value="">
              <div class="form-text"></div>
            </div> -->
            <div class="mb-2">
              <label for="friend_id" class="form-label text-light">friend_id</label>
              <input type="text" class="form-control border-0" id="friend_id" name="friend_id" value="">
              <div class="form-text"></div>
            </div>
            <div class="mb-2">
              <label for="status" class="form-label text-light">status</label>
              <input type="text" class="form-control border-0" id="status" name="status" value="">
              <div class="form-text"></div>
            </div>
            <div class="d-flex justify-content-end mt-5 pb-0">
              <button type="submit" class="btn btn-light rounded text-dark btn-sm" onclick="goBack()" data-bs-toggle="modal" data-bs-target="#exampleModal">
                創建
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
      <div class="modal-body">
        <div class="alert alert-success fs-5 text-center" role="alert">
          創建成功✌('ω'✌ )三✌('ω')✌三( ✌'ω')✌
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const goBack = () => {
    setTimeout(() => {
      location.href = "friends.php";
    }, 2000);
  }


  const sendForm = e => {
    e.preventDefault();

    let isPass = true;

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);
      console.log(fd);
      fetch('friends-create-api.php', {
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
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>