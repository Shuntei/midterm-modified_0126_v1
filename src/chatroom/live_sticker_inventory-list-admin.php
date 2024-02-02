<?php

require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM live_sticker_inventory";

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if (isset($_GET['sort'])) {
    $selectedSort = $_GET["sort"];
    switch ($selectedSort) {
        case 'id_descend':
            $sortColumn = "sticker_inventory_id";
            $sortDisplay = "DESC";
            break;
        case 'cost_descend':
            $sortColumn = "sticker_cost";
            $sortDisplay = 'DESC';
            break;
        case 'cost_ascend':
            $sortColumn = "sticker_cost";
            $sortDisplay = 'ASC';
            break;
        case 'original':
            $sortColumn = "sticker_inventory_id";
            $sortDisplay = 'ASC';
            break;
        default:
            $sortColumn = "sticker_inventory_id";
            $sortDisplay = 'ASC';
            break;
    }
} else {
    $sortColumn = "sticker_inventory_id";
    $sortDisplay = 'ASC';
}

$search = isset($_GET['searchbar']) ? $_GET['searchbar'] : "";
$searching_sql = "WHERE sticker_title LIKE'%" . $search . "%'";

if (empty($search)) {
    $searching_sql = "";
}

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf("SELECT * FROM live_sticker_inventory %s ORDER BY %s %s LIMIT %s, %s", $searching_sql, $sortColumn, $sortDisplay, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include('./../package/packageUp.php') ?>
<!-- Navbar -->
<?php
if (empty($pageName)) {
    $pageName = '';
}
?>
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./live_get_point-list-admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./live_get_point-add.php">新增</a>
                    </li>
                    <li class="nav-item">
                        <p class="clock">現在 00:00:00</p>
                    </li>
                    <li class="nav-item">
                        <p class="timePassed">閒置 00:00</p>
                    </li>
                </ul>
                <!-- <ul class="navbar-nav mb-2 mb-lg-0">
        <?php if (isset($_SESSION['admin'])) : ?>
            <li class="nav-item">
            <!-- <a class="nav-link">暱稱</a> -->
                <a class="nav-link"><?= $_SESSION['admin']['nickname'] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">登出</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'login' ? 'active' : '' ?>" href="./login.php">登入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'register' ? 'active' : '' ?>" href="./register.php">註冊</a>
                </li>
            <?php endif ?>
            </ul> -->
            </div>
        </div>
    </nav>

</div>
<!-- Navbar End -->

<style>
    .photo {
        width: 100px;
        height: 100px;
        overflow: hidden;
        /* border: 1px solid black; */
    }

    .table td img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 0;
    }

    .distance {
        margin-left: 10px;
    }

    ul.li distance {
        line-height: 100%;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- 功能欄位在這裡 -->
        <div class="col">
            <!-- <?= "$totalRows, $totalPages" ?> -->
            <nav aria-label="Page navigation example">
                <ul class="pagination mt-2 mb-2">
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-angle-left" href="?page"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                    <?php endif;
                    endfor; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>

                    <form method="GET">
                        <input type="text" id="searchbar" name="searchbar" class="searchbar distance" placeholder="輸入關鍵字">
                        <select name="sort" id="sort">
                            <option value="" selected disabled>誰排在前面？</option>
                            <option value="original">小編碼</option>
                            <option value="id_descend">大編碼</option>
                            <option value="cost_ascend">小金額</option>
                            <option value="cost_descend">大金額</option>
                        </select>
                        <button type="button" class="reset">重置</button>
                    </form>
                </ul>
        </div>
        <!-- 功能欄位結束了 -->
        </nav>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-trash-can"></i></th>
                    <th>#</th>
                    <th>貼圖名稱</th>
                    <th>貼圖費用</th>
                    <th>貼圖連結</th>
                    <th><i class="fa-solid fa-pen-to-square"></i></th>
                </tr>
            </thead>
            <tbody>
                <!-- while($r = $stmt->fetch()):  -->
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td>
                            <a href="javascript: delete_one(<?= $r['sticker_inventory_id'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                        <td style="max-width: 30px;">
                            <?= $r['sticker_inventory_id'] ?>
                        </td>
                        <td style="max-width: 150px;">
                            <?= $r['sticker_title'] ?>
                        </td>
                        <td style="max-width: 50px;">
                            <?= $r['sticker_cost'] ?>
                        </td>
                        <td style="max-width: 200px;">
                            <div class="photo">
                                <img src="./imgs/<?= $r['sticker_pic'] ?>">
                            </div>
                        </td>
                        <td>
                            <a href="live_sticker_inventory-edit.php?sticker_inventory_id=<?= $r['sticker_inventory_id'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- endwhile  -->
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<!-- <prev><?php
            print_r($stmt->fetch());
            print_r($stmt->fetch());
            ?></prev> -->
</div>
<?php include('./../package/packageDown.php') ?>
<?php include __DIR__ . '/parts/scripts.php' ?>

<script>
    function delete_one(
        sticker_inventory_id) {
        if (confirm(`是否要刪除編號為${sticker_inventory_id}的資料?`)) {
            location.href = `live_sticker_inventory-delete.php?sticker_inventory_id=${sticker_inventory_id}`;
        }
    }

    // 排序＆搜尋系統開始
    let sort = document.getElementById('sort')
    let submit = document.getElementById('submit')

    function changeUrl() {
        let sortValue = sort.value
        let searchbar = document.getElementById('searchbar').value
        window.location.href = `live_sticker_inventory-list-admin.php?&sort=${sortValue}&searchbar=${searchbar}&submit=`
    }

    sort.addEventListener('change', changeUrl);
    searchbar.addEventListener("change", changeUrl)

    document.addEventListener('DOMContentLoaded', function() {
        const searchResult = new URLSearchParams(window.location.search);
        const getSearchResult = searchResult.get('searchbar');

        if (getSearchResult !== null) {
            searchbar.value = decodeURIComponent(getSearchResult);
        }
    });

    let reset = document.querySelector('.reset')
    reset.addEventListener("click", event => {
        window.location.href = `live_sticker_inventory-list-admin.php`
    })
    // 排序＆搜尋系統結束

    // SideProject 計時器開始
    let clock = document.querySelector('.clock')
    let nowTime = () => {
        let date = new Date();
        return date.toLocaleTimeString()
    }

    setInterval(() => {
        clock.innerHTML = `現在 ${nowTime()}`
    }, 1000)

    let startTime = 0
    let elapsedTime = 0
    let timePassed = document.querySelector('.timePassed')

    function timer() {
        startTime = Date.now() - elapsedTime
        setInterval(update, 1000)
    }

    function update() {
        let currentTime = Date.now();
        elapsedTime = currentTime - startTime;

        let minutes = Math.floor(elapsedTime / (1000 * 60) % 60)
        let secs = Math.floor(elapsedTime / 1000 % 60)

        minutes = String(minutes).padStart(2, "0");
        secs = String(secs).padStart(2, "0");

        timePassed.innerHTML = `閒置 ${minutes}:${secs}`
    }

    timer()
    // SideProject 計時器結束
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>