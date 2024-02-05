<?php
$dir = __DIR__ . "/imgs/"; # 存放檔案的資料夾

$exts = [   # 檔案類型的篩選
  'image/jpeg' => '.jpg',
  'image/png' =>  '.png',
  'image/webp' => '.webp',
];

$output = [ 
  'success' => false,
  'file' => '' ,
  'data'=> $_FILES  
]; # 輸出的格式

  
# 確保有上傳檔案，並且有 sticker 欄位，並且沒有錯誤
if (!empty($_FILES) and !empty($_FILES['sticker']) and $_FILES['sticker']['error']==0) {
  # 如果類型有對應到副檔名
  if (!empty( $exts[$_FILES['sticker']['type']] )) {
    $ext = $exts[$_FILES['sticker']['type']]; # 副檔名
    $f = sha1($_FILES['sticker']['name']. uniqid()); # 隨機的主檔名
    if ( move_uploaded_file( $_FILES['sticker']['tmp_name'], $dir . $f. $ext ) ) {
      $output['success'] = true;
      $output['file'] = $f. $ext;
    }
  }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
