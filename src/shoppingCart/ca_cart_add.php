<?php require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'add';
$title = '新增';
?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>


<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>

<div class="container-fluid mx-auto my-auto ">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">新增資料</h5>
                    <form name="form1" method="post" onsubmit="sendForm(event)">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">使用者ＩＤ</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id">
                                    <div class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">商品ＩＤ</label>
                                    <input type="text" class="form-control" id="item_id" name="item_id">
                                    <span id="itemNameDisplay" class=""></span>
                                    <br>
                                    <span id="unitPriceDisplay"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">數量</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity">
                                    <div class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="total_price" class="form-label">總價</label>
                                    <span id="totalPriceDisplay"></span>
                                    <div class="form-text"></div>
                                </div>

                            </div>
                            <div>

                                <!-- <button type="button" class="btn btn-outline-secondary text-dark" onclick="addNewItem()">新增項目</button>
                            </div>
                            <div id="additionalItems"></div>
 -->

                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-outline-dark ">新增</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- 新增一個空的 div，用於容納動態新增的輸入欄位 -->
    <div id="additionalItems"></div>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">新增結果</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    新增成功
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
                <a type="button" class="btn btn-dark" href="ca_cart_list_admin.php">到列表頁</a>
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

    // JavaScript 函數，用於動態新增輸入欄位
    // function addNewItem() {
    //     const newItemHtml = `
    //         <div class="mb-3">
    //             <label for="item_id" class="form-label">商品ＩＤ</label>
    //             <input type="text" class="form-control" name="item_id[]">
    //         </div>
    //         <div class="mb-3">
    //             <label for="quantity" class="form-label">數量</label>
    //             <input type="text" class="form-control" name="quantity[]">
    //         </div>
    //     `;

    //     // 將新的輸入欄位添加到 additionalItems 元素中
    //     document.getElementById('additionalItems').innerHTML += newItemHtml;
    // }
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


    const sendForm = e => {
        e.preventDefault();
        const fd = new FormData(document.form1);

        fetch('ca_cart_add_api.php', {
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