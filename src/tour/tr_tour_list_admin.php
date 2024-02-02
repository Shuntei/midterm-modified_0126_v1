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

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchStartDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$searchEndDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$t_sql = "SELECT COUNT(1) FROM tr_tour";

// totalpage changes accordingly to the search results
if (!empty($search)) {
    $t_sql .= " WHERE title LIKE :search";
}
$t_stmt = $pdo->prepare($t_sql);
if (!empty($search)) {
    $searchParam = '%' . $search . '%';
    $t_stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}
$t_stmt->execute();
$row = $t_stmt->fetch(PDO::FETCH_NUM);
// totalpage changes end

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

// if ($totalRows > 0) {
//     $totalPages = ceil($totalRows / $perPage);

//     if ($page > $totalPages) {
//         header('Location: ?page=' . $totalPages);
//         exit;
//     }
//     // search function start, show search result
//     if (!empty($search)) {
//         $sql = sprintf("SELECT * FROM tr_tour WHERE title LIKE '%%%s%%' ORDER BY tour_id DESC LIMIT %s, %s", $search, ($page - 1) * $perPage, $perPage);
//     } else {
//         $sql = sprintf("SELECT * FROM tr_tour ORDER BY tour_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
//     }
//     $stmt = $pdo->query($sql);
//     $rows = $stmt->fetchAll();
// }

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    // search function start, show search result
    $sql = "SELECT * FROM tr_tour WHERE 1"; // Start with a basic query
    if (!empty($search)) {
        $sql .= " AND title LIKE :search";
    }
    if (!empty($searchStartDate) && !empty($searchEndDate)) {
        $sql .= " AND event_date BETWEEN :start_date AND :end_date";
    }

    // Order by and limit
    $sql .= " ORDER BY tour_id DESC LIMIT " . (($page - 1) * $perPage) . ", " . $perPage;

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }
    if (!empty($searchStartDate) && !empty($searchEndDate)) {
        $stmt->bindParam(':start_date', $searchStartDate, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $searchEndDate, PDO::PARAM_STR);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll();
}


?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include './../package/packageUp.php' ?>
<?php
if (empty($pageName)) {
    $pageName = '';
}
?>
<style>
    .trunc-text {
        max-width: 20vw !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-lg-0" style="width: 100;">
                    <li class="nav-item">
                        <a class="me-2 nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_list_admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="me-2 nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_add.php">新增</a>
                    </li>

                    <!-- <form class="d-flex" method="GET" action="tr_tour_list_admin.php">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form> -->
                    <!-- search for time period -->
                    <form class="d-flex" method="GET" action="tr_tour_list_admin.php">
                        <input class="form-control me-2" type="search" placeholder="Search Title" aria-label="Search" name="search">
                        <div style="white-space: nowrap;">活動日期：</div>
                        <label class="me-2 flex-nowrap" for="start_date" style="white-space: nowrap;" display="hidden"></label>
                        <input class="form-control me-2" type="date" id="start_date" name="start_date" 
                        value="<?= isset($searchStartDate) ? htmlspecialchars($searchStartDate) : '' ?>">
                        <label class="me-2 flex-nowrap" for="end_date" style="white-space: nowrap;">至</label>
                        <input class="form-control me-2" type="date" id="end_date" name="end_date"
                        value="<?= isset($searchEndDate) ? htmlspecialchars($searchEndDate) : '' ?>">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>

                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!--  page navigation start-->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="?page=1">
                                    <i class="fa-solid fa-angles-left"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">
                                    <i class="fa-solid fa-angle-left" href="?page"></i>
                                </a>
                            </li>
                            <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                                if ($i >= 1 and $i <= $totalPages) : ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                            <?php endif;
                            endfor; ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">
                                    <i class="fa-solid fa-angle-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $totalPages; ?>">
                                    <i class="fa-solid fa-angles-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!--  page navigation end-->
                    <?php
                    if (empty($rows) && !empty($search)) {
                        echo '<div class="alert alert-light text-danger fw-bold" role="alert">No results found.</div>';
                    } else if (empty($rows) && empty($search) && !empty($searchStartDate) && !empty($searchStartDate)) {
                        echo '<div class="alert alert-light text-danger fw-bold" role="alert">No results found.</div>';
                    } else {
                    ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><!--<i class="fa-solid fa-trash-can"></i>--></th>
                                        <th>#</th>
                                        <th>會員編號</th>
                                        <th>廢墟編號</th>
                                        <th>活動日期</th>
                                        <th>總人數</th>
                                        <th>活動時長(hr)</th>
                                        <th>難易度</th>
                                        <th>影片連結</th>
                                        <th>標題</th>
                                        <th>簡介</th>
                                        <th>內文</th>
                                        <th>建立時間</th>
                                        <th><!--<i class="fa-solid fa-pen-to-square"></i>--></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- while($r = $stmt->fetch()):  -->
                                    <?php foreach ($rows as $r) : ?>
                                        <tr>
                                            <td>
                                                <a href="javascript: delete_one(<?= $r['tour_id'] ?>)">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                            <td><?= $r['tour_id'] ?></td>
                                            <td><?= $r['user_id'] ?></td>
                                            <td><?= $r['ruin_id'] ?></td>
                                            <td><?= $r['event_date'] ?></td>
                                            <td><?= $r['max_groupsize'] ?></td>
                                            <td><?= $r['event_period'] ?></td>
                                            <td><?= $r['level_id'] ?></td>
                                            <td><?= $r['video_url'] ?></td>
                                            <td><?= $r['title'] ?></td>
                                            <td class="trunc-text"><?= $r['description'] ?></td>
                                            <td class="trunc-text" data-toggle="tooltip" data-placement="top" title="<?= $r['content'] ?>"><?= $r['content'] ?></td>
                                            <td><?= $r['created_at'] ?></td>
                                            <td>
                                                <a href="tr_tour_edit.php?tour_id=<?= $r['tour_id'] ?>">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- endwhile  -->
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- <prev><?php
                print_r($stmt->fetch());
                print_r($stmt->fetch());
                ?></prev> -->
</div>

<?php include './../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(tour_id) {
        if (confirm(`是否要刪除編號為${tour_id}的資料?`)) {
            location.href = `tr_tour_delete.php?tour_id=${tour_id}`;
        }
    }

    // Content - hover over tolltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltips = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
        console.log(tooltips);
        var tooltipList = tooltips.map(function(tooltip) {
            return new bootstrap.Tooltip(tooltip);
        });
    });
    // tooltip end
    // disable date before start_date
    document.addEventListener('DOMContentLoaded', ()=> {
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        // Update the min attribute of end_date based on start_date selection
        startDateInput.addEventListener('input', ()=> {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>