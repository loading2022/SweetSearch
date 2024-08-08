<?php
require_once('../db.php'); // 引入資料庫連線
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/all.css">
</head>

</html>

<body>
    <div class="wrap">
        <div class="navbar">
            <?php
            if (isset($_SESSION['nowUser']) && $_SESSION['nowUser']['user_Role'] == "manager") {
                echo "<h1 class='logo'><a href='../manager_index.php'>搜蒐甜點店</a></h1>";
            } else {
                echo "<h1 class='logo'><a href='../index.php'>搜蒐甜點店</a></h1>";
            }
            ?>
            <ul class="nav">
                <?php
                if (isset($_SESSION['nowUser'])) {
                    // 使用者已登入，顯示收藏和圖鑑
                    if ($_SESSION['nowUser']['user_Role'] == "manager") {
                        echo '<li class="nav-content"><a href="../manager_index.php">管理店家</a></li>';
                    }
                    echo '<li class="nav-content"><a href="../favorite/favorite.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">收藏</a></li>';
                    echo '<li class="nav-content"><a href="../gallery/gallery.php?userid=' . $_SESSION['nowUser']['user_ID'] . '">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="../user/user_info.php?userid=' . $_SESSION['nowUser']['user_ID'] . '"><i class="fa-solid fa-user"></i></a></li>';
                } else {
                    // 使用者未登入
                    echo '<li class="nav-content hide"><a href="#">收藏</a></li>';
                    echo '<li class="nav-content hide"><a href="#">圖鑑</a></li>';
                    echo '<li class="nav-content"><a href="../user/signup.php"><i class="fa-solid fa-user"></i></a></li>';
                }
                ?>

            </ul>
        </div>
        <?php
        if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"]) && $_SESSION['nowUser']['user_Role'] == "manager") {
            // 找出現有資料

            // $shop_ID=$_GET['shop_id'];
            $shop_ID = isset($_GET['shop_id']) ? $_GET['shop_id'] : '';

            $shop_NameQuery = "SELECT shop_Name FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_NameResult = $conn->query($shop_NameQuery);
            $shop_Name = $shop_NameResult->fetch_assoc();
            $shop_Name = isset($shop_Name["shop_Name"]) ? $shop_Name["shop_Name"] : '';

            $shop_PhoneQuery = "SELECT shop_Phone FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_PhoneResult = $conn->query($shop_PhoneQuery);
            $shop_Phone = $shop_PhoneResult->fetch_assoc();
            $shop_Phone = isset($shop_Phone["shop_Phone"]) ? $shop_Phone["shop_Phone"] : '';

            $shop_WebsiteQuery = "SELECT shop_Website FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_WebsiteResult = $conn->query($shop_WebsiteQuery);
            $shop_Website = $shop_WebsiteResult->fetch_assoc();
            $shop_Website = isset($shop_Website["shop_Website"]) ? $shop_Website["shop_Website"] : '';

            $shop_IGQuery = "SELECT shop_IG FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_IGResult = $conn->query($shop_IGQuery);
            $shop_IG = $shop_IGResult->fetch_assoc();
            $shop_IG = isset($shop_IG["shop_IG"])?$shop_IG["shop_IG"]:'';

            $shop_FBQuery = "SELECT shop_FB FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_FBResult = $conn->query($shop_FBQuery);
            $shop_FB = $shop_FBResult->fetch_assoc();
            $shop_FB = isset($shop_FB["shop_FB"])?$shop_FB["shop_FB"]:"";

            $shop_EmailQuery = "SELECT shop_Email FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_EmailResult = $conn->query($shop_EmailQuery);
            $shop_Email = $shop_EmailResult->fetch_assoc();
            $shop_Email = isset($shop_Email["shop_Email"])?$shop_Email["shop_Email"]:'';

            $shop_AddressQuery = "SELECT shop_Address FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_AddressResult = $conn->query($shop_AddressQuery);
            $shop_Address = $shop_AddressResult->fetch_assoc();
            $shop_Address = isset($shop_Address["shop_Address"])?$shop_Address["shop_Address"]:'';

            $shop_ForHereQuery = "SELECT shop_ForHere FROM shop WHERE shop_ID = '$shop_ID'";
            $shop_ForHereResult = $conn->query($shop_ForHereQuery);
            $shop_ForHere = $shop_ForHereResult->fetch_assoc();
            $shop_ForHere = $shop_ForHere["shop_ForHere"];

            $shop_PhotoQuery="SELECT shop_Photo FROM shop WHERE shop_ID='$shop_ID'";
            $shop_PhotoResult=$conn->query($shop_PhotoQuery);
            $shop_Photo=$shop_PhotoResult->fetch_assoc();
            $shop_Photo=isset($shop_Photo["shop_Photo"])?$shop_Photo["shop_Photo"]:'';

        }
        ?>
        <div class="adjust-shop-main">
            <h2>修改店家</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <div class="adjust-shop-main-left">
                    <label for="shop_ID">店家ID(不可更改)</label>
                    <input type="text" id="shop_ID" name="shop_ID" value="<?php echo $shop_ID; ?>" readonly>
                    <label for="newshop_Name">名稱</label>
                    <input type="text" id="newshop_Name" name="newshop_Name" value="<?php echo $shop_Name; ?>" required>
                    <label for="newshop_Phone">電話</label>
                    <input type="text" id="newshop_Phone" name="newshop_Phone" maxlength="10" value="<?php echo $shop_Phone; ?>">
                    <label for="newshop_Website">網站</label>
                    <input type="text" id="newshop_Website" name="newshop_Website" value="<?php echo $shop_Website; ?>">
                    <label for="newshop_IG">IG</label>
                    <input type="text" id="newshop_IG" name="newshop_IG" value="<?php echo $shop_IG; ?>">
                    <label for="newshop_FB">FB</label>
                    <input type="text" id="newshop_FB" name="newshop_FB" value="<?php echo $shop_FB; ?>">
                    <label for="newshop_Email">Email</label>
                    <input type="email" id="newshop_Email" name="newshop_Email" value="<?php echo $shop_Email; ?>">
                    <label for="newshop_Address">地址</label>
                    <input type="text" id="newshop_Address" name="newshop_Address" value="<?php echo $shop_Address; ?>" required>
                    <label for="newshop_ForHere">內用/外帶(預設內用)</label>
                    <div class="forhere">
                        <input type="radio" id="newshop_ForHere_1" name="newshop_ForHere" value="1" <?php if($shop_ForHere=='1'){echo "checked";} ?>><label for="newshop_ForHere_1">內用</label>
                        <input type="radio" id="newshop_ForHere_0" name="newshop_ForHere" value="0"<?php if($shop_ForHere=='0'){echo "checked";} ?>><label for="newshop_ForHere_0">外帶</label>
                    </div>
                    <!-- 照片 -->
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                    <button type="submit" class="user-update" name="submit">送出</button>
                </div>
                <div class="adjust-shop-main-right">
                <div>
                <h3>營業時間</h3>
                    <?php
                    $chineseDays = array("星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日");
                    $englishDays = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
                    for ($i = 0; $i < count($chineseDays); $i++) {
                        /*
                        $shop_OpenTime_Query="SELECT SUBSTRING_INDEX(shop_OpenTime,'-',1) FROM opentime WHERE shop_OpenWeek='$chineseDays[$i]' AND shop_ID='$shop_ID'";
                        $shop_OpenTime_Result = $conn->query($shop_OpenTime_Query);
                        $shop_OpenTime = $shop_OpenTime_Result->fetch_assoc();
                        $shop_OpenTime=$shop_OpenTime['SUBSTRING_INDEX(shop_OpenTime,\'-\',1)'];
                        */
                        $shop_Time_Query="SELECT shop_OpenTime FROM opentime WHERE shop_OpenWeek='$chineseDays[$i]' AND shop_ID='$shop_ID'";
                        $shop_Time_Result = $conn->query($shop_Time_Query);
                        $shop_Time = $shop_Time_Result->fetch_assoc();
                        $shop_Time=$shop_Time['shop_OpenTime'];
                        
                        $opentime="";
                        $closetime="";
                        if($shop_Time!="休息"&&$shop_Time!=""){
                            $shop_OpenTime=explode("-",$shop_Time)[0];
                            $shop_CloseTime=explode("-",$shop_Time)[1];
                            if($shop_OpenTime!="休息"&&$shop_OpenTime!=""){
                                if (str_contains( $shop_OpenTime,"下午")) {
                                    $opentime=explode("下午",$shop_OpenTime);
                                    $hours=explode(":",$opentime[1]);
                                    $openminutes=$hours[1];
                                    $openhours=$hours[0];
                                    
                                    if($openhours!=12){
                                        $openhours = intval($hours[0]) + 12;
                                    }
                                    $opentime = sprintf("%02d:%02d", $openhours, $openminutes);
                                }
                                else if(str_contains($shop_OpenTime,"上午")){
                                    $opentime=explode("上午",$shop_OpenTime);
                                    $hours=explode(":",$opentime[1]);
                                    $openminutes=$hours[1];
                                    $openhours=$hours[0];
                                    $opentime = sprintf("%02d:%02d", $openhours, $openminutes);
                                }
                            }
                            
                            if($shop_CloseTime!="休息"&&$shop_CloseTime!=""){
                                if (str_contains($shop_CloseTime,"下午")) {
                                    $closetime=explode("下午",$shop_CloseTime);
                                    $hours=explode(":",$closetime[1]);
                                    $closeminutes=$hours[1];
                                    if($hours[0]!=12){
                                        $closehours = intval($hours[0]) + 12;
                                    }     
                                    $closetime = sprintf("%02d:%02d", $closehours, $closeminutes);
                                }
                                else if(str_contains($shop_CloseTime,"上午")){
                                    $closetime=explode("上午",$shop_CloseTime);
                                    $hours=explode(":",$closetime[1]);
                                    $closeminutes=$hours[1];
                                    $closehours=$hours[0];
                                    $closetime = sprintf("%02d:%02d", $closehours, $closeminutes);
                                }
                            }
                        }

                    $chineseDays = array("星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日");
                    $englishDays = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
                    echo '<div class="day-create">';
                    echo '<label for="' . strtolower($englishDays[$i]) . '">' . $chineseDays[$i] . '</label>';
                    
                    if($shop_Time!="休息"&&$shop_Time!=""){
                        echo '<label class="switch">';
                        echo '<input class="toggle" id="' . strtolower($englishDays[$i]) . '" type="checkbox" name="'.($englishDays[$i]).'_status" onchange="toggleTimeFields(this)" checked>';
                        echo '<span class="slider"></span>';
                        echo '</label>';
                        echo '<span id="' . strtolower($englishDays[$i]) . '-open-status">今日有營業</span>';
                        echo '<input type="time" name="' . strtolower($englishDays[$i]) . '_open_time" value="'.$opentime.'"> - <input type="time" name="' . strtolower($englishDays[$i]) . '_close_time" value="'.$closetime.'">';
                    }
                    else{
                        echo '<label class="switch">';
                        echo '<input class="toggle" id="' . strtolower($englishDays[$i]) . '" type="checkbox" name="'.($englishDays[$i]).'_status" onchange="toggleTimeFields(this)">';
                        echo '<span class="slider"></span>';
                        echo '</label>';
                        echo '<span id="' . strtolower($englishDays[$i]) . '-open-status">未營業</span>';
                        echo '<input type="time" name="' . strtolower($englishDays[$i]) . '_open_time" disabled="disabled"> - <input type="time" name="' . strtolower($englishDays[$i]) . '_close_time" disabled="disabled">';
                    }
                    echo '</div>';
                     }
                    echo "</div>";
                    
                    ?>
                    <div>
                    <h3>目前店家圖片</h3>
                    <?php
                        echo "<input type='hidden' name='existingPhoto' value='$shop_Photo'>";
                        if (!empty($shop_Photo)){
                            echo "<img src='$shop_Photo' alt='Current Photo'>";
                        }
                        else{
                            echo "<img src='../image/no-image.png' alt='尚無店家圖片'>";
                        }
                    ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <div class="left-footer"><img src='../image/logo-4.png'></div>
        <div class="right-footer">
            <p>Copyright © 2023 搜蒐甜點店 All Rights Reserved</p>
        </div>
    </div>
    <script src="../js/all.js"></script>
</body>

</html>

<?php 
// 检查是否有文件上传
if (isset($_FILES["fileToUpload"])) {
    $shop_ID = isset($_POST['shop_ID']) ? $_POST['shop_ID'] : '';
    $target_dir = "../temp/"; // 上传目标目录
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); // 上传文件的完整路径
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    /*
    // 检查文件是否已存在
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    */
    // 检查文件大小
    // if ($_FILES["fileToUpload"]["size"] > 500000) {
    //     $message="圖片檔案太大";
    //     $uploadOk = 0;
    // }

    // 允许的文件类型
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $shop_PhotoResult->num_rows < 0 ) {
        $message="圖片格式不正確，應為 jpg, png, jpeg 或 gif 檔 ";
        //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    // 如果 $uploadOk 为 0，表示文件上传失败
    if ($uploadOk == 0) {
        //echo "Sorry, your file was not uploaded.";
        $destination="";
    // if everything is ok, try to upload file
    } else {
        $extension  = pathinfo( $_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION ); 
        $baseName=$shop_ID.".".$extension;
        $destination="../upload/{$baseName}";
        //unlink($destination);
        //echo $destination;
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $destination);
    }
}

if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"]) && $_SESSION['nowUser']['user_Role'] == "manager") {
 // 檢查表單是否提交
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得表單提交的資料
    $shop_ID=$_POST["shop_ID"];
    $newshop_Name = $_POST["newshop_Name"];
    $newshop_Phone = $_POST["newshop_Phone"];
    $newshop_Website = $_POST["newshop_Website"];
    $newshop_IG = $_POST["newshop_IG"];
    $newshop_FB = $_POST["newshop_FB"];
    $newshop_Email = $_POST["newshop_Email"];
    $newshop_Address = $_POST["newshop_Address"];
    $newshop_ForHere = isset($_POST["newshop_ForHere"]) ? intval($_POST["newshop_ForHere"]) : NULL;
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["tmp_name"] != ''){
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        else {
            $destination = isset($_POST["existingPhoto"]) ? $_POST["existingPhoto"] : '';
        }

    $updateSql = "UPDATE shop SET shop_Name='$newshop_Name', shop_Phone='$newshop_Phone', shop_Website='$newshop_Website', shop_IG='$newshop_IG', shop_FB='$newshop_FB', shop_Email='$newshop_Email', shop_Address='$newshop_Address', shop_ForHere='$newshop_ForHere', shop_Photo='$destination' WHERE shop_ID='$shop_ID'";
    if ($conn->query($updateSql) !== TRUE) {
        echo "錯誤：" . $conn->error;
    }
    $days = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
    $chineseDays = array("星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日");
    for ($i = 0; $i < count($chineseDays); $i++) {
        $status = isset($_POST[$days[$i] . '_status']) && $_POST[$days[$i] . '_status'] == 'on' ? '營業中' :'休息'  ;
        
        $openTime = isset($_POST[$days[$i] .'_open_time']) && $_POST[$days[$i] .'_open_time'] != '' ? $_POST[$days[$i] .'_open_time'] : '';
        $openTime12  = $openTime != '' ? date("A g:i", strtotime($openTime)) : '休息';
        $openTime12 = str_replace("AM", "上午", $openTime12);
        $openTime12 = str_replace("PM", "下午", $openTime12);
        $closeTime = isset($_POST[$days[$i] . '_close_time']) && $_POST[$days[$i] . '_close_time'] != '' ? $_POST[$days[$i] . '_close_time'] : '';
        $closeTime12 = $closeTime != '' ? date("A g:i", strtotime($closeTime)) : '休息';
        $closeTime12 = str_replace("AM", "上午", $closeTime12);
        $closeTime12 = str_replace("PM", "下午", $closeTime12);


            if ($status == '休息' || $openTime == "" || $closeTime == ""){
                $sql = "UPDATE opentime SET shop_OpenTime='休息' WHERE shop_ID='$shop_ID' AND shop_OpenWeek='$chineseDays[$i]'";
            }
            else{
                $sql = "UPDATE opentime SET shop_OpenTime='$openTime12 - $closeTime12' WHERE shop_ID='$shop_ID'AND shop_OpenWeek='$chineseDays[$i]'";
            }


        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    header("Location: ../shop/shop_info.php?shop_id=$shop_ID&&message=$message");
    exit();
}

ob_end_flush();
}
?>
