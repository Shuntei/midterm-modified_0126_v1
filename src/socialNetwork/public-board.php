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
    $sql = sprintf("SELECT * FROM sn_public_boards ORDER BY board_id ASC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

?>


<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>


<div class="container-fluid">
    <div class="row">
        <h3 class="my-2 text-center fw-bold">Public Boards</h3>
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
            <div id="boardContainer" class="col">
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
                                <td style="color: #081031;"><?= $r['board_id'] ?></td>
                                <td>
                                <!-- <button>test</button> -->
                                <a href="#postTableBody" onclick="chooseBoard(<?= $r['board_id'] ?>)" class="link-opacity-50-hover text-decoration-none">
                                        <?= $r['board_name'] ?>
                                </a>
                                </td>
                                <td style="color: #081031;"><?= $r['description'] ?></td>
                            </tr>
                            <!-- endwhile  -->
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <h3 class="text-center fw-bold pt-3">Posts</h3>
            <div id="postContainer" class="col">
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
                    <tbody id="postTableBody">
                        <!-- 抓取相同board_id的post內容 -->
                        <?php
                        $sql_board_post = "SELECT * FROM sn_posts JOIN sn_public_boards USING(board_id) WHERE board_id=10";
                        $stmt = $pdo->query($sql_board_post);
                        $row_board_post = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // echo json_encode($row_board_post);
                        ?>
                        <!-- 抓取結束 -->
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
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    // function delete_one(sid) {
    //     if (confirm(`是否要刪除編號為${sid}的資料?`)) {
    //         location.href = `delete.php?comment_id=${sid}`;
    //     }
    // }

    function chooseBoard(boardId) {
        // event.preventDefault();
        // 防止移動被取消

        fetch(`public-board-api.php?board_id=${boardId}`)
        .then(response => response.json())
        .then(posts => {
            updatePostsTable(posts);
        })
        .catch(error => {
            console.log('Error fetching posts:', error);
        });
    }

    function updatePostsTable(posts) {
        const tbody = document.getElementById('postTableBody');
        // 清空現有行
        tbody.innerHTML = '';

        // 將獲取的帖子添加到表格中
        posts.map(post => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${post.post_id}</td>
                <td>${post.board_id}</td>
                <td>${post.content}</td>
                <td>${post.image_url}</td>
                <td>${post.video_url}</td>
                <td>${post.location}</td>
                <td>${post.tagged_users}</td>
                <td>${post.posts_timestamp}</td>
            `;
            tbody.appendChild(row);
        });
    }
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>