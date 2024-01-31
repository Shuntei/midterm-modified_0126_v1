<?php 
require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'edit';
$title = '編輯';

$tourid= isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
$sql= "SELECT * FROM tr_tour WHERE tour_id=$tourid";

$row= $pdo->query($sql)->fetch(); #只抓一筆所以不要用fetchAll

if(empty($row)){
    header('Location: tr_tour_list_admin.php');
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
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_list_admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_add.php">新增</a>
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
                            <input type="text" class="form-control" disabled value="<?= $row['tour_id'] ?>">
                        </div>
                        <input type="hidden" name="tour_id" value="<?= $row['tour_id'] ?>">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">會員編號</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $row['user_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ruin_id" class="form-label">廢墟編號</label>
                            <input type="text" class="form-control" id="ruin_id" name="ruin_id" value="<?= $row['ruin_id'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="form-label">活動日期</label>
                            <input type="date" class="form-control" id="event_date" name="event_date" value="<?= $row['event_date'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="max_groupsize" class="form-label">人數</label>
                            <input type="text" class="form-control" id="max_groupsize" name="max_groupsize" value="<?= $row['max_groupsize'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="event_period" class="form-label">時長</label>
                            <input type="text" class="form-control" id="event_period" name="event_period" value="<?= $row['event_period'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="level_id" class="form-label">難度</label>
                            <select class="form-control" id="level_id" name="level_id">
                                <option><?= $row['level_id'] ?></option>
                                <option value="1">容易</option>
                                <option value="2">中等</option>
                                <option value="3">困難</option>
                            </select>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="video_url" class="form-label">影片</label>
                            <input type="text" class="form-control" id="video_url" name="video_url" value="<?= $row['video_url'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">標題</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= $row['title'] ?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">簡介</label>
                            <textarea type="text" class="form-control" id="description" name="description" cols="40" rows="4" value="<?= $row['description'] ?>"></textarea>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">內文</label>
                            <textarea type="text" class="form-control" id="content" name="content" cols="40" rows="8" value="<?= $row['content'] ?>"></textarea>
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
                <a type="button" class="btn btn-primary" href="tr_tour_list_admin.php">前往列表</a>
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

        fetch('tr_tour_edit_api.php', {
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