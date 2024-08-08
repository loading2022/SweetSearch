<?php
require_once '../db.php';

// 接收 AJAX 請求中的數據
$ID = $_POST['dessTypeID'];
$newValues = json_decode($_POST['newValues'], true);
$newName=$newValues[0];

// echo $newName;

// $searchTN="SELECT desstype_ID FROM desstype WHERE desstype_Name	='$newDessTypeName'";
// $searchTNResult=$conn->query($searchTN);
// $searchResult=$searchTNResult->fetch_assoc();
// $newDessTypeID=$searchResult["desstype_ID"];

$sql = "UPDATE desstype SET desstype_Name ='$newName' WHERE desstype_ID	='$ID'";
$rsult = $conn->query($sql);
if ($conn->query($sql) == TRUE) {
    echo "succes";
    // header("Location: manager_dessert_index.php?shop_id=$shopID&dess_id=$dessID");
    // exit();
}
else 
{
    echo "錯誤：" . $conn->error;
}

// 關閉資料庫連線
$conn->close();

// 返回確認消息
echo 'Data updated successfully';
?>