<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$cart_id = isset($_GET['cart_id']) ? $_GET['cart_id'] : 0;
$sql = "SELECT * FROM ca_cart WHERE cart_id=$cart_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header("Location: ca_cart_list_admin.php");
  exit; #結束php程式
}
?>


<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>


<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="container-fluid mx-auto my-auto">
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <form name="form1" method="post" onsubmit="sendForm(event)">
          <div class="mb-3">
              <label class="form-label">編號</label>
              <input type="text" class="form-control" disabled value="<?= $row['cart_id'] ?>">
            </div>
            <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
            <div class="mb-3">
              <label for="user_id" class="form-label">使用者id</label>
              <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $row['user_id'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="item_id" class="form-label">商品id</label>
              <input type="text" class="form-control" id="item_id" name="item_id" value="<?= htmlentities($row['item_id']) ?>">
              <span id="itemNameDisplay"></span>
              <span id="unitPriceDisplay"></span>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="quantity" class="form-label">數量</label>
              <input type="text" class="form-control" id="quantity" name="quantity" value="<?= $row['quantity'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label  class="form-label">總價</label>
              <span id="totalPriceDisplay"></span>
              <div class="form-text"></div>
            </div>

            <button type="submit" class="btn btn-outline-dark">修改</button>
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
        <a type="button" class="btn btn-primary" href="ca_cart_list_admin.php">到列表頁</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  
  document.getElementById('quantity').addEventListener('input', function() {
    const itemId = document.getElementById('item_id').value.trim();
    const quantity = this.value.trim();

    if (itemId !== '' && quantity !== '') {
        fetch(`get_total_price.php?item_id=${itemId}&quantity=${quantity}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalPriceDisplay').textContent = `總價: ${data.total_price}`;
                } else {
                    document.getElementById('totalPriceDisplay').textContent = '計算失敗';
                }
            })
            .catch(error => {
                console.error('Error fetching total price:', error);
            });
    } else {
        document.getElementById('totalPriceDisplay').textContent = '';
    }
});

  document.getElementById('item_id').addEventListener('input', function() {
    const itemId = this.value.trim();

    if (itemId !== '') {
        fetch(`get_item_name.php?item_id=${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('itemNameDisplay').textContent = `商品名稱: ${data.item_name}`;
                } else {
                    document.getElementById('itemNameDisplay').textContent = '查詢失敗';
                }
            })
            .catch(error => {
                console.error('Error fetching item name:', error);
            });
    } else {
        document.getElementById('itemNameDisplay').textContent = '';
    }
});

document.getElementById('item_id').addEventListener('input', function() {
    const itemId = this.value;

    if (itemId.trim() !== '') {
        fetch(`get_unit_price.php?item_id=${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('unitPriceDisplay').textContent = `單價: ${data.unit_price}`;
                } else {
                    document.getElementById('unitPriceDisplay').textContent = '找不到商品';
                }
            })
            .catch(error => {
                console.error('Error fetching unit price:', error);
            });
    } else {
        document.getElementById('unitPriceDisplay').textContent = '';
    }
});

  const sendForm = e => {
    e.preventDefault();
    const fd = new FormData(document.form1);

    fetch('ca_cart_edit_api.php', {
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
  const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))

  
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>