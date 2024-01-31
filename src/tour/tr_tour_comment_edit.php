<?php 
require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$tour_comment_id= isset($_GET['tour_comment_id']) ? intval($_GET['tour_comment_id']) : 0;
$sql= "SELECT * FROM tr_tour_comment WHERE tour_comment_id=$tour_comment_id";

$row= $pdo->query($sql)->fetch(); #只抓一筆所以不要用fetchAll

if(empty($row)){
    header('Location: tr_tour_comment_list_admin.php');
    exit;  #結束php程式
}
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include './../package/packageUp.php' ?>

<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>

<!-- navbar start -->
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_comment_list_admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_comment_add.php">新增</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<!-- navbar end -->

<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="mb-3">
                            <label class="form-label">編號</label>
                            <input type="text" class="form-control" disabled value="<?= $row['tour_comment_id'] ?>">
                        </div>
                        <input type="hidden" name="tour_comment_id" value="<?= $row['tour_comment_id'] ?>">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">會員編號</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $row['user_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="tour_id" class="form-label">揪團編號</label>
                            <input type="text" class="form-control" id="tour_id" name="tour_id" value="<?= $row['tour_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="comment_content" class="form-label">留言內容</label>
                            <textarea type="text" class="form-control" id="comment_content" name="comment_content" cols="40" rows="4" value="<?= $row['comment_content'] ?>"></textarea>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="comment_parent_id" class="form-label">回覆編號</label>
                            <input type="text" class="form-control" id="comment_parent_id" name="comment_parent_id" value="<?= $row['comment_parent_id'] ?>">
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                編輯成功
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary" href="tr_tour_comment_list_admin.php">前往列表</a>
            </div>
        </div>
    </div>
</div>

<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    // const {
    //     name: name_f,
    //     email: email_f,
    //     event_date: event_date_f,
    // } = document.form1;

    // function validateEmail(email) {
    //     var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //     return re.test(email);
    // }

    // function validateevent_date(event_date) {
    //     var re = /^09\d{2}-?\d{3}-?\d{3}$/;
    //     return re.test(event_date);
    // }


    // const sendForm = e => {
    //     e.preventDefault();
    //     name_f.style.border = '1px solid #CCC';
    //     name_f.nextElementSibling.innerHTML = "";
    //     email_f.style.border = '1px solid #CCC';
    //     email_f.nextElementSibling.innerHTML = "";
    //     event_date_f.style.border = '1px solid #CCC';
    //     event_date_f.nextElementSibling.innerHTML = "";

    // TODO:資料送出之前,檢查(有沒有填寫,格式對不對)
    // let isPass = true; // 表單有沒有通過檢查

    // if (name_f.value.length < 2) {
    //     // alert("請填寫正確姓名");
    //     isPass = false;
    //     name_f.style.border = '1px solid red';
    //     name_f.nextElementSibling.innerHTML = "請填寫正確姓名";
    // }

    // if (email_f.value && !validateEmail(email_f.value)) {
    //     isPass = false;
    //     email_f.style.border = '1px solid red';
    //     email_f.nextElementSibling.innerHTML = "請填寫正確Email";
    // }

    // if (event_date_f.value && !validateevent_date(event_date_f.value)) {
    //     isPass = false;
    //     event_date_f.style.border = '1px solid red';
    //     event_date_f.nextElementSibling.innerHTML = "請填寫正確日期";
    // }
        
    const sendForm = e => {
        e.preventDefault();
        // 沒有外觀的表單
        const fd = new FormData(document.form1);

        fetch('tr_tour_comment_edit_api.php', {
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
    const myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>