<?php
require_once '../db.php';
$userID =$_SESSION['nowUser']['user_ID'];
$shopID = isset($_GET['shop_id']) ? $_GET['shop_id'] : '';
$deleteSql = "DELETE FROM comment WHERE shop_ID = '$shopID' AND user_ID='$userID'";
if ($conn->query($deleteSql) === TRUE) {
    header("Location: ../shop/shop_info.php?shop_id=" . $shopID);
    exit;
} else {
    echo "Error deleting comment: ";
}
?>