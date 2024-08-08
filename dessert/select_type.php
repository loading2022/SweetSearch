<?php
require_once('../db.php'); // 引入資料庫連線
// session_start();
?>
<?php
$sqlDesstype = "SELECT desstype_ID, desstype_Name FROM desstype";
$resultDesstype = $conn->query($sqlDesstype);

// 将 desstype 选项存储到数组中
$desstypeOptions = array();
while ($rowDesstype = $resultDesstype->fetch_assoc()) {
    $desstypeOptions[] = $rowDesstype;
}

// 在 JavaScript 中使用 json_encode 将数组转换为 JSON 格式的字符串
$desstypeJson = json_encode($desstypeOptions);
// 設定 HTTP 標頭，允許跨域請求
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// 輸出 JSON 資料
echo $desstypeJson;
// 關閉連接
$conn->close();
?>