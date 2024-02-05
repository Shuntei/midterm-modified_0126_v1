<?php

require "./parts/db_connect_midterm.php";
include "./parts/html-head.php";
include "./../package/packageUp.php";

$pageName = 'list';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
$sortDirection = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'asc' : 'desc';
$searchEmail = isset($_GET['searchEmail']) ? $_GET['searchEmail'] : '';

if ($page < 1) {
  header('Location: ?page=1&sort=' . $sortDirection);
  exit;
}

$t_sql = "SELECT COUNT(1) from mb_user";

$row = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM);
$totalRows = $row[0];

if ($searchName || $searchEmail) {
  $totalRows = count($rows);
}

$totalPages = 0;

if ($totalRows > 1) {
  $totalPages = ceil($totalRows / $perPage);

  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages);
    exit;
  }

  $orderBy = 'user_id ' . $sortDirection;
  $sql = sprintf(
    "SELECT * from mb_user order by %s limit %s, %s",
    $orderBy,
    ($page - 1) * $perPage,
    $perPage
  );
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll();
}
?>

<style>

  .text-gray {
    color: gray;
  }

  /* div */
  .search-box {
    position: absolute;
    top: 10%;
    right: 0;
    background: white;
    height: 40px;
    border-radius: 20px;
    padding: 10px;
  }

  /* input */
  .search-input {
    outline: none;
    border: none;
    background: none;
    width: 0;
    color: black;
    float: left;
    font-size: 16px;
    transition: .3s;
  }

  .search-input::placeholder {
    color: gray;
  }

  /* icon */
  .search-btn {
    color: #fff;
    float: right;
    width: 25px;
    height: 25px;
    background: #6b6b6b;
    display: flex;
    align-items: center;
    padding-left: 1px;
    text-decoration: none;
    transition: .3s;
    position: absolute;
    top: 20%;
    right: 0;
    z-index: 1;
  }

  .search-input:focus,
  .search-input:not(:placeholder-shown) {
    width: calc(100%);
    padding: 0 6px;
  }

  .search-box:hover>.search-input {
    width: calc(100%);
    padding: 0 6px;
  }

  .search-box:hover>.search-btn,
  .search-input:focus+.search-btn,
  .search-input:not(:placeholder-shown)+.search-btn {
    background: #333232;
    color: white;
    position: absolute;
    top: 10%;
    right: -10px;
    width: 30px;
    height: 30px;
    padding-left: 3px;
  }

  #accountPlus,
  .btn {
    text-decoration: none;
    border: none;
    transition: background-color 0.3s ease;
    /* Add a smooth transition effect */
  }

  #accountPlus:hover,
  .btn:hover {
    background-color: #6b6b6b !important;
  }

  #accountPlus:hover i,
  .btn:hover i {
    color: white;
  }

  a:hover .fa-file-pen,
  a:hover .fa-trash {
    color: black;
  }
</style>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <!-- pagination start -->
      <div class="row">
        <div class="col d-flex align-items-center justify-content-between">
          <nav aria-label="Page navigation example">
            <ul class="pagination">

              <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=1">
                  <i class="fa-solid fa-angles-left"></i>
                </a>
              </li>

              <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= isset($_GET['page']) ? $_GET['page'] - 1 : 1 ?>">
                  <i class="fa-solid fa-angle-left"></i>
                </a>
              </li>


              <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                if ($i >= 1 and $i <= $totalPages) : ?>
                  <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                  </li>
              <?php endif;
              endfor; ?>

              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= isset($_GET['page']) ? $_GET['page'] + 1 : 1 ?>">
                  <i class="fa-solid fa-angle-right"></i>
                </a>
              </li>
              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                  <i class="fa-solid fa-angles-right"></i>
                </a>
              </li>
            </ul>
          </nav>
          <div>
            <a class="btn ms-3 bg-light p-2 border rounded text-dark" id="accountPlus" href="add.php">
              <i class="mdi mdi-account-plus fs-5"></i>
            </a>
            <a href="javascript:deleteSelectedRows()" class="btn ms-3 bg-light p-2 border rounded text-dark">
              <i class="bi bi-trash-fill fs-5"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- pagination end -->
      <h4 class="card-title">User Information</h4>
      <p class="card-description">
      </p>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>
                <input type="checkbox" id="selectAllCheckbox">
              </th>
              <th>ID
                <i class="bi <?= $sortDirection === 'desc' ? 'bi-arrow-down' : 'bi-arrow-up' ?> " id="sortIcon"></i>
              </th>
              <th class="position-relative d-flex align-items-center">Name
                <div class="search-box">
                  <input type="text" class="search-input searchName" placeholder="Search Name">
                  <a class="search-btn rounded-circle" href="#">
                    <i class="fas fa-search"></i>
                  </a>
                </div>
              </th>
              <th class="position-relative">Email
                <div class="search-box me-5">
                  <input type="text" class="search-input searchEmail" placeholder="Search Email">
                  <a class="search-btn rounded-circle" href="#">
                    <i class="fas fa-search"></i>
                  </a>
                </div>
              </th>
              <th class="position-relative">Phone
                <div class="search-box">
                  <input type="text" class="search-input searchPhone" placeholder="Search Phone">
                  <a class="search-btn rounded-circle" href="#">
                    <i class="fas fa-search"></i>
                  </a>
                </div>
              </th>
              <th>Birthday</th>
              <th>Created at</th>
              <th>Skin id</th>
              <th><i class="fa-solid fa-file-pen"></i></th>
              <th><i class="fa-solid fa-trash"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r) : ?>
              <tr>
                <td>
                  <input type="checkbox" class="row-checkbox" value="<?= $r['user_id'] ?>">
                </td>
                <td><?= $r['user_id'] ?></td>
                <td><?= $r['name'] ?></td>
                <td><?= $r['email'] ?></td>
                <td><?= $r['phone'] ?></td>
                <td><?= $r['birthday'] ?></td>
                <td><?= $r['created_at'] ?></td>
                <td><?= $r['fk_skin_id'] ?></td>
                <td><a href="edit.php?userId=<?= $r['user_id'] ?>&page=<?= $page ?>">
                    <i class="fa-solid fa-file-pen text-gray"></i>
                  </a>
                </td>
                <td>
                  <a href="javascript: deleteUser(<?= $r['user_id'] ?>)">
                    <i class="fa-solid fa-trash text-gray"></i>
                  </a>
                </td>
              <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include "./../package/packageDown.php";
include "./parts/scripts.php" ?>
<script>
  document.querySelector('#selectAllCheckbox').addEventListener('change', function() {
    const isChecked = this.checked

    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
      checkbox.checked = isChecked
    })
  })

  function deleteSelectedRows() {
    const selectedRows = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(checkbox => checkbox.value)

    if (selectedRows.length === 0) {
      swal("No rows selected", {
        icon: "warning",
      });
      return;
    }

    swal({
      title: "Delete selected rows?",
      text: "If you delete, the information will be lost.",
      icon: "warning",
      buttons: ["Cancel", "Delete"],
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        fetch('delete.php', {
            method: 'post',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              userIds: selectedRows
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              swal("The selected accounts have been deleted", {
                icon: "success"
              }).then(() => {
                location.reload()
              })
            }
          })
      }
    })
  }

  let sortDirection = '<?= $sortDirection ?>'
  let rows = <?= json_encode($rows) ?>;

  function toggleSortDirection() {
    sortDirection = sortDirection === 'desc' ? 'asc' : 'desc';
    location.href = `?page=<?= $page ?>&sort=${sortDirection}`;
  }

  document.querySelector('#sortIcon').addEventListener('click', () => {
    toggleSortDirection()
  })

  document.querySelector('.searchName').addEventListener('input', function() {
    const searchName = this.value.toLowerCase();

    fetch(`search.php?searchName=${searchName}&sort=${sortDirection}`)
      .then(r => r.json())
      .then(data => {
        updateUI(data)
        toggleSearchIcon(searchName, 'name');
      })
      .catch(error => console.error('Error: ', error))
  })

  document.querySelector('.searchEmail').addEventListener('input', function() {
    const searchEmail = this.value.toLowerCase();

    fetch(`search.php?searchEmail=${searchEmail}&sort=${sortDirection}`)
      .then(r => r.json())
      .then(data => {
        updateUI(data)
        toggleSearchIcon(searchEmail, 'email');
      })
      .catch(error => console.error('Error: ', error))
  })

  document.querySelector('.searchPhone').addEventListener('input', function() {
    const searchPhone = this.value.toLowerCase();

    fetch(`search.php?searchPhone=${searchPhone}&sort=${sortDirection}`)
      .then(r => r.json())
      .then(data => {
        updateUI(data)
        toggleSearchIcon(searchPhone, 'phone');
      })
      .catch(error => console.error('Error: ', error))
  })

  function toggleSearchIcon(searchItem, inputType) {
    let searchIcon;

    if (inputType === 'name') {
      searchIcon = document.querySelector('.searchName + .search-btn i')
    } else if (inputType === 'email') {
      searchIcon = document.querySelector('.searchEmail + .search-btn i')
    } else if (inputType === 'phone') {
      searchIcon = document.querySelector('.searchPhone + .search-btn i')
    }

    if (searchIcon) {
      if (searchItem.trim() !== '') {
        searchIcon.classList.replace('fa-search', 'bi-x');
        searchIcon.classList.add('fs-4', 'ms-0');
        searchIcon.addEventListener('click', clearSearch)
      } else {
        searchIcon.classList.replace('bi-x', 'fa-search');
        searchIcon.classList.remove('fs-4', 'ms-0');
        searchIcon.removeEventListener('click', clearSearch)
      }
    }
  }

  function clearSearch() {
    document.querySelector('.searchName').value = ''
    document.querySelector('.searchEmail').value = '';
    document.querySelector('.searchPhone').value = '';

    toggleSearchIcon('', 'name');
    toggleSearchIcon('', 'email');
    toggleSearchIcon('', 'phone');

    fetch(`search.php?sort=${sortDirection}`)
      .then(r => r.json())
      .then(data => {
        updateUI(data);
      })
      .catch(error => console.error('Error: ', error));
  }
  // document.querySelector('.searchName').addEventListener('blur', function(){
  //   this.value = '';
  // })

  function updateUI(data) {
    const tBody = document.querySelector('tbody')
    tBody.innerHTML = '';

    data.forEach(row => {
      const newRow = document.createElement('tr')
      newRow.innerHTML = `
          <td>
            <input type="checkbox" class="row-checkbox" value="<?= $r['user_id'] ?>">
          </td>
          <td>${row.user_id}</td>
          <td>${row.name}</td>
          <td>${row.email ?? ''}</td>
          <td>${row.phone ?? ''}</td>
          <td>${row.birthday ?? ''}</td>
          <td>${row.created_at ?? ''}</td>
          <td>${row.fk_skin_id ?? ''}</td>
          <td><a href="edit.php?userId=${row.user_id}&page=<?= $page ?>">
              <i class="fa-solid fa-file-pen text-gray"></i>
            </a>
          </td>
          <td>
            <a href="javascript: deleteUser(${row.user_id})">
              <i class="fa-solid fa-trash text-gray"></i>
            </a>
          </td>
      `;
      tBody.appendChild(newRow);

    })
  }

  function deleteUser(userId) {
    swal({
      title: `Delete the information of user ${userId}?`,
      text: "if you delete, the information will be lost.",
      icon: "warning",
      buttons: ["Cancel", "Delete"],
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        fetch(`delete.php?userId=${userId}`, {
            method: 'get'
          }).then(r => r.json())
          .then(data => {
            if (data.success) {
              swal("It has been deleted", {
                icon: "success",
              }).then(() => {
                location.reload()
              })
            }
          })
      }
    })
  }
</script>
<?php include "./parts/html-foot.php" ?>