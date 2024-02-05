<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = '好友';
$title = '好友';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_friends";

// $t_stmt = $pdo->query($t_sql);
// $row = $t_stmt->fetch(PDO::FETCH_NUM);
$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];

$order = isset($_GET['order']) ? $_GET['order'] : '';
// 切換排序順序
$newOrder = ($order === 'asc') ? 'desc' : 'asc';
// 生成帶有新排序順序的 URL(第二種寫法,a href要帶入$toggleUrl)
// $toggleUrl = $_SERVER['PHP_SELF'] . "?order=$newOrder";

$toggle = isset($_GET['toggleImg']) ? $_GET['toggleImg'] : '';
$imgChange = ($toggle === "<i class='fa-solid fa-down-long text-white'></i>") ? "<i class='fa-solid fa-up-long text-white'></i>" : "<i class='fa-solid fa-down-long text-white'></i>";

$inputSearch = isset($_POST['search']) ? $_POST['search'] : '';
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$selectedOption = isset($_POST['search_type']) ? $_POST['search_type'] : '';

// Add this query to fetch distinct values from the "status" column
$statusQuery = "SELECT DISTINCT status FROM sn_friends";
$statusStmt = $pdo->query($statusQuery);
$statusOptions = $statusStmt->fetchAll(PDO::FETCH_COLUMN);

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    if (!$startDate && !$endDate && !$inputSearch) {
        $sql = sprintf("SELECT * FROM sn_friends ORDER BY friend_timestamp $newOrder 
        LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    } else {
        if ($inputSearch && !$startDate && !$endDate) {
            $sql = "SELECT * FROM sn_friends WHERE $selectedOption LIKE '%$inputSearch%'
            ORDER BY friend_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
        } else {
            if ($startDate && !$endDate) {
                $sql = "SELECT * FROM sn_friends WHERE $selectedOption LIKE '%$inputSearch%'
                AND (friend_timestamp BETWEEN '$startDate' AND '$startDate 23:59:59')
                ORDER BY friend_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
            } else {
                $sql = "SELECT * FROM sn_friends WHERE $selectedOption LIKE '%$inputSearch%'
                AND (friend_timestamp BETWEEN '$startDate' AND '$endDate 23:59:59')
                ORDER BY friend_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
            }
        }
    }
    // $sql = sprintf("SELECT * FROM sn_friends ORDER BY friendship_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<!-- <?php include __DIR__ . '/parts/navbar.php' ?> -->

<div class="container-fluid px-5" style="background-color: #6C757D;">
    <div class="row mb-5">
        <div class="col" style="padding-bottom: 100000px">
            <h3 class="my-2 text-center fw-bold mt-4 mb-5">Friends</h3>
            <nav aria-label="Page navigation example" class="d-flex justify-content-between">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link text-dark" href="?page=<?= 1 ?>">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link text-dark" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-angle-left" href="?page"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link text-dark" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                    <?php endif;
                    endfor; ?>

                    <li class="page-item">
                        <a class="page-link text-dark" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link text-dark" href="?page=<?= $totalPages ?>">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
                <ul class="d-flex">
                    <li>
                        <nav class="navbar bg-light rounded pb-1">
                            <div class="container-fluid">
                                <form class="d-flex" method="POST" action="friends.php?">
                                    <!-- 測試 -->
                                    <label for="inputOptions" style="white-space:nowrap" class="me-2"><i class="fa-solid fa-filter fs-5 pt-1"></i></label>
                                    <select id="inputOptions" name="search_type" class="form-select form-select-sm me-2 search-custom focus-ring focus-ring-light" style="height: 32px">
                                        <!-- <option value="user_id">user_id</option>
                                        <option value="status">status</option> -->
                                        <option value="user_id" <?= ($selectedOption === 'user_id') ? 'selected' : '' ?>>user_id</option>
                                        <option value="friendship_id" <?= ($selectedOption === 'status') ? 'selected' : '' ?>>friendship_id</option>
                                        <option value="status" <?= ($selectedOption === 'status') ? 'selected' : '' ?>>status</option>
                                    </select>
                                    <button class="me-2 btn btn-outline-dark border-0 btn-sm"><i class="fa-solid fa-paper-plane"></i></button>
                                    <?php if ($selectedOption === 'status') : ?>
                                        <select name="search" class="form-select form-select-sm me-2 search-custom focus-ring focus-ring-light" style="height: 32px">
                                            <?php foreach ($statusOptions as $option) : ?>
                                                <option value="<?= $option ?>" <?= ($inputSearch === $option) ? 'selected' : '' ?>>
                                                    <?= $option ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else : ?>
                                        <input class="form-control search-custom me-1" type="search" name="search" placeholder="Search" value="<?= $inputSearch ?>" aria-label="Search">
                                    <?php endif; ?>
                                    <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control me-2 px-1 search-custom" id="startDate">
                                    <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control me-2 px-1 search-custom" id="endDate">
                                    <!-- <input class="form-control search-custom me-1" type="search" name="search" placeholder="Search" value="<?= $inputSearch ?>" aria-label="Search"> -->
                                    <button class="btn btn-outline-dark btn-sm py-0 border-white" type="submit"><i class="fa-solid fa-magnifying-glass fs-5"></i></button>
                                    <a href="friends-create.php" class="text-decoration-none fs-10 btn btn-outline-dark btn-sm border-white me-2"><i class="fa-solid fa-user-plus fs-5"></i></a>
                                    <a href="friends.php" class="d-flex align-baseline align-self-center text-decoration-none mx-1">
                                        <i class="fa-solid fa-arrow-rotate-right fs-5 page-link text-dark"></i></a>
                                </form>
                            </div>
                        </nav>
                    </li>
                </ul>
            </nav>
            <?php if ($rows) : ?>
                <table class="table table-light table-hover">
                    <thead>
                        <tr class="table-dark">
                            <th style="border-radius: 10px 0 0 0">remove</i></th>
                            <th>edit status</i></th>
                            <th>friendship_id</th>
                            <th>user_id</th>
                            <th>friend_id</th>
                            <th>status</th>
                            <th style="border-radius: 0 10px 0 0">
                                friend_timestamp
                                <a href="?order=<?= $newOrder; ?>&toggleImg=<?= $imgChange; ?>" class="text-decoration-none">
                                    <?= $imgChange ?>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- while($r = $stmt->fetch()):  -->
                        <?php foreach ($rows as $r) : ?>
                            <tr>
                                <td>
                                    <a href="javascript: delete_one(<?= $r['friendship_id'] ?>)">
                                        <i class="fa-solid fa-trash-can text-dark"></i>
                                    </a>
                                </td>
                                <td>
                                    <!-- <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"> -->
                                    <a href="friends-edit.php?friendship_id=<?= $r['friendship_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square text-dark"></i>
                                    </a>
                                    <!-- </button> -->
                                </td>
                                <td><?= $r['friendship_id'] ?></td>
                                <td><?= $r['user_id'] ?></td>
                                <td><?= $r['friend_id'] ?></td>
                                <td><?php if(!$r['status']) :?>
                                    <span class="text-danger">未加好友</span><?php else: ?><?= $r['status'] ?><?php endif; ?>
                                </td>
                                <td><?= $r['friend_timestamp'] ?></td>
                            </tr>
                            <!-- endwhile  -->
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="text-center fs-5 text-warning mb-5 fw-bold" style="padding-bottom: 100000px;">Sorry, we couldn't find any results.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(friendship_id) {
        if (confirm(`是否要刪除編號為${friendship_id}的好友?`)) {
            location.href = `friends-delete.php?friendship_id=${friendship_id}`;
        }
    }
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>