<?php require __DIR__ . '/parts/db_connect.php';
$pageName = 'edit';
$title = '編輯';

$coupon_id = isset($_GET['coupon_id']) ? $_GET['coupon_id'] : 0;
$sql = "SELECT * FROM gm_coupon WHERE coupon_id=$coupon_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: gm_coupon_list_admin.php");
  exit; #結束php程式
}
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
          <h5 class="card-title">編輯</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
          <div class="mb-3">
              <label class="form-label">Coupon ID</label>
              <input type="text" class="form-control" disabled value="<?= $row['coupon_id'] ?>">
            </div>
            <input type="hidden" name="coupon_id" value="<?= $row['coupon_id'] ?>">
            <div class="mb-3">
              <label for="coupon_model_id" class="form-label">Coupon Model ID</label>
              <input type="text" class="form-control" id="coupon_model_id" name="coupon_model_id" value="<?= htmlentities($row['coupon_model_id']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="denomination" class="form-label">Denomination</label>
              <input type="text" class="form-control" id="denomination" name="denomination" value="<?= $row['denomination'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="coupon_name" class="form-label">Coupon Name</label>
              <input type="text" class="form-control" id="coupon_name" name="coupon_name" value="<?= $row['coupon_name'] ?>">
              <div class="form-text"></div>
            </div>
            <div>
              <button type="submit" class="btn btn-primary text-white me-0" data-bs-toggle="modal"
              data-bs-target="#exampleModal">修改</button>
            </div>
          </div>
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
        <a type="button" class="btn btn-primary" href="gm_coupon_list_admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
const {
    coupon_id: coupon_id_f,
    coupon_model_id: coupon_model_id_f,
    denomination: denomination_f,
    coupon_name: coupon_name_f,
  } = document.form1;

  function validateID(coupon_id) {
        return coupon_id.length = 4;
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
    coupon_id_f.style.border = '1px solid #CCC';
    coupon_id_f.nextElementSibling.innerHTML = "";
    coupon_model_id_f.style.border = '1px solid #CCC';
    coupon_model_id_f.nextElementSibling.innerHTML = "";
    denomination_f.style.border = '1px solid #CCC';
    denomination_f.nextElementSibling.innerHTML = "";
    coupon_name_f.style.border = '1px solid #CCC';
    coupon_name_f.nextElementSibling.innerHTML = "";

    // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    let isPass = true;

    if (isPass) {
      const fd = new FormData(document.form1);

      fetch('gm_coupon_edit_api.php', {
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