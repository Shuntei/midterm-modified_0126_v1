<?php require __DIR__ . '/parts/db_connect.php';
$pageName = 'edit';
$title = '編輯';

$friendship_id = isset($_GET['friendship_id']) ? $_GET['friendship_id'] : 0;
$sql = "SELECT * FROM sn_friends WHERE friendship_id=$friendship_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: friends.php");
  exit; #結束php程式
}

$sql_status = "SELECT status FROM sn_friends";
$stmt = $pdo->query($sql_status);
$rows = $stmt->fetchAll();
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
            <h5 class="card-title text-light">修改關係</h5>
            <a href="./friends.php" class="ms-2"><i class="fa-solid fa-xmark fs-5 text-light"></i></a>
          </div>
          <form name="form1" method="post" onsubmit="sendForm(event)">
            <div class="mb-2">
              <label for="friendship_id" class="form-label text-light">friendship_id: <?= $row['friendship_id'] ?></label>
              <input type="hidden" class="form-control border-0" id="friendship_id" name="friendship_id" value="<?= $row['friendship_id'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label text-light">status</label>
              <select type="text" class="form-control" id="status" name="status" value="<?= $row['status'] ?>">
                <?php foreach ($rows as $option) : ?>
                  <option value="<?= $option['status'] ?>" <?= ($row['status'] === $option['status']) ? 'selected' : '' ?> <?= $option['status'] ?>>
                    <?= $option['status'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="d-flex justify-content-end mt-5 pb-0">
              <button type="submit" class="btn btn-light rounded text-dark btn-sm">修改</button>
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success fs-5" role="alert">
          修改成功✌('ω'✌ )三✌('ω')✌三( ✌'ω')✌
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a type="button" class="btn btn-dark" href="friends.php">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const sendForm = e => {
    e.preventDefault();

    let isPass = true;

    if (isPass) {
      //"沒有外觀"的表單
      const fd = new FormData(document.form1);

      fetch('friends-edit-api.php', {
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