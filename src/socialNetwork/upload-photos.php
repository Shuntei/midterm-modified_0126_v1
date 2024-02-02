<?php
$dir = __DIR__. './upload-photos/'; # 存放檔案的資料夾
$exts = [   # 檔案類型的篩選
  'image/jpeg' => '.jpg',
  'image/png' =>  '.png',
  'image/webp' => '.webp',
];

$output = [ 'success' => false, 'files' => [] ];    # 輸出的格式
if (!empty($_FILES) and !empty($_FILES['image_url'])) {
  if (is_array($_FILES['image_url']['name'])) {    # 是不是陣列
    foreach ($_FILES['image_url']['name'] as $i => $name) {
      if (!empty($exts[$_FILES['image_url']['type'][$i]]) and $_FILES['image_url']['error'][$i]==0) {
        $ext = $exts[$_FILES['image_url']['type'][$i]]; # 副檔名
        $f = sha1($name . uniqid() . rand());# 隨機的主檔名
        if ( move_uploaded_file( $_FILES['image_url']['tmp_name'][$i], $dir . $f . $ext) ) {
          $output['files'][] = $f . $ext;  // array push
        }
      }
    }
    if (count($output['files'])) {
      $output['success'] = true;
    }
  }
}
header('Content-Type: application/json');
echo json_encode($output);
