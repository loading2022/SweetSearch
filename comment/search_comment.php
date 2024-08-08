<?php
    require_once '../db.php';
    
// 初始化一個變數來保存搜尋結果的 HTML 代碼
$searchResultHTML = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_keyword = $_POST['comment-keyword'];
    $comment_rating_choice=$_POST['comment-rating-choice'];
    $shopID = $_POST['shop_id'];

    if ($comment_keyword != null) {
        $sql = "SELECT comment.*,user.* FROM comment,user WHERE shop_ID='$shopID' AND com_Content LIKE '%$comment_keyword%' AND comment.user_ID=user.user_ID";
    }
    else{
        $sql = "SELECT comment.*,user.* FROM comment,user WHERE shop_ID='$shopID' AND comment.user_ID=user.user_ID";  
    }
    if($comment_rating_choice!="全部")
    {
        $sql .= " AND com_Rating=$comment_rating_choice";
    }
    $result = $conn->query($sql);
} 
else {
    $searchResultHTML = "非法請求";
}

//display
$searchResultHTML .= "<ul class='comment-list'>";
if ($result->num_rows > 0) {
    while ($row_comment = $result->fetch_assoc()) {
        $searchResultHTML .= "<li class='comment'>
            <div class='comment-user'>
                <p>".$row_comment['user_NickName']."</p>
                <p>" . $row_comment['com_Rating'] . "</p>
            </div>
                <p>" . $row_comment['com_Content'] . "</p>
            </li>";
        }
        $searchResultHTML .= "</ul>";
    } 
else {
    $searchResultHTML .= "
    <li class='comment'>
        <p>無任何相符合結果</p>
    </li>";    
}
// 關閉數據庫連接
$conn->close();

// 將搜尋結果保存在 SESSION 中，以便在 shop_info.php 中使用
//session_start();
$_SESSION['searchResultHTML'] = $searchResultHTML;

// 重定向回 shop_info.php
header("Location: ../shop/shop_info.php?shop_id=" . $shopID);
exit;
?>
