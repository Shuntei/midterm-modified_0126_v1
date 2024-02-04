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
<div class="container-fluid content-wrapper">
  <div class="row">
    <div class="col-md-6 grid-margin">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-3">
              <label class="form-label">Coupon ID</label>
              <input type="number" class="form-control" disabled value="<?= $row['coupon_id'] ?>">
            </div>
            <input type="hidden" name="coupon_id" value="<?= $row['coupon_id'] ?>">
            <div class="mb-3">
              <label for="coupon_model_id" class="form-label">Coupon Model ID</label>
              <input type="text" class="form-control" id="coupon_model_id" name="coupon_model_id" value="<?= htmlentities($row['coupon_model_id']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="denomination" class="form-label">Denomination</label>
              <input type="number" class="form-control" id="denomination" name="denomination" value="<?= $row['denomination'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="coupon_name" class="form-label">Coupon Name</label>
              <input type="text" class="form-control" id="coupon_name" name="coupon_name" value="<?= $row['coupon_name'] ?>">
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary text-white me-0">確定修改</button>
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
        <div class="alert alert-success" role="alert">
          編輯成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
        <a type="button" class="btn btn-primary text-white me-0" href="gm_coupon_list_admin.php">回列表</a>
      </div>
    </div>
  </div>
</div>
<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const {
    coupon_id: coupon_id_f,
    coupon_model: coupon_model_id,
    denomination: denomination_f,
    coupon_name: coupon_name_f,
  } = document.form1;
  

  const sendForm = e => {
    e.preventDefault();
    // user_id_f.style.border = '1px solid #CCC';
    // user_id_f.nextElementSibling.innerHTML = "";

    // received_point_f.style.border = '1px solid #CCC';
    // received_point_f.nextElementSibling.innerHTML = "";

    // point_source_f.style.border = '1px solid #CCC';
    // point_source_f.nextElementSibling.innerHTML = "";

    // // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    // let isPass = true;

    // if (user_id_f.value === "" || isNaN(user_id_f.value)) {
    //   isPass = false;
    //   user_id_f.style.border = '1px solid red';
    //   user_id_f.nextElementSibling.innerHTML = "請正確填入資料";
    // }

    // if (received_point_f.value === "" || isNaN(received_point_f.value)) {
    //   // alert("請填寫正確的姓名");
    //   isPass = false;
    //   received_point_f.style.border = '1px solid red';
    //   received_point_f.nextElementSibling.innerHTML = "請正確填數資料";
    // }

    // if (point_source_f.value === '') {
    //   isPass = false;
    //   point_source_f.style.border = '1px solid red';
    //   point_source_f.nextElementSibling.innerHTML = "請正確填數資料";
    // }

    // if (isPass) {
      //"沒有外觀"的表單
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
  // }

  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>