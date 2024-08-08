<?php
    require_once('../db.php'); // 引入資料庫連線
    // session_start();
?>
<?php
    $shopID = $_GET['id'];
    $sql = "DELETE FROM shop WHERE shop_ID='$shopID'";

    if ($conn->query($sql) === TRUE) {
        echo "成功刪除";
        header("Location: ../manager_index.php");
        exit;
    } else {
        // 如果有錯誤，可以返回錯誤的回應
        // echo "沒有成功刪除";
        echo "Error deleting record: " . $conn->error;
    }

    // 關閉連接
    $conn->close();
?>