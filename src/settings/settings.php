<?php

require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";

$permissionSql = "SELECT * FROM `mb_permission` WHERE 1";
$perStmt = $pdo->query($permissionSql);
$pRows = $perStmt->fetchAll();

$sql =  "SELECT * FROM `mb_team_member` JOIN mb_permission on fk_permission_id = permission_id";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();
?>

<style>
    .text-gray {
        color: gray;
    }

    /* div */
    .search-box {
        position: absolute;
        top: 10%;
        right: 0;
        background: white;
        height: 40px;
        border-radius: 20px;
        padding: 10px;
    }

    /* input */
    .search-input {
        outline: none;
        border: none;
        background: none;
        width: 0;
        color: black;
        float: left;
        font-size: 16px;
        transition: .3s;
    }

    .search-input::placeholder {
        color: gray;
    }

    /* icon */
    .search-btn {
        color: #fff;
        float: right;
        width: 25px;
        height: 25px;
        background: #6b6b6b;
        display: flex;
        align-items: center;
        padding-left: 1px;
        text-decoration: none;
        transition: .3s;
        position: absolute;
        top: 20%;
        right: 0;
        z-index: 1;
    }

    .search-input:focus,
    .search-input:not(:placeholder-shown) {
        width: calc(100%);
        padding: 0 6px;
    }

    .search-box:hover>.search-input {
        width: calc(100%);
        padding: 0 6px;
    }

    .search-box:hover>.search-btn,
    .search-input:focus+.search-btn,
    .search-input:not(:placeholder-shown)+.search-btn {
        background: #333232;
        color: white;
        position: absolute;
        top: 10%;
        right: -10px;
        width: 30px;
        height: 30px;
        padding-left: 3px;
    }

    #accountPlus,
    .btn {
        text-decoration: none;
        border: none;
        transition: background-color 0.3s ease;
        /* Add a smooth transition effect */
    }

    #accountPlus:hover,
    .btn:hover {
        background-color: #6b6b6b !important;
    }

    #accountPlus:hover i,
    .btn:hover i {
        color: white;
    }

    a:hover .fa-file-pen,
    a:hover .fa-trash {
        color: black;
    }
</style>

<div class="d-flex justify-content-center mt-5">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Access privilege</h4>
                <p class="card-description">
                </p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="position-relative d-flex align-items-center">Name</th>
                                <th class="position-relative">Permission ID</th>
                                <th class="position-relative">Role</th>
                                <th class="position-relative">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r) : ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><?= $r['user_name'] ?></td>
                                    <td><?= $r['permission_id'] ?></td>
                                    <td>
                                        <select class="form-select form-control mt-2 permission-select" name="permissionId" data-row-id="<?= $r['id'] ?>">
                                            <?php foreach ($pRows as $pOption) : ?>
                                                <option value="<?= $pOption['permission_id'] ?>" <?= ($r['role'] == $pOption['role']) ? 'selected' : '' ?>>
                                                    <?= $pOption['permission_id'] ?> <?= $pOption['role'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><?= $r['description'] ?></td>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary me-2" disabled id="btn-save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "./../package/packageDown.php";
include "./parts/scripts.php" ?>
<script>
    document.querySelectorAll('.permission-select').forEach(selectElement => {
        selectElement.addEventListener('change', enableSaveBtn)
    })

    function enableSaveBtn() {
        const saveBtn = document.querySelector('#btn-save')
        saveBtn.disabled = false
    }

    document.querySelector('#btn-save').addEventListener('click', saveChanges)

    function saveChanges() {
        const selectElements = document.querySelectorAll('.permission-select')

        const promises = []

        selectElements.forEach(selectElement => {
            const perId = selectElement.value
            const id = selectElement.dataset.rowId

            // Check if the value has changed
            if (perId !== selectElement.dataset.originalValue) {
                const formData = new FormData();
                formData.append('permissionId', perId);
                formData.append('id', id)

                promises.push(
                    fetch('save.php', {
                        method: 'post',
                        body: formData
                    })
                    .then(response => response.json())
                )
            }
        })

        Promise.all(promises)
            .then(results => {
                const success = results.every(result => result.success)
                if (success) {
                    swal("Saved changes!", {
                            icon: "success"
                        })
                        .then(() => {
                            location.reload()
                        })
                } else {
                    // Handle errors if needed
                }
            })
            .catch(error => {
                console.error('Error:', error)
            })
    }
</script>
<?php include "./parts/html-foot.php" ?>