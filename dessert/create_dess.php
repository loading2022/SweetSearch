<?php
require_once '../db.php';

// 接收 AJAX 請求中的數據
$shopID = $_POST['shop_id'];
$dessID = $_POST['dess_id'];
$dessName = $_POST['dess_name'];
$dessPrice = $_POST['dess_price'];
$dessType = $_POST['dess_type'];
//type

// echo $dessType;

$alldessID = "SELECT dess_ID FROM dessert WHERE shop_ID='$shopID' AND dess_ID='$dessID'";
$alldessIDresult = $conn->query("$alldessID");
$alldessIDs=$alldessIDresult -> fetch_assoc();
// echo $onealldessID;
// echo $dessID;

if ($alldessIDs!=null) {
    $searchTN = "SELECT desstype_ID FROM desstype WHERE desstype_Name	='$dessType'";
    $searchTNResult = $conn->query($searchTN);
    $searchResult = $searchTNResult->fetch_assoc();
    $newDessTypeID = $searchResult["desstype_ID"];

    $sql = "UPDATE dessert SET dess_Name='$dessName', dess_Price='$dessPrice',desstype_ID='$newDessTypeID' WHERE shop_ID='$shopID' AND dess_ID='$dessID'";
    $rsult = $conn->query($sql);
    echo "update";
} else {
    $searchTN = "SELECT desstype_ID FROM desstype WHERE desstype_Name	='$dessType'";
    $searchTNResult = $conn->query($searchTN);
    $searchResult = $searchTNResult->fetch_assoc();
    $newDessTypeID = $searchResult["desstype_ID"];

    $insertSql = "INSERT INTO dessert(shop_ID, dess_ID, dess_Name, dess_Price, desstype_ID) VALUES ('$shopID', '$dessID', '$dessName', '$dessPrice', '$newDessTypeID')";
    if ($conn->query($insertSql) === TRUE) {
            echo "succes create";
            // header("Location: manager_index.php");
            // exit();
        } else {
            echo "錯誤：" . $conn->error;
        }
}


// $searchTN = "SELECT desstype_ID FROM desstype WHERE desstype_Name	='$dessType'";
// $searchTNResult = $conn->query($searchTN);
// $searchResult = $searchTNResult->fetch_assoc();
// $newDessTypeID = $searchResult["desstype_ID"];

// $insertSql = "INSERT INTO dessert(shop_ID, dess_ID, dess_Name, dess_Price, desstype_ID) VALUES ('$shopID', '$dessID', '$dessName', '$dessPrice', '$newDessTypeID')";

// if ($conn->query($insertSql) === TRUE) {
//     echo "succes";
//     // header("Location: manager_index.php");
//     // exit();
// } else {
//     echo "錯誤：" . $conn->error;
// }

// 關閉資料庫連線
$conn->close();

// 返回確認消息
// echo 'Data updated successfully';
?>