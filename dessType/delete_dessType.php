<?php
require_once('../db.php'); // 引入資料庫連線
// session_start();
?>
<?php
$desstypeID = $_GET['id'];
$sql = "DELETE FROM desstype WHERE desstype_ID='$desstypeID'";

if ($conn->query($sql) === TRUE)
{
    echo "成功刪除";
    header("Location: ./manager_dessType_index.php");
    exit;
}
else
{
    // 如果有錯誤，可以返回錯誤的回應
    // echo "沒有成功刪除";
    echo "Error deleting record: " . $conn->error;
}

// 關閉連接
$conn->close();
?>