<?php 
require __DIR__ . '/admin-required.php';
require __DIR__ . '/parts/db_connect1.php';

$pageName = 'edit';
$title = '編輯';

$sid= isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$sql= "SELECT * FROM address_book WHERE sid=$sid";

$row= $pdo->query($sql)->fetch(); #只抓一筆所以不要用fetchAll

if(empty($row)){
    header('Location: list.php');
    exit;  #結束php程式
}


?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">編號</label>
                            <input type="text" class="form-control" disabled value="<?= $row['sid'] ?>">
                        </div>
                        <input type="hidden" name="sid" value="<?= $row['sid'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">姓名</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">電郵</label>
                            <input type="text" class="form-control" id="email" name="email">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">手機</label>
                            <input type="text" class="form-control" id="mobile" name="mobile">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">生日</label>
                            <input type="date" class="form-control" id="birthday" name="birthday">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">地址</label>
                            <input type="text" class="form-control" id="address" name="address">
                            <div class="form-text"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">編輯結果</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                編輯成功
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">繼續新增</button>
                <a type="button" class="btn btn-primary" href="list.php">前往列表</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/script.php' ?>
<script>
    const {
        name: name_f,
        email: email_f,
        mobile: mobile_f,
    } = document.form1;

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function validateMobile(mobile) {
        var re = /^09\d{2}-?\d{3}-?\d{3}$/;
        return re.test(mobile);
    }


    const sendForm = e => {
        e.preventDefault();
        name_f.style.border = '1px solid #CCC';
        name_f.nextElementSibling.innerHTML = "";
        email_f.style.border = '1px solid #CCC';
        email_f.nextElementSibling.innerHTML = "";
        mobile_f.style.border = '1px solid #CCC';
        mobile_f.nextElementSibling.innerHTML = "";

        // TODO:資料送出之前,檢查(有沒有填寫,格式對不對)
        let isPass = true; // 表單有沒有通過檢查

        if (name_f.value.length < 2) {
            // alert("請填寫正確姓名");
            isPass = false;
            name_f.style.border = '1px solid red';
            name_f.nextElementSibling.innerHTML = "請填寫正確姓名";
        }

        if (email_f.value && !validateEmail(email_f.value)) {
            isPass = false;
            email_f.style.border = '1px solid red';
            email_f.nextElementSibling.innerHTML = "請填寫正確Email";
        }

        if (mobile_f.value && !validateMobile(mobile_f.value)) {
            isPass = false;
            mobile_f.style.border = '1px solid red';
            mobile_f.nextElementSibling.innerHTML = "請填寫正確手機號碼";
        }

        if (isPass) {
            // 沒有外觀的表單
            const fd = new FormData(document.form1);

            fetch('edit-api.php', {
                    method: 'POST',
                    body: fd, // content-type: multipart/form-data
                }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if(result.success){
                        myModal.show();
                    }
                })
                .catch(ex => console.log(ex))
        }
    }

    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>