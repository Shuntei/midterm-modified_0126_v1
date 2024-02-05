<?php
$dir = __DIR__ . '/upload-photos/'; # 存放檔案的資料夾

$exts = [   # 檔案類型的篩選
  'image/jpeg' => '.jpg',
  'image/png' =>  '.png',
  'image/webp' => '.webp',
];

$output = [
  'success' => false,
  'file' => '',
  'data'=>$_FILES
]; # 輸出的格式

# 確保有上傳檔案，並且有 avatar 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['picture']) and $_FILES['picture']['error'] == 0) {
  # 如果類型有對應到副檔名
  if (!empty($exts[$_FILES['picture']['type']])) {
    $ext = $exts[$_FILES['picture']['type']]; # 副檔名
    $f = sha1($_FILES['picture']['name'] . uniqid()); # 隨機的主檔名
    if (move_uploaded_file($_FILES['picture']['tmp_name'], $dir . $f . $ext)) {
      $output['success'] = true;
      $output['file'] = $f . $ext;
    }
  }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);



// 多張
// <?php
// $dir = __DIR__. './upload-photos/'; # 存放檔案的資料夾
// $exts = [   # 檔案類型的篩選
//   'image/jpeg' => '.jpg',
//   'image/png' =>  '.png',
//   'image/webp' => '.webp',
// ];

// $output = [ 'success' => false, 'files' => [] ];    # 輸出的格式
// if (!empty($_FILES) and !empty($_FILES['image_url'])) {
//   if (is_array($_FILES['image_url']['name'])) {    # 是不是陣列
//     foreach ($_FILES['image_url']['name'] as $i => $name) {
//       if (!empty($exts[$_FILES['image_url']['type'][$i]]) and $_FILES['image_url']['error'][$i]==0) {
//         $ext = $exts[$_FILES['image_url']['type'][$i]]; # 副檔名
//         $f = sha1($name . uniqid() . rand());# 隨機的主檔名
//         if ( move_uploaded_file( $_FILES['image_url']['tmp_name'][$i], $dir . $f . $ext) ) {
//           $output['files'][] = $f . $ext;  // array push
//         }
//       }
//     }
//     if (count($output['files'])) {
//       $output['success'] = true;
//     }
//   }
// }
// header('Content-Type: application/json');
// echo json_encode($output);


// gpt
// $output = [
//   "success" => false,
//   "error" => "",
//   "files" => [],
// ];

// if (!empty($_FILES['image_url'])) {
//   $uploadDirectory = __DIR__ . '/upload-photos/';

//   foreach ($_FILES['image_url']['tmp_name'] as $key => $tmp_name) {
//     $fileName = $_FILES['image_url']['name'][$key];
//     $targetPath = $uploadDirectory . $fileName;

//     if (move_uploaded_file($tmp_name, $targetPath)) {
//       $output['files'][] = $fileName;
//     } else {
//       $output['error'] = '無法移動上傳的文件。';
//     }
//   }

//   $output['success'] = true;
// } else {
//   $output['error'] = '未上傳文件。';
// }

// header('Content-Type: application/json');
// echo json_encode($output);