<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $tour_comment_id  = isset($_GET['tour_comment_id']) ? intval($_GET['tour_comment_id']) : 0;
    $sql = "DELETE FROM tr_tour_comment WHERE tour_comment_id=$tour_comment_id ";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'tr_tour_comment_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);
    
    if (isset($_POST['selected_comments'])) {
        // Sanitize and prepare the comment IDs
        $selectedComments = array_map('intval', $_POST['selected_comments']);
        $commentIds = implode(',', $selectedComments);
    
        // Perform the delete operation
        $sql = "DELETE FROM tr_tour_comment WHERE tour_comment_id IN ($commentIds)";
        $pdo->query($sql);
    
        // Redirect back to the original page
        $goto = empty($_SERVER['HTTP_REFERER']) ? 'tr_tour_comment_list_admin.php' : $_SERVER['HTTP_REFERER'];
        header('Location: ' . $goto);
        exit;
    } else {
        // Handle cases where the form is not submitted
        // Redirect to the desired page or display an error message
    }
    ?>