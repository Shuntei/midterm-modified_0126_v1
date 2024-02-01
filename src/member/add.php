<?php
require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";


$pageName = 'add';
$title = 'add';

$skinSql = "SELECT skin_id, skin_name FROM `gm_skin` WHERE 1";
$skinRow = $pdo->query($skinSql)->fetchAll();
?>

<div class="container d-flex justify-content-center">
    <div class="container col-12 col-md-6 mt-4">
        <div class="col-md-12 col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Add New User</h4>
                    <p class="card-description text-center">
                        Please fill the information correctly
                    </p>
                    <form class="forms-sample" name="form1" method="post" onsubmit="sendForm(event)" novalidate>
                        <div class="form-group row align-items-start">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9 mt-2">
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Username">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9 mt-2">
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="phone" class="col-sm-3 col-form-label">Mobile</label>
                            <div class="col-sm-9 mt-2">
                                <input type="text" class="form-control" id="phone" name="phone" required placeholder="Mobile number">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start mb-0">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9 mt-2">
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                                <p class="card-description mt-1">The password needs to include 6-16 characters, at least one number and one special character
                            </div>
                            </p>
                        </div>
                        <div class="form-group row align-items-start mt-0">
                            <label for="rePassword" class="col-sm-3 col-form-label">Re Password</label>
                            <div class="col-sm-9 mt-2">
                                <input type="password" class="form-control" id="rePassword" name="rePassword" required placeholder="Password">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="picture" class="col-sm-3 col-form-label">Profile Picture</label>
                            <div class="col-sm-9 mt-2 ">
                                <input type="file" class="form-control" id="picture" name="picture">
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="birthday" class="col-sm-3 col-form-label">Birthday</label>
                            <div class="col-sm-9 mt-2">
                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                                <p class="text-danger mt-1"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-start">
                            <label for="skinId" class="col-sm-3 col-form-label">Skin ID</label>
                            <div class="col-sm-9">
                                <!-- dropdown start -->
                                <select class="form-select form-control mt-2" id="skinId" name="skinId">
                                    <?php foreach ($skinRow as $skinOption) : ?>
                                        <option value="<?= $skinOption['skin_id'] ?>">
                                            <?= $skinOption['skin_id'] ?> <?= $skinOption['skin_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- dropdown end -->
                            </div>
                        </div>
                        <p class="text-danger mt-1" id="requiredInfo"></p>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Add</button>
                            <button type="button" class="btn btn-light">Clear</button>
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
        password,
        rePassword,
        picture,
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

    function validatePass(password) {
        // const passwordRe = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        const passwordRe = /^(?=.*\d)(?=.*[!@#$%^&*()-_+={}[\]|;:"<>,./?])[\w!@#$%^&*()-_+={}[\]|;:"<>,./?]{6,16}$/;
        return passwordRe.test(password);
    }

    function validateRePass(password, rePassword) {
        return password === rePassword;
    }

    function updatePassErrorMessage(password) {
        const passwordError = password.nextElementSibling
        let errorMessage = 'The password needs to include ';

        if (password.value.length < 6 || password.value.length > 16) {
            errorMessage += '6-16 characters, ';
        } else {
            errorMessage = '';
        }

        if (!/\d/.test(password.value)) {
            errorMessage += 'at least one number, ';
        }

        if (!/[!@#$%^&*]/.test(password.value)) {
            errorMessage += 'at least one special character, '
        }

        errorMessage = errorMessage.replace(new RegExp(', $'), '');

        if (!(errorMessage == 'The password needs to include ')) {
            passwordError.innerHTML = errorMessage
        } else {
            password.innerHTML = '';
        }

        if (errorMessage) {
            passwordError.classList.add('text-danger')
        }
    }

    function validateDate(birthday) {
        const dateRe = /^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/;
        return dateRe.test(birthday);
    }

    function validatePassword() {
        const passwordError = password.nextElementSibling;

        if (!validatePass(password)) {
            updatePassErrorMessage(password);
        } else {
            passwordError.innerHTML = '';
        }
    }

    function validateRePassword() {
        const rePasswordError = rePassword.nextElementSibling;
        if (!validateRePass(password.value, rePassword.value)) {
            rePasswordError.innerHTML = 'Passwords do not match'
        } else {
            rePasswordError.innerHTML = ''
        }
    }

    password.addEventListener('input', () => {
        validatePassword()
    })

    rePassword.addEventListener('input', () => {
        validateRePassword();
    })

    const requiredFields = document.querySelectorAll('[required]')



    requiredFields.forEach(field => {
        field.addEventListener('input', () => {
            const fieldValue = field.value.trim()
            if (fieldValue) {
                name.nextElementSibling.innerHTML = ''
                requiredInfo.innerHTML = ''
                field.style.border = '1px solid #dee2e6'
            }
        })
    })

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

        const requiredFields = document.querySelectorAll('[required]')
        const requiredInfo = document.querySelector('#requiredInfo')

        requiredFields.forEach(field => {
            const fieldName = field.getAttribute('name')
            const fieldValue = field.value.trim();

            if (!fieldValue) {
                isPass = false;
                // field.nextElementSibling.innerHTML = `Please enter ${fieldName}`
                requiredInfo.innerHTML = 'Please fill out the required information'
                field.style.border = '1px solid #f95f53'
                field.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                })
            }
        })

        if (!validateName(name.value)) {
            isPass = false;
            name.nextElementSibling.innerHTML = "Please enter a correct name"
            name.style.border = '1px solid #f95f53';
            name.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            })
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
                    title: "Proceed?",
                    text: "New user will be added",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willEdit) => {
                    if (willEdit) {
                        // Proceed with form submission
                        const fd = new FormData(document.form1)
                        fetch('add-api.php', {
                                method: 'post',
                                body: fd
                            }).then(r => r.json())
                            .then(result => {
                                console.log({
                                    result
                                })
                                if (result.success) {
                                    swal("New user has been added", {
                                        icon: "success",
                                    });
                                } else {
                                    switch (result.code) {
                                        case 1:
                                            swal("This email has been registered already", {
                                                icon: "error",
                                            });
                                            break;
                                        case 2:
                                            swal("This phone number has been registered already", {
                                                icon: "error",
                                            });
                                            break;
                                        case 3:
                                            swal("Unsuccessful", {
                                                icon: "error",
                                            });
                                            break;
                                    }
                                }
                            })
                    }
                });
        }
    }
</script>


<?php include "./../package/packageDown.php";
include "./parts/html-foot.php" ?>