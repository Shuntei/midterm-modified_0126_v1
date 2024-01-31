<?php
require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";

$pageName = 'edit';
$title = 'edit';

$userId = isset($_GET['userId']) ? $_GET['userId'] : 0;

$sql = "SELECT * from mb_user where user_id=$userId";

$row = $pdo->query($sql)->fetch();

if (empty($row)) {
    header("Location: member.php");
}

$skinSql = "SELECT skin_id, skin_name FROM `gm_skin` WHERE 1";
$skinRow = $pdo->query($skinSql)->fetchAll();

?>

<style>
    .swal-footer {
        text-align: center;
    }

    .swal-button {
        border: none;
    }

    .swal-button--cancel {
        background-color: #fbfbfb;
        color: #1E283D;
        cursor: pointer;
        border-radius: 5px;
        border: none;
        text-shadow: none;
    }

    .swal-button--cancel:focus {
        border: none;
        outline: none;
        text-shadow: none;

    }

    .swal-button--confirm {
        background: #1F3BB3;
        color: white;
        outline: none;
        border: none;

    }

    .swal-button:hover {
        background: #0a58ca;
        color: white;
    }

    button {
        padding-block: 0;
    }
</style>

<div class="container d-flex justify-content-center">
    <div class="container col-12 col-md-6 mt-4">
        <div class="col-md-12 col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title text-center">Edit User Information</h4>

                    <p class="card-description text-center">
                        User Id: <?= $row['user_id'] ?>
                    </p>
                    <form class="forms-sample" name="form1" method="post" onsubmit="sendForm(event)">
                        <input type="text" class="form-control" id="userId" hidden name="userId" value="<?= $row['user_id'] ?>">
                        <div class="form-group row align-items-start">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9 mt-2">
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($row['name']) ?>">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9 mt-2">
                                <input type="email" class="form-control" id="email" name="email" value="<?= $row['email'] ?>">
                                <div class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="phone" class="col-sm-3 col-form-label">Mobile</label>
                            <div class="col-sm-9 mt-2">
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $row['phone'] ?>">
                                <div class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="birthday" class="col-sm-3 col-form-label">Birthday</label>
                            <div class="col-sm-9 mt-2">
                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $row['birthday'] ?>">
                                <div class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="skinId" class="col-sm-3 col-form-label">Skin ID</label>
                            <div class="col-sm-9">
                                <!-- dropdown start -->
                                <select class="form-select form-control mt-2" id="skinId" name="skinId">
                                    <?php foreach ($skinRow as $skinOption) : ?>
                                        <option value="<?= $skinOption['skin_id'] ?>" <?= ($row['fk_skin_id'] == $skinOption['skin_id']) ? 'selected' : '' ?>>
                                            <?= $skinOption['skin_id'] ?> <?= $skinOption['skin_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- dropdown end -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Edit</button>
                            <button type="button" class="btn btn-light">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./parts/scripts.php" ?>
<script>
    const {
        name,
        email,
        phone,
        birthday
    } = document.form1;

    function validateName(name) {
        return name.length >= 2;
    }

    function validateEmail(email) {
        const emailRe = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailRe.test(email);
    }

    function validatePhone(phone) {
        const phoneRe = /^09\d{2}-?\d{3}-?\d{3}$/;
        return phoneRe.test(phone);
    }

    function validateDate(birthday) {
        const dateRe = /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/;
        return dateRe.test(birthday);
    }

    const sendForm = e => {
        e.preventDefault();
        name.nextElementSibling.innerHTML = "";
        name.style.border = "1px solid #dee2e6";
        email.nextElementSibling.innerHTML = "";
        email.style.border = "1px solid #dee2e6";
        phone.nextElementSibling.innerHTML = "";
        phone.style.border = "1px solid #dee2e6";
        birthday.nextElementSibling.innerHTML = "";
        birthday.style.border = "1px solid #dee2e6";

        let isPass = true;

        if (!validateName(name.value)) {
            isPass = false;
            name.nextElementSibling.innerHTML = "Please enter a correct name"
            name.style.border = '1px solid #f95f53';
        }

        if (email.value && !validateEmail(email.value)) {
            isPass = false;
            email.nextElementSibling.innerHTML = "Please enter a correct email"
            email.style.border = '1px solid #f95f53';
        }

        if (phone.value && !validatePhone(phone.value)) {
            isPass = false;
            phone.nextElementSibling.innerHTML = "Please enter a correct phone number"
            phone.style.border = '1px solid #f95f53';
        }

        if (birthday.value && !validateDate(birthday.value)) {
            isPass = false;
            birthday.nextElementSibling.innerHTML = "Please enter a correct date"
            birthday.style.border = '1px solid #f95f53';
        }

        if (isPass) {
            swal({
                    title: "Are you sure?",
                    text: "Once edited, you will not be able to revert the changes!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willEdit) => {
                    if (willEdit) {
                        // Proceed with form submission
                        const fd = new FormData(document.form1)
                        fetch('edit-api.php', {
                                method: 'post',
                                body: fd
                            }).then(r => r.json())
                            .then(result => {
                                console.log({
                                    result
                                })
                                if (result.success) {
                                    swal("It has been edited successfully", {
                                        icon: "success",
                                    });
                                }
                            })
                    }
                });
        }
    }
</script>


<?php include "./../package/packageDown.php";
include "./parts/html-foot.php" ?>