<?php
$dir = __DIR__. '/3dmodel/'; # 存放檔案的資料夾

$exts = [   # 檔案類型的篩選
    'model/gltf+json' => '.gltf',
    'application/octet-stream' => '.bin',
];

$output = [ 
  'success' => false,
  'file' => '' ]; # 輸出的格式
  
# 確保有上傳檔案，並且有 avatar 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['upload_file']) and $_FILES['upload_file']['error']==0) {
  # 如果類型有對應到副檔名
  if (!empty( $exts[$_FILES['upload_file']['type']] )) {
    $ext = $exts[$_FILES['upload_file']['type']]; # 副檔名
    $f = sha1($_FILES['upload_file']['name']. uniqid()); # 隨機的主檔名
    if ( move_uploaded_file( $_FILES['upload_file']['tmp_name'], $dir . $f. $ext ) ) {
      $output['success'] = true;
      $output['file'] = $f. $ext;
    }
  }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);