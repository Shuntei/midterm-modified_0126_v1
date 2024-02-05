<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";

?>
<style>
    .auth .brand-logo img {
        width: 50px;
    }
</style>
<div class="">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex pt-5 auth px-0">
            <div class="row w-100 mx-0 pt-5">
                <div class="col-lg-6 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div>
                            <a class="navbar-brand brand-logo" href="./index_.php">
                                <img src="../assets/images/ruined.png" alt="logo" />
                            </a>
                        </div>
                        <h6 class="fw-light">Sign in to continue.</h6>
                        <form class="pt-3" onsubmit="sendForm(event)" name="form1">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg rounded" id="userName" placeholder="Username" name="userName">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg rounded" id="password" placeholder="Password" name="password">
                            </div>
                            <div class="mt-3 text-center">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<?php include "./parts/scripts.php" ?>
<script>
    const {
        userName,
        password
    } = document.form1

    let isPass = true;

    const sendForm = e => {
        e.preventDefault()

        if (isPass) {
            const fd = new FormData(document.form1) 

            fetch('login-api.php', {
                    method: 'post',
                    body: fd
                }).then(r => r.json())
                .then(result => {
                    if (result.success) {
                        location.href = 'member.php'
                    } else {
                        switch (result.code) {
                            case 1:
                                swal("Empty", {
                                    icon: "error",
                                });
                                break;
                            case 2:
                                swal("The account is wrong", {
                                    icon: "error",
                                });
                                break;
                            case 3:
                                swal("The password is wrong", {
                                    icon: "error",
                                });
                                break;
                        }
                    }
                })
                .catch(ex => {
                    console.log(ex)
                })
        }
    }
</script>
<?php include "./../package/packageDown.php";
include "./parts/html-foot.php" ?>