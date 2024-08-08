<?php
    require_once '../db.php';
    //session_start();
?>
<?php
$keyword = $_POST['favorite-search-bar'];
$userID=$_SESSION["nowUser"]['user_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($keyword !== null && $keyword !== "") {
        $sql = "SELECT * FROM shop WHERE shop_ID IN (SELECT shop_ID FROM favorite WHERE user_ID='$userID') AND (shop_Name LIKE '%$keyword%')";
    } else {
        $sql = "SELECT * FROM shop WHERE shop_ID IN (SELECT shop_ID FROM favorite WHERE user_ID='$userID')";
    }
}
else {
    $sql="SELECT * FROM shop WHERE shop_ID IN(SELECT shop_ID FROM favorite WHERE user_ID='$userID')";
}
$result = $conn->query($sql);
$numRows = mysqli_num_rows($result);
//display
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favoriteResult .= "<li class='favorite-shop-detail'>
        <img src='".$row['shop_Photo']."' alt='".$row['shop_Name']."'>
        <div class='favorite-shop-content'>
            <h2><a href='../shop/shop_info.php?shop_id=".$row["shop_ID"]."'>".$row["shop_Name"]."</a></h2>
            <p><i class='fas fa-map-marker-alt' style='color: #ea0b43;margin-right:10px;'></i>".$row["shop_Address"]."</p>
            <p><i class='fa-solid fa-phone' style='color: #21e448;margin-right:10px;'></i>".$row["shop_Phone"]."</p>
            <div class='heart'>
                <i class='fa-solid fa-heart' style='color: #f10937;'></i>&nbsp;已收藏
            </div>
        </div>
    </li>";
        }
    } 
else {
    $favoriteResult .= "<li class='favorite-shop-detail'>
    <div class='favorite-shop-content'>
        <p>無任何相符合結果</p>
    </div>
</li>";    
}
$favoriteTitle.="<p>$numRows 間店</p>";
$conn->close();

$_SESSION['favorite-result'] = $favoriteResult;
$_SESSION['favorite-title'] = $favoriteTitle;
header("Location: favorite.php");
exit;
?>
