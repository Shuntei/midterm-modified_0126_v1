<?php
require __DIR__ . '/parts/db_connect.php';

$pageName = 'list';
$title = 'posts';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM sn_posts";
$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);

// 抓取comment內容
$sql_cm = "SELECT * FROM sn_comments ORDER BY comment_id DESC";
$row_cm = $pdo->query($sql_cm)->fetchAll(PDO::FETCH_ASSOC);
// 抓取結束 

// 抓取comment_reply內容
$sql_reply = "SELECT * FROM sn_comments_reply";
$stmt = $pdo->query($sql_reply);
$row_reply = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 抓取結束 

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

if ($totalRows > 0) {
    $totalPages = ceil($totalRows / $perPage);

    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    if (!$startDate && !$endDate && !$inputSearch) {
        $sql = sprintf("SELECT * FROM sn_posts ORDER BY posts_timestamp $newOrder 
        LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    } else {
        if ($inputSearch && !$startDate && !$endDate) {
            $sql = "SELECT * FROM sn_posts WHERE $selectedOption LIKE '%$inputSearch%'
            ORDER BY posts_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
        } else {
            if ($startDate && !$endDate) {
                $sql = "SELECT * FROM sn_posts WHERE $selectedOption LIKE '%$inputSearch%'
                AND (posts_timestamp BETWEEN '$startDate' AND '$startDate 23:59:59')
                ORDER BY posts_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
            } else {
                $sql = "SELECT * FROM sn_posts WHERE $selectedOption LIKE '%$inputSearch%'
                AND (posts_timestamp BETWEEN '$startDate' AND '$endDate 23:59:59')
                ORDER BY posts_timestamp $newOrder LIMIT " . (($page - 1) * $perPage) . ", $perPage";
            }
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
}

function highlightSearchTerm($content, $searchTerm)
{
    if ($searchTerm) {
        // 使用 str_ireplace 進行不區分大小寫的搜尋並替換，並添加高亮樣式
        $highlightedTerm = '<span class="bg-dark text-light fs-3" style="border-radius: 3px">' . $searchTerm . '</span>';
        $content = str_ireplace($searchTerm, $highlightedTerm, $content);
    }

    return $content;
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/../package/packageUp.php' ?>
<!-- <?php include __DIR__ . '/parts/navbar.php' ?> -->
<div class="container-fluid overflow-auto px-5" style="background-color: #6C757D;">
    <div class="row">
        <div class="col">
            <h3 class="my-2 text-center fw-bold mt-4">Posts</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb border-0 ps-0">
                    <li class="breadcrumb-item"><a href="./public-board.php" class="text-decoration-none" style="color:#000">Public Boards</a></li>
                    <li class="mx-3" style="color:#000">&gt;</li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Posts &amp; Comments</li>
                    <li class="mx-3" style="color:#000">&gt;</li>
                    <li class="breadcrumb-item"><a href="./comments-reply-list-no-admin.php" class="text-decoration-none" style="color:#000">Comment Replies</a></li>
                </ol>
            </nav>
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
                                <form class="d-flex" method="POST" action="posts-list-no-admin.php?">
                                    <!-- 測試 -->
                                    <label for="inputOptions" style="white-space:nowrap" class="me-2"><i class="fa-solid fa-filter fs-5 pt-1"></i></label>
                                    <select id="inputOptions" name="search_type" class="form-select form-select-sm me-2 search-custom focus-ring focus-ring-light" style="height: 32px">
                                        <option value="content">content (posts)</option>
                                        <option value="location">location (posts)</option>
                                        <option value="board_id">border id (posts)</option>
                                    </select>
                                    <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control me-2 px-1 search-custom" id="startDate">
                                    <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control me-2 px-1 search-custom" id="endDate">
                                    <input class="form-control search-custom me-1" type="search" name="search" placeholder="Search" value="<?= $inputSearch ?>" aria-label="Search">
                                    <button class="btn btn-outline-dark btn-sm py-0 border-white" type="submit"><i class="fa-solid fa-magnifying-glass fs-5"></i></button>
                                    <a href="./posts-add.php" class="text-decoration-none fs-10 btn btn-outline-dark btn-sm border-white me-2"><i class="fa-solid fa-plus fs-5"></i></a>
                                    <a href="posts-list-no-admin.php" class="d-flex align-baseline align-self-center text-decoration-none mx-1">
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
                            <th style="border-radius: 10px 0 0 0">remove</th>
                            <th>edit</th>
                            <th>post_id</th>
                            <th>user_id</th>
                            <th>board_id</th>
                            <th class="text-center">content</th>
                            <th>image_url</th>
                            <th>video_url</th>
                            <th>location</th>
                            <th>tagged_users</th>
                            <th style="border-radius: 0 10px 0 0">posts_timestamp
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
                                    <a href="javascript: delete_one(<?= $r['post_id'] ?>)">
                                        <i class="fa-solid fa-trash-can text-dark"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="post-edit.php?post_id=<?= $r['post_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square text-dark"></i>
                                    </a>
                                </td>
                                <td><?= $r['post_id'] ?></td>
                                <td><?= $r['user_id'] ?></td>
                                <td><?= $r['board_id'] ?></td>
                                <td class="d-flex justify-content-between align-items-center">
                                    <?php if (!$r['content']) : ?>
                                        <div class="text-danger fs-6">No contents.</div>
                                        <!-- modal -->
                                        <!-- Button trigger modal -->
                                    <?php else : ?>
                                        <?php
                                        $postId = $r['post_id'];
                                        ?>
                                        <div><?= highlightSearchTerm($r['content'], $inputSearch) ?></div>
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailCm-<?= $postId ?>" id="showCm-<?= $postId ?>">
                                            查看留言
                                        </button>
                                    <?php endif; ?>
                                    <!-- Modal -->
                                    <div class="modal fade" id="detailCm-<?= $postId ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="yes">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-secondary">
                                                    <h5 class="modal-title" id="exampleModalLabel">留言</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach ($row_cm as $r_cm) : ?>
                                                        <?php if ($r['post_id'] === $r_cm['post_id']) : ?>
                                                            <span class="fs-6 fw-bold" style="white-space:normal;"><?= $r_cm['content'] . "(post id: " . $r_cm['post_id'] . ")" . "<br>"; ?></span>
                                                            <!-- 用api寫 -->
                                                            <button onclick="checkReply(<?= $r_cm['comment_id'] ?>)" style="margin-top: 5px" class="border-0 rounded px-2 py-1">查看回覆</button>
                                                            <?= "(comment id: " . $r_cm['comment_id'] . ")" ?>
                                                            <div id='showReply<?= $r_cm['comment_id'] ?>' style="margin-top: 5px;white-space: normal;"></div>
                                                            <!-- api結束 -->
                                                        <?php endif; ?>
                                                    <?php endforeach ?>
                                                    <form name="formCm<?= $r['post_id'] ?>" method="post" onsubmit="sendCmForm(<?= $r['post_id'] ?>)" class="d-flex flex-column">
                                                        <input type="hidden" name="post_id" value="<?= $r['post_id'] ?>">
                                                        <textarea type="text" id="content" name="content" placeholder="留言..." style="border: 1px solid #dee2e6;border-radius: 4px;width: 100%;padding: 14px 22px"></textarea>
                                                        <button type="submit" class="btn btn-dark btn-sm mt-2 me-2 py-1 align-self-end" style="width: 10%"><i class="fa-regular fa-circle-right"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- model -->
                                </td>
                                <td><img src="./upload-photos/<?= $r['image_url'] ?>" alt="" width="50" height="50"></td>
                                <td><?= $r['video_url'] ?></td>
                                <td><?= $r['location'] ?></td>
                                <td><?= $r['tagged_users'] ?></td>
                                <td><?= $r['posts_timestamp'] ?></td>
                            </tr>
                            <!-- endwhile  -->
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="text-center fs-5 text-warning mb-5 fw-bold">Sorry, we couldn't find any results.</div>
            <?php endif; ?>

            <h3 class="my-4 text-center fw-bold">Comments</h3>
            <table class="table table-light table-hover mb-5">
                <thead>
                    <tr class="table-dark">
                        <th style="border-radius: 10px 0 0 0">remove</th>
                        <th>comment_id</th>
                        <th>user_id</th>
                        <th>post_id</th>
                        <th>content</th>
                        <th style="border-radius: 0 10px 0 0">comment_timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row_cm as $r_cm) : ?>
                        <tr>
                            <td>
                                <a href="javascript: delete_cm(<?= $r_cm['comment_id'] ?>)">
                                    <i class="fa-solid fa-trash-can text-dark"></i>
                                </a>
                            </td>
                            <td><?= $r_cm['comment_id'] ?></td>
                            <td><?= $r_cm['user_id'] ?></td>
                            <td><?= $r_cm['post_id'] ?></td>
                            <td><?= $r_cm['content'] ?></td>
                            <td><?= $r_cm['comment_timestamp'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
    function delete_one(post_id) {
        if (confirm(`是否要刪除編號為${post_id}的貼文?`)) {
            location.href = `post-delete.php?post_id=${post_id}`;
        }
    }

    function delete_cm(comment_id) {
        if (confirm(`是否要刪除編號為${comment_id}的留言?`)) {
            location.href = `posts-comment-delete.php?comment_id=${comment_id}`;
        }
    }

    const checkReply = (commentId) => {
        event.preventDefault();

        fetch(`posts-comment-reply-api.php?comment_id=${commentId}`)
            .then(response => response.json())
            .then((replies) => {
                getReply(replies);
            }).catch((e) => {
                console.log('Error fetching parent_id:', e);
            });
    }

    const getReply = (replies) => {
        console.log('e', replies);
        showReply = document.querySelector(`#showReply${replies[0].parent_id}`);
        showReply.innerHTML = "";

        // 將獲取的回覆添加到留言下
        replies.map((items) => {
            showReply.innerHTML += `
                    <div>cr_id: ${items.cr_id}</div>
                    <div>user_id: ${items.user_id}</div>
                    <div>post_id: ${items.post_id}</div>
                    <div>content: ${items.content}</div>
                    <div>parent_id: ${items.parent_id}</div>
                    <div>comment_timestamp: ${items.comment_timestamp}</div>
                    <hr>
                `;
        });
    }

    const sendCmForm = (postId) => {
        event.preventDefault();

        let isPass = true;

        if (isPass) {
            //"沒有外觀"的表單
            console.log(postId);
            const fd = new FormData(document.forms[`formCm${postId}`]);
            console.log('確認:', fd);
            fetch(`comments-add-api.php?`, {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(result => {
                    console.log({
                        result
                    });
                    if (result.success) {
                        alert('留言成功~');
                        // location.href= "posts-list-no-admin.php";
                        location.reload();
                    }
                }).catch(
                    e => console.log('Fetching comment failed:', e)
                );
        }
    }
</script>
<?php include __DIR__ . '/../package/packageDown.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>