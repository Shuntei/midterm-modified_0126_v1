<?php
require __DIR__ . '/parts/db_connect_midterm.php';
$pageName = 'list';
$title = '列表';

function highlightTerm($text, $searchTerm)
{
    // Highlight the search term in the text
    $highlightedText = preg_replace("/\b($searchTerm)\b/i", '<span class="highlight">$1</span>', $text);

    return $highlightedText;
}


// Fetch ruin_name values from tr_location table
$ruinNameList = [];
$namesql = "SELECT ruin_id, ruin_name FROM tr_location";
$nameStmt = $pdo->query($namesql);
while ($row = $nameStmt->fetch(PDO::FETCH_ASSOC)) {
    $ruinNameList[$row['ruin_id']] = $row['ruin_name'];
}

// Fetch level_name values from tr_level table
$lvlList = [];
$lvlsql = "SELECT level_id, level_name FROM tr_level";
$lvlstmt = $pdo->query($lvlsql);
while ($row = $lvlstmt->fetch(PDO::FETCH_ASSOC)) {
    $lvlList[$row['level_id']] = $row['level_name'];
}

$perPage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchStartDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$searchEndDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// totalpage changes accordingly to the search results
$t_sql = "SELECT COUNT(1) FROM tr_tour";

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

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    // search function start, show search result
    $searchColumn = isset($_GET['search_column']) ? $_GET['search_column'] : 'title';

    $sql = "SELECT * FROM tr_tour WHERE 1"; // Start with a basic query
    if (!empty($search)) {
        if ($searchColumn === 'title') {
            $sql .= " AND title LIKE :search";
        } elseif ($searchColumn === 'ruin_id') {
            $sql .= " AND ruin_id IN (SELECT ruin_id FROM tr_location WHERE ruin_name LIKE :search)";
        } elseif ($searchColumn === 'user_id') {
            $sql .= " AND user_id = :search";
        }
    }
    if (!empty($searchStartDate) && !empty($searchEndDate)) {
        $sql .= " AND event_date BETWEEN :start_date AND :end_date";
    }
    
    // Order by and limit
    $sql .= " ORDER BY tour_id ASC LIMIT " . (($page - 1) * $perPage) . ", " . $perPage;

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
        max-width: 10vw !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <!-- navbar -->
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mb-lg-0" style="width: 100;">
                                    <li class="nav-item">
                                        <a class="me-2 nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_list_admin.php">列表</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="me-2 nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_add.php">新增</a>
                                    </li> -->
                                    <!-- <form class="d-flex" method="GET" action="tr_tour_list_admin.php">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                                    <button class="btn btn-success" type="submit">Search</button>
                                    </form> -->
                                    <!-- search for time period -->
                                    <form class="d-flex" method="GET" action="tr_tour_list_admin.php">
                                        <select class="form-select me-2" name="search_column" id="search_column">
                                            <option disabled selected>選擇欄位</option>
                                            <option value="title" <?= isset($_GET['search_column']) && $_GET['search_column'] === 'title' ? 'selected' : '' ?>>標題</option>
                                            <option value="ruin_id" <?= isset($_GET['search_column']) && $_GET['search_column'] === 'ruin_id' ? 'selected' : '' ?>>地點</option>
                                            <!-- <option value="user_id" <?= isset($_GET['search_column']) && $_GET['search_column'] === 'user_id' ? 'selected' : '' ?>>會員編號</option>
                                            <option value="max_groupsize" <?= isset($_GET['search_column']) && $_GET['search_column'] === 'max_groupsize' ? 'selected' : '' ?>>總人數</option>
                                            <option value="level_id" <?= isset($_GET['search_column']) && $_GET['search_column'] === 'level_id' ? 'selected' : '' ?>>難易度</option> -->
                                        </select>
                                        <input class="form-control me-2" type="search" placeholder="Search Title" aria-label="Search" name="search" value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                                        <div style="white-space: nowrap;">活動日期：</div>
                                        <label class="me-2 flex-nowrap" for="start_date" style="white-space: nowrap;" display="hidden"></label>
                                        <input class="form-control me-2" type="date" id="start_date" name="start_date" value="<?= isset($searchStartDate) ? htmlspecialchars($searchStartDate) : '' ?>">
                                        <label class="me-2 flex-nowrap" for="end_date" style="white-space: nowrap;">至</label>
                                        <input class="form-control me-2" type="date" id="end_date" name="end_date" value="<?= isset($searchEndDate) ? htmlspecialchars($searchEndDate) : '' ?>">
                                        <button class="btn text-light" type="submit" style="background-color: rgb(13, 110, 253); padding:2px 15px">Search</button>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <!--  add/delete start-->
                    <div>
                        <a class="btn text-light" href="./tr_tour_add.php" style="background-color: rgb(13, 110, 253); padding:10px">
                            <i class="fa-solid fa-file-circle-plus"></i>新增
                        </a>
                        <button type="button" class="btn text-light" id="deleteSelected" style="background-color: rgb(13, 110, 253); padding:10px">
                            <i class="fa-solid fa-trash-can"></i>
                            刪除
                        </button>
                    </div>
                    <!--  add/delete end-->
                    <?php
                    if (empty($rows) && !empty($search)) {
                        echo '<div class="alert alert-light text-danger fw-bold" role="alert">No results found.</div>';
                    } else if (empty($rows) && empty($search) && !empty($searchStartDate) && !empty($searchStartDate)) {
                        echo '<div class="alert alert-light text-danger fw-bold" role="alert">No results found.</div>';
                    } else {
                    ?>
                        <form id="deleteForm" method="post" action="tr_tour_delete.php">
                            <div class="table-responsive">
                                <table class="table table-hover"> <!-- data-toggle="table" data-show-columns="true" -->
                                    <thead>
                                        <tr class="text-center">
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th></th>
                                            <th>#</th>
                                            <th>會員編號
                                                <!-- <a href="#" class="link-dark"><i class="fa-solid fa-angle-up"></i><i class="fa-solid fa-angle-down"></i></a> -->
                                            </th>
                                            <th>地點</th>
                                            <th>活動日期</th>
                                            <th>總人數</th>
                                            <th>活動時長(hr)</th>
                                            <th>難易度</th>
                                            <th>標題</th>
                                            <th>簡介</th>
                                            <th>內文</th>
                                            <th>影片連結</th>
                                            <th>建立時間</th>
                                            <th><!--<i class="fa-solid fa-pen-to-square"></i>--></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- while($r = $stmt->fetch()):  -->
                                        <?php foreach ($rows as $r) : ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_tours[]" value="<?= $r['tour_id'] ?>">
                                                </td>
                                                <td>
                                                    <a href="javascript: delete_one(<?= $r['tour_id'] ?>)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center"><?= $r['tour_id'] ?></td>
                                                <td class="text-center"><?= $r['user_id'] ?></td>
                                                <td><?= isset($ruinNameList[$r['ruin_id']]) ? $ruinNameList[$r['ruin_id']] : $r['ruin_id'] ?></td>
                                                <td><?= $r['event_date'] ?></td>
                                                <td class="text-end"><?= $r['max_groupsize'] ?></td>
                                                <td class="text-end"><?= $r['event_period'] ?></td>
                                                <td class="text-center"><?= isset($lvlList[$r['level_id']]) ? $lvlList[$r['level_id']] : $r['level_id'] ?></td>
                                                <td><?= highlightTerm($r['title'], $search) ?></td>
                                                <td class="trunc-text"><?= $r['description'] ?></td>
                                                <td class="trunc-text" data-toggle="tooltip" data-placement="top" title="<?= $r['content'] ?>"><?= $r['content'] ?></td>
                                                <td><?= $r['video_url'] ?></td>
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
                        </form>
                    <?php } ?>
                    <!--  page navigation start-->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-2">
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
                </div>
            </div>
        </div>
    </div>
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
    document.addEventListener('DOMContentLoaded', () => {
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        // Update the min attribute of end_date based on start_date selection
        startDateInput.addEventListener('input', () => {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
    });

    // Check/Uncheck all
    document.addEventListener('DOMContentLoaded', function() {
        var checkAllCheckbox = document.getElementById('checkAll');
        var checkboxes = document.querySelectorAll('input[name="selected_tours[]"]');

        // Toggle all checkboxes when "Check All" is clicked
        checkAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = checkAllCheckbox.checked;
            });
        });

        // Update "Check All" checkbox when individual checkboxes are clicked
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                checkAllCheckbox.checked = Array.from(checkboxes).every(function(checkbox) {
                    return checkbox.checked;
                });
            });
        });

        // delete checked rows
        var deleteButton = document.getElementById('deleteSelected');
        var deleteForm = document.getElementById('deleteForm');

        deleteButton.addEventListener('click', function() {
            // Filter checked checkboxes
            var selectedCheckboxes = Array.from(checkboxes).filter(function(checkbox) {
                return checkbox.checked;
            });

            // Check if any checkboxes are selected
            if (selectedCheckboxes.length > 0) {
                // Get tour_id values of selected rows
                var selectedTourIds = selectedCheckboxes.map(function(checkbox) {
                    return checkbox.value;
                });
                // Confirm deletion with tour_id details
                if (confirm(`是否要刪除編號為${selectedTourIds.join(', ')}的資料?`)) {
                    deleteForm.submit();
                }
            } else {
                alert('請先勾選欲刪除的資料');
            }
        });
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>