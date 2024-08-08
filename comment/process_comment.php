<?php
require_once '../db.php';
//session_start();

// 檢查是否有 POST 數據
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shopID = $_POST['shop_id'];

    // 假設這裡有一個函數可以檢查用戶是否已經對該商店寫過評論，例如 checkIfUserReviewed($user, $shopID)
    function checkIfUserReviewed($conn, $user, $shopID) {
        $stmt = $conn->prepare("SELECT 1 FROM comment WHERE user_ID = ? AND shop_ID = ?");
        $stmt->bind_param("ss", $user, $shopID);
        $stmt->execute();
        $stmt->store_result();
        $result = $stmt->num_rows > 0;
        $stmt->close();
        return $result;
    }

    $user=$_SESSION['nowUser']['user_ID'];
    $rating =isset($_POST['selected_rating']) ? intval($_POST['selected_rating']) : 0;
    $comment = $_POST['comment-content']; // 評論內容
    $today = date("Y-m-d");
    $sql = "INSERT INTO comment (shop_ID, com_Date, com_Content, com_Rating, user_ID) VALUES ('$shopID', '$today', '$comment', '$rating', '$user')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../shop/shop_info.php?shop_id=" . $shopID);
        exit;
    } else {
        echo $rating;
        echo "Error: " . $sql . "<br>";
    }

    // 關閉數據庫連接
    $conn->close();
}
?>
