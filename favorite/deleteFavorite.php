<?php
    require_once '../db.php';
    //session_start();
?>
<?php
$userid=$_SESSION['nowUser']['user_ID'];
$shopID = $_GET['id'];

$sql = "DELETE FROM favorite WHERE shop_ID='$shopID' AND user_ID='$userid'";

if ($conn->query($sql) === TRUE) {
    header("Location: favorite.php?userid=$userid");
    exit;
} else {
    // 如果有錯誤，可以返回錯誤的回應
    echo "Error deleting record: " . $conn->error;
}

// 關閉連接
$conn->close();
?>
