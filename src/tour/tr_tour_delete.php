<!-- <?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
    $sql = "DELETE FROM tr_tour WHERE tour_id=$tour_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'tr_tour_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);
?> -->

<?php
require __DIR__ . "/parts/db_connect_midterm.php";

if (isset($_POST['selected_tours'])) {
    // Sanitize and prepare the tour IDs
    $selectedTours = array_map('intval', $_POST['selected_tours']);
    $tourIds = implode(',', $selectedTours);

    // Perform the delete operation
    $sql = "DELETE FROM tr_tour WHERE tour_id IN ($tourIds)";
    $pdo->query($sql);

    // Redirect back to the original page
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'tr_tour_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);
    exit;
} else {
    // Handle cases where the form is not submitted
    // Redirect to the desired page or display an error message
}
?>
