<?php
$dir = __DIR__. '/3dmodel/'; # 存放檔案的資料夾

$exts = [
          'model/gltf+json' => '.gltf',
          'application/octet-stream' => '. gltf',
          'text/plain' => '.gltf',
      ];

$output = [ 
  'success' => false,
  'file' => '' ]; # 輸出的格式
  
# 確保有上傳檔案，並且有 avatar 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['model']) and $_FILES['model']['error']==0) {
  # 如果類型有對應到副檔名
  if (!empty( $exts[$_FILES['model']['type']] )) {
    $ext = $exts[$_FILES['model']['type']]; # 副檔名
    $f = sha1($_FILES['model']['name']. uniqid()); # 隨機的主檔名
    if ( move_uploaded_file( $_FILES['model']['tmp_name'], $dir . $f. $ext ) ) {
      $output['success'] = true;
      $output['file'] = $f. $ext;
    }
  }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);