<?php

require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';

$perPage = 15;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM live_get_point";

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);
}

if (isset($_GET['sort'])) {
    $selectedSort = $_GET["sort"];
    switch ($selectedSort) {
        case 'time_descend':
            $sortColumn = "date_get_point";
            $sortDisplay = 'DESC';
            break;
        case 'time_ascend':
            $sortColumn = "date_get_point";
            $sortDisplay = 'ASC';
            break;
        case 'point_descend':
            $sortColumn = "received_point";
            $sortDisplay = 'DESC';
            break;
        case 'point_ascend':
            $sortColumn = "received_point";
            $sortDisplay = 'ASC';
            break;
        case 'id_ascend':
            $sortColumn = "get_point_id";
            $sortDisplay = 'ASC';
            break;
        case 'id_descend':
            $sortColumn = "get_point_id";
            $sortDisplay = 'DESC';
            break;
        default:
            $sortColumn = "get_point_id";
            $sortDisplay = 'DESC';
            break;
    }
} else {
    $sortColumn = "get_point_id";
    $sortDisplay = 'DESC';
}

$search = isset($_GET['searchbar']) ? $_GET['searchbar'] : "";

$searchbar_id = isset($_GET['searchbar_id']) ? $_GET['searchbar_id'] : "";
$searchingid_sql = !empty($searchbar_id) ? "WHERE user_id LIKE '%" . $searchbar_id . "%'" : "";

if (empty($searchingid_sql)) {
    $searching_sql = !empty($search) ? "WHERE point_source LIKE '%" . $search . "%'" : "";
} else {
    $searching_sql = !empty($search) ? "AND point_source LIKE '%" . $search . "%'" : "";
}

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM live_get_point %s %s ORDER BY %s %s LIMIT %s, %s", $searchingid_sql, $searching_sql, $sortColumn, $sortDisplay, ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts-get-point/html-head.php' ?>
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
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./live_get_point-add.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./live_get_point-add.php">新增</a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex justify-content-end mb-2 mb-lg-0">
                    <li class="nav-item">
                        <p class="nav-link clock me-3 fs-5">⏰ 0:00:00 PM</p>
                    </li>
                    <li class="nav-item">
                        <p class="nav-link timePassed fs-5">😴 00:00</p>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</div>
<!-- Navbar End -->

<style>
    .distance {
        margin-left: 10px;
    }

    ul.li distance {
        line-height: 100%;
    }

    .outline {
        border: 1px solid hsl(0, 0%, 0%, 0.2) !important;
        padding: 3px 5px;
    }

    .reset {
        transition: background-color 0.5 ease;
    }

    .reset:hover {
        background-color: red;
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- 功能欄位在這裡 -->
        <div class="col">
            <form method="GET" class="d-flex justify-content-center my-3">
                <input type="text" id="searchbar_id" name="searchbar_id" class="searchbar distance ps-2 me-3 page-link border" type="search" placeholder="搜尋用戶ID">

                <input type="text" id="searchbar" name="searchbar" class="searchbar distance ps-2 me-3 page-link border" type="search" placeholder="搜尋點數來源">
                
                <select name="sort" id="sort" class="me-3 page-link border">
                    <option value="" selected disabled>誰排在前面？</option>
                    <option value="id_descend">最新資料</option>
                    <option value="id_ascend">最舊資料</option>
                    <option value="point_descend">點數大👉小</option>
                    <option value="point_ascend">點數小👉大</option>
                    <option value="time_descend">時間近👉遠</option>
                    <option value="time_ascend">時間遠👉近</option>
                </select>
                <button type="button" class="reset me-3 page-link border border-light outline">重置</button>
            </form>
            </ul>
            <!-- 功能欄位結束了 -->
            </nav>
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>用戶ID</th>
                        <th>獲得點數</th>
                        <th>點數來源</th>
                        <th>獲得時間</th>
                        <th><i class="fa-solid fa-pen-to-square"></i></th>
                        <th><i class="fa-solid fa-trash-can"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <?= $r['get_point_id'] ?>
                            </td>
                            <td>
                                <?= $r['user_id'] ?>
                            </td>
                            <td>
                                <?= $r['received_point'] ?>
                            </td>
                            <td>
                                <?= $r['point_source'] ?>
                            </td>
                            <td>
                                <?= $r['date_get_point'] ?>
                            </td>
                            <td>
                                <a href="live_get_point-edit.php?get_point_id=<?= $r['get_point_id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td>
                                <a href="javascript: delete_one(<?= $r['get_point_id'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- endwhile  -->
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- 功能欄位在這裡 -->
            <div class="col d-flex justify-content-center">
                <!-- <?= "$totalRows, $totalPages" ?> -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination mt-2 mb-2">
                        <li class="page-item">
                            <a class="page-link"  href="?page=<?= $page - 1 ?>">
                                <i class="fa-solid fa-angle-left"></i>
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
                    </ul>
                </nav>
            </div>
            <!-- <prev><?php
                        print_r($stmt->fetch());
                        print_r($stmt->fetch());
                        ?></prev> -->
        </div>
        <?php include('./../package/packageDown.php') ?>
        <?php include __DIR__ . '/parts-get-point/scripts.php' ?>

        <script>
            function delete_one(
                get_point_id) {
                if (confirm(`是否要刪除編號為${get_point_id}的資料?`)) {
                    location.href = `live_get_point-delete.php?get_point_id=${get_point_id}`;
                }
            }

            // 排序＆搜尋系統開始
            let sort = document.getElementById('sort')
            let submit = document.getElementById('submit')

            function changeUrl() {
                let sortValue = sort.value
                let searchbar = document.getElementById('searchbar').value
                let searchbar_id = document.getElementById('searchbar_id').value
                window.location.href = `live_get_point-list-admin.php?sort=${sortValue}&searchbar=${searchbar}&searchbar_id=${searchbar_id}&submit=`
            }

            sort.addEventListener('change', changeUrl);
            searchbar.addEventListener("change", changeUrl)
            searchbar_id.addEventListener("change", changeUrl)


            document.addEventListener('DOMContentLoaded', function() {
                const searchResult = new URLSearchParams(window.location.search);
                const getSearchResult = searchResult.get('searchbar');
                const getSearchResult_id = searchResult.get('searchbar_id');
                const getSortValue = searchResult.get('sort');

                if (getSearchResult !== null) {
                    searchbar.value = decodeURIComponent(getSearchResult);
                }
                if (getSearchResult_id !== null) {
                    searchbar_id.value = decodeURIComponent(getSearchResult_id);
                }
                if(getSortValue !==null){
                    sort.value=getSortValue
                }
            });

            let reset = document.querySelector('.reset')
            reset.addEventListener("click", event => {
                window.location.href = `live_get_point-list-admin.php`
            })
            // 排序＆搜尋系統結束

            // SideProject 計時器開始
            let clock = document.querySelector('.clock')
            let nowTime = () => {
                let date = new Date();
                return date.toLocaleTimeString()
            }

            setInterval(() => {
                clock.innerHTML = `⏰ ${nowTime()}`
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

                timePassed.innerHTML = `😴 ${minutes}:${secs}`
            }

            timer()
            // SideProject 計時器結束
        </script>

        <?php include __DIR__ . '/parts-get-point/html-foot.php' ?>