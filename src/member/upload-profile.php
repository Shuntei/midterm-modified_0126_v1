<?php 

$dir = "../assets/images/member/";
header('Content-Type: application/json');

$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/webp' => '.webp'
];

$output = [
    'success' => false,
    'file' => '' 
];

if(!empty($_FILES) and !empty($_FILES['picture']) and $_FILES['picture']['error'] == 0){
    if(!empty($exts[$_FILES['picture']['type']])){
        $ext = $exts[$_FILES['picture']['type']];
        $f = sha1($_FILES['picture']['name']. uniqid());
        if(move_uploaded_file($_FILES['picture']['tmp_name'], $dir . $f . $ext)){
            $output['success'] = true;
            $output['file'] = $f. $ext;
        } else {
            $output['error'] = 'Failed to move the uploaded file';
        }
    }
}

echo json_encode($output, JSON_UNESCAPED_UNICODE)
?>