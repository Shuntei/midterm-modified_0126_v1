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

$t_sql = "SELECT COUNT(1) FROM tr_tour_comment";

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

$totalRows = $row[0];
$totalPages = 0;
$rows = [];


if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM tr_tour_comment ORDER BY tour_comment_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
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
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="./tr_tour_comment_list_admin.php">列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="./tr_tour_comment_add.php">新增</a>
                    </li>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fa-solid fa-trash-can"></i></th>
                                    <th>#</th>
                                    <th>會員編號</th>
                                    <th>揪團編號</th>
                                    <th>留言內容</th>
                                    <th>回覆編號</th>
                                    <th>建立時間</th>
                                    <th><i class="fa-solid fa-pen-to-square"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- while($r = $stmt->fetch()):  -->
                                <?php foreach ($rows as $r) : ?>
                                    <tr>
                                        <td>
                                            <a href="javascript: delete_one(<?= $r['tour_comment_id'] ?>)">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                        <td><?= $r['tour_comment_id'] ?></td>
                                        <td><?= $r['user_id'] ?></td>
                                        <td><?= $r['tour_id'] ?></td>
                                        <td><?= $r['comment_content'] ?></td>
                                        <td><?= $r['comment_parent_id'] ?></td>
                                        <td><?= $r['comment_time'] ?></td>
                                        <td>
                                            <a href="tr_tour_comment_edit.php?tour_comment_id=<?= $r['tour_comment_id'] ?>">
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
    function delete_one(tour_comment_id) {
        if (confirm(`是否要刪除編號為${tour_comment_id}的資料?`)) {
            location.href = `tr_tour_comment_delete.php?tour_comment_id=${tour_comment_id}`;
        }
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();
        let name_value = search_byname.value;
        location.href = "ca_merchandise_list_admin.php?item_name=" + name_value;
    });
</script>

<?php include __DIR__ . '/parts/html-foot.php' ?>