<?php
require __DIR__ . '/parts/db_connect.php';
$pageName = 'list';
$title = 'public-boards';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_public_boards";

// $t_stmt = $pdo->query($t_sql);
// $row = $t_stmt->fetch(PDO::FETCH_NUM);
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
    $sql = sprintf("SELECT * FROM sn_public_boards ORDER BY board_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>



<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>

<div class="container-fluid overflow-auto">
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= 1 ?>">
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
                        <a class="page-link" href="?page=<?= $totalPages ?>">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>board_id</th>
                        <th>board_name</th>
                        <th>description</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- while($r = $stmt->fetch()):  -->
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><?= $r['board_id'] ?></td>
                            <td>
                                <button onclick="chooseBoard(event)" id="show">
                                    <?= $r['board_name'] ?>
                                </button>
                            </td>
                            <td><?= $r['description'] ?></td>
                        </tr>
                        <!-- endwhile  -->
                    <?php endforeach ?>
                </tbody>
            </table>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>post_id</th>
                        <th>board_id</th>
                        <th>content</th>
                        <th>image_url</th>
                        <th>video_url</th>
                        <th>location</th>
                        <th>tagged_users</th>
                        <th>posts_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row_board_post as $r_posts) : ?>
                        <tr>
                            <td><?= $r_posts['post_id'] ?></td>
                            <td><?= $r_posts['board_id'] ?></td>
                            <td><?= $r_posts['content'] ?></td>
                            <td><?= $r_posts['image_url'] ?></td>
                            <td><?= $r_posts['video_url'] ?></td>
                            <td><?= $r_posts['location'] ?></td>
                            <td><?= $r_posts['tagged_users'] ?></td>
                            <td><?= $r_posts['posts_timestamp'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(sid) {
        if (confirm(`是否要刪除編號為${sid}的資料?`)) {
            location.href = `delete.php?comment_id=${sid}`;
        }
    }

    let show = document.querySelector('#show');

    const chooseBoard = () => {

    }
</script>
<?php include __DIR__ . '/parts/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>