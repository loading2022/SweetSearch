<?php
require_once '../db.php';
//session_start();

// 檢查是否有 POST 數據
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $shopID = $_POST['shop_id'];
        $userID =$_SESSION['nowUser']['user_ID'];
        $comContent = $_POST['comment-content'];
        $rating =isset($_POST['selected_edit_rating']) ? intval($_POST['selected_edit_rating']) : 0;
        if (isset($_POST['comment-update'])) {
            $updateSql = "UPDATE comment SET com_Content = '$comContent',com_Rating='$rating' WHERE shop_ID = '$shopID' AND user_ID='$userID'";
            if ($conn->query($updateSql) === TRUE) {
                header("Location: ../shop/shop_info.php?shop_id=" . $shopID);
                exit;
            } else {
                echo "Error updating comment: ";
            }
        }
    // 關閉數據庫連接
    $conn->close();
}
?>
