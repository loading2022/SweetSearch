<?php
    require_once '../db.php';
    
// 初始化一個變數來保存搜尋結果的 HTML 代碼
$searchTypeResultHTML = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['selectedType'];
    $shopID = $_POST['shop_id'];
    if($type == "全部"){
        $sql = "SELECT * FROM dessert,desstype WHERE dessert.desstype_ID=desstype.desstype_ID AND shop_ID = '$shopID'";
        $result = $conn->query($sql);
    }
    else{
        $sql = "SELECT * FROM dessert,desstype WHERE dessert.desstype_ID=desstype.desstype_ID AND shop_ID = '$shopID' AND desstype_Name='$type'";
        $result = $conn->query($sql);
    }
} 
else {
    $sql = "SELECT * FROM dessert,desstype WHERE dessert.desstype_ID=desstype.desstype_ID AND shop_ID = '$shopID'";
    $result = $conn->query($sql);
}

//display
$searchTypeResultHTML .= "<ul class='dessert-list' id='dessert-list'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $searchTypeResultHTML .= "<li class='dessert'>
            <h3>" . $row['dess_Name'] . "</h3>
            <p>$" . $row['dess_Price'] . "</p>
        </li>";
        }
        $searchTypeResultHTML .= "</ul>";
    } 
else {
    $searchTypeResultHTML .= "
    <li class='dessert'>
        <p>暫無任何品項</p>
    </li>";    
}
// 關閉數據庫連接
$conn->close();

$_SESSION['searchTypeResultHTML'] = $searchTypeResultHTML;

// 重定向回 shop_info.php
header("Location: ../shop/shop_info.php?shop_id=" . $shopID."#dessert-title");
exit;
?>