<?php
require_once '../db.php';

// 接收 AJAX 請求中的數據
$shopID = $_POST['shopID'];
$dessID = $_POST['dessID'];
$newValues = json_decode($_POST['newValues'], true);
$newName=$newValues[0];
$newPrice=$newValues[1];
$newDessTypeName=$newValues[2];

echo $newName;

$searchTN="SELECT desstype_ID FROM desstype WHERE desstype_Name	='$newDessTypeName'";
$searchTNResult=$conn->query($searchTN);
$searchResult=$searchTNResult->fetch_assoc();
$newDessTypeID=$searchResult["desstype_ID"];

$sql = "UPDATE dessert SET dess_Name='$newName', dess_Price='$newPrice',desstype_ID='$newDessTypeID' WHERE shop_ID='$shopID' AND dess_ID='$dessID'";
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