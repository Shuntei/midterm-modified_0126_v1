<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
$sql = "SELECT * FROM ca_category WHERE category_id=$category_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header("Location: ca_merchandise_list_admin.php");
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

<div class="container-fluid mx-auto my-3">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">編號</label>
                            <input type="text" class="form-control" disabled value="<?= $row['category_id'] ?>">
                        </div>
                        <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">類別名稱</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" value="<?= htmlentities($row['category_name']) ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="sort" class="form-label">sort</label>
                            <input type="text" class="form-control" id="sort" name="sort" value="<?= $row['category_sort'] ?>">
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
                <a type="button" class="btn btn-dark" href="ca_merchandise_list_admin.php">到列表頁</a>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    // const {
    //   name: name_f,
    //   email: email_f,
    //   mobile: mobile_f,
    // } = document.form1;

    // function validateEmail(email) {
    //   var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //   return re.test(email);
    // }

    // function validateMobile(mobile) {
    //   var re = /^09\d{2}-?\d{3}-?\d{3}$/;
    //   return re.test(mobile);
    // }

    // const sendForm = e => {
    //   e.preventDefault();
    //   name_f.style.border = '1px solid #CCC';
    //   name_f.nextElementSibling.innerHTML = "";
    //   email_f.style.border = '1px solid #CCC';
    //   email_f.nextElementSibling.innerHTML = "";
    //   mobile_f.style.border = '1px solid #CCC';
    //   mobile_f.nextElementSibling.innerHTML = "";

    //   // TODO: 資料送出之前, 要做檢查 (有沒有填寫, 格式對不對)
    //   let isPass = true;

    //   if(name_f.value.length < 2) {
    //     // alert("請填寫正確的姓名");
    //     isPass = false;
    //     name_f.style.border = '1px solid red';
    //     name_f.nextElementSibling.innerHTML = "請填寫正確的姓名";
    //   }

    //   if(email_f.value === '' || !validateEmail(email_f.value)) {
    //     isPass = false;
    //     email_f.style.border = '1px solid red';
    //     email_f.nextElementSibling.innerHTML = "請填寫正確的 Email";
    //   }

    //   if(mobile_f.value === '' || !validateMobile(mobile_f.value)) {
    //     isPass = false;
    //     mobile_f.style.border = '1px solid red';
    //     mobile_f.nextElementSibling.innerHTML = "請填寫正確的手機號碼";
    //   }

    //   if(isPass) {
    //     //"沒有外觀"的表單
    //     const fd = new FormData(document.form1);

    //     fetch('add-api.php', {
    //       method: 'POST',
    //       body: fd,
    //     }).then(r => r.json())
    //     .then(result => {
    //       console.log({result});
    //       if(result.success) {
    //         myModal.show();
    //       }
    //     }).catch(
    //       e =>console.log(e)
    //     );
    //   }
    // }
    const sendForm = e => {
        e.preventDefault();
        const fd = new FormData(document.form1);

        fetch('ca_category_edit_api.php', {
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