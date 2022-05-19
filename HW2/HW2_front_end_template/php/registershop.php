<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {   
        # 有東西是空的
        if (empty($_POST['name'])) throw new Exception('NAME ERROR: Please input something.');
        if (empty($_POST['category'])) throw new Exception('RE-PASSWORD ERROR: Please input something.');
        if (empty($_POST['latitude'])) throw new Exception('LATITUDE ERROR: Please input something.');
        if (empty($_POST['longitude'])) throw new Exception('LONGGITUDE ERROR: Please input something.');

        # 取得POST的資料
        $name = $_POST['name'];
        $category = $_POST['category'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $phonenumber=$_SESSION['phonenumber'];
        $UID=$_SESSION['UID'];
        $id=$_SESSION['identity'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $stmt = $conn->prepare("select shop_name from shop where shop_name=:name");
        $stmt->execute(array(':name' => $name));  # 防SQL injection

        $stmt1 = $conn->prepare("select identity from user where UID=:UID");
        $stmt1->execute(array(':UID' => $UID));  # 防SQL injection

        # 檢查輸入的資料
        $row = $stmt1->fetch();
        if($row['identity']=='shop manager')
        {
            throw new Exception('REGISTER ERROR: You are already the shop manager.');
        }
        if($stmt->rowCount() == 1) # 帳號已被註冊
            throw new Exception('ACCOUNT ERROR: The name has been used.');
            
        if (!preg_match("/^[0-9]*\.?[0-9]*$/",$latitude)) # 檢查latitude格式
            throw new Exception('LATITUDE ERROR: Only float-number allowed');
        
        $latitude = (float)$latitude;

        if(!($latitude <= 90 && $latitude >= -90)) # 檢查latitude範圍 !!! 不確定南邊是不是負的
            throw new Exception('LATITUDE ERROR: Only -90~90 allowed');
            
        if (!preg_match("/^[0-9]*\.?[0-9]*$/",$longitude)) # 檢查longitude格式
            throw new Exception('LONGTITUDE ERROR: Only float-number allowed');

        $longitude = (float)$longitude;

        if(!($longitude <= 180 && $longitude >= -180)) # 檢查longitude範圍 !!! 不確定西邊是不是負的
            throw new Exception('LONGTITUDE ERROR: Only -180~180 allowed');

        # 準備data
        $location = "POINT($longitude $latitude)"; # 因為bindParam的第二個參數一定要放一個變數
        # SQL
        $stmt=$conn->prepare("insert into shop (SID, shop_name, location, phonenumber, shop_category, UID) 
                              values (NULL, :name, ST_GeomFromText(:location,0), :phonenumber, :category, :UID)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);   # 因為bind後在sql指令中前後都會加上''，而ST_GeomFromText前後有''會出錯，而POINT要用''，所以填POINT的部分就好
        $stmt->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':UID', $UID, PDO::PARAM_STR);
        $stmt->execute();

        $stmt = $conn->prepare("update user set identity='shop manager' where UID=:UID");
        $stmt->bindParam(':UID', $UID, PDO::PARAM_STR);
        $stmt->execute();
        # 跳通知並跳轉
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Register Sucess.");
                window.location.replace("../nav.php#menu1");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e)
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../nav.php#menu1");
            </script>
        </body>
        </html>
        EOT;
    }
?>