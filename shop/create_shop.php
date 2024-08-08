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
                        echo '<li class="nav-content"><a href="../dessType/manager_dessType_Index.php">管理甜點種類</a></li>';
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
            // 從資料庫中獲取最後一個 "shop_ID"
            $sql = "SELECT shop_ID FROM shop ORDER BY shop_ID DESC LIMIT 1";
            $result = $conn->query($sql);
            $lastShopID = $result->fetch_assoc();
            // 取得結果
            // $lastShopID = $result->fetchColumn();
            // 將 "s_" 後的數字提取出來，加1，再組合成新的 "shop_ID"
            $lastNumber = (int) substr($lastShopID['shop_ID'], 2,2);
            $newNumber = $lastNumber + 1;
            $newID = 's_' . sprintf('%02d', $newNumber);
        ?>
        <div class="adjust-shop-main">
            <h2>新增店家</h2>
            <form method="post" action="create_shop.php" enctype="multipart/form-data">
                <div class="adjust-shop-main-left">
                    <label for="shop_ID">店家ID(自動配給)</label>
                    <input type="text" id="shop_ID" name="shop_ID" value="<?php echo $newID; ?>" readonly>
                    <label for="shop_Name">名稱</label>
                    <input type="text" id="shop_Name" name="shop_Name" value="" required>
                    <label for="shop_Phone">電話</label>
                    <input type="tel" id="shop_Phone" name="shop_Phone" value="">
                    <label for="shop_Website">網站</label>
                    <input type="url" id="shop_Website" name="shop_Website" value="">
                    <label for="shop_IG">IG</label>
                    <input type="url" id="shop_IG" name="shop_IG" value="">
                    <label for="shop_FB">FB</label>
                    <input type="url" id="shop_FB" name="shop_FB" value="">
                    <label for="shop_Email">Email</label>
                    <input type="email" id="shop_Email" name="shop_Email" value="">
                    <label for="shop_Address">地址</label>
                    <input type="text" id="shop_Address" name="shop_Address" value="" required>
                    <label for="shop_ForHere">內用/外帶(預設內用)</label>
                    <div class="forhere">
                        <input type="radio" id="shop_ForHere_1" name="shop_ForHere" value="1" checked><label for="newshop_ForHere_1">內用</label>
                        <input type="radio" id="shop_ForHere_0" name="shop_ForHere" value="0"><label for="newshop_ForHere_0">外帶</label>
                    </div>
                    <!-- 照片 -->
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
                    <button type="submit" class="user-update">送出</button>
                </div>
                <div class="create-shop-main-right">
                <h3>營業時間</h3>
                <?php
                $chineseDays = array("星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日");
                $englishDays = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
                for ($i = 0; $i < count($chineseDays); $i++) {
                    echo '<div class="day-create">';
                    echo '<label for="' . strtolower($englishDays[$i]) . '">' . $chineseDays[$i] . '</label>';
                    echo '<label class="switch">';
                    echo '<input class="toggle" id="' . strtolower($englishDays[$i]) . '" type="checkbox" name="'.($englishDays[$i]).'_status" onchange="toggleTimeFields(this)">';
                    echo '<span class="slider"></span>';
                    echo '</label>';
                    echo '<span id="' . strtolower($englishDays[$i]) . '-open-status">未營業</span>';
                    echo '<input type="time" name="' . strtolower($englishDays[$i]) . '_open_time" disabled="disabled"> - <input type="time" name="' . strtolower($englishDays[$i]) . '_close_time" disabled="disabled">';
                    echo '</div>';
                }

                ?>
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
    if(isset($_FILES["fileToUpload"])){
        $target_dir = "../temp/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        //echo $_FILES['fileToUpload']['tmp_name'];
        /*
        if (file_exists($target_file)) {
            //echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        */
        // Check file size
        // if ($_FILES["fileToUpload"]["size"] > 500000) {
        //     $message="圖片檔案太大";
        //     $uploadOk = 0;
        // }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            //$message="圖片格式不正確，應為 jpg, png, jpeg 或 gif 檔";
            $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
            $destination="";
        // if everything is ok, try to upload file
        } else {
            $fileName=$newID;
            $extension  = pathinfo( $_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION ); 
            $baseName=$fileName.".".$extension;
            $destination="../upload/{$baseName}";
            //echo $destination;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $destination);
        }
    }
    if (isset($_SESSION["nowUser"]) && !empty($_SESSION["nowUser"]) && $_SESSION['nowUser']['user_Role'] == "manager") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // 取得表單提交的資料

            $shop_ID = $_POST["shop_ID"];
            $shop_Name = $_POST["shop_Name"];
            $shop_Phone = $_POST["shop_Phone"];
            $shop_Website = $_POST["shop_Website"];
            $shop_IG = $_POST["shop_IG"];
            $shop_FB = $_POST["shop_FB"];
            $shop_Email = $_POST["shop_Email"];
            $shop_Address = $_POST["shop_Address"];
            $shop_ForHere = isset($_POST["shop_ForHere"]) ? intval($_POST["shop_ForHere"]) : NULL;
            
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["tmp_name"] != ''){
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
            }
            $ExistSQL = "SELECT shop_Name FROM shop WHERE shop_Name = '$shop_Name' AND shop_Address = '$shop_Address'";
            $ExistSQLResult = $conn->query($ExistSQL);

            if($ExistSQLResult->num_rows > 0){
                echo "<script>alert('店家已經存在了喔!')</script>";
                exit();
            }
            
            // 新增進資料庫內
            $insertSql = "INSERT INTO shop(shop_ID, shop_Name, shop_Phone, shop_Website, shop_IG, shop_FB, shop_Email, shop_Address, shop_ForHere,shop_Photo) VALUES ('$shop_ID', '$shop_Name', '$shop_Phone', '$shop_Website', '$shop_IG', '$shop_FB', '$shop_Email', '$shop_Address', '$shop_ForHere','$destination')";
            if ($conn->query($insertSql) !== TRUE) {
                echo "Error: " . $insertSql . "<br>" . $conn->error;
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


                    if ($status == '休息' || $openTime =="" || $closeTime ==""){
                        $sql = "INSERT INTO opentime (shop_ID, shop_OpenWeek, shop_OpenTime)
                        VALUES ('$shop_ID', '$chineseDays[$i]', '休息')";
                    }
                    else{
                        $sql = "INSERT INTO opentime (shop_ID, shop_OpenWeek, shop_OpenTime)VALUES ('$shop_ID', '$chineseDays[$i]', '$openTime12 - $closeTime12')";
                    }


                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            header("Location: ../shop/shop_info.php?shop_id=$shop_ID&message=$message");
            exit();
        }
    }
    ob_end_flush();
?>