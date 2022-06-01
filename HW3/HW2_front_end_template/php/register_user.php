<?php
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        
        if (!isset($_POST['user_name']) || !isset($_POST['phonenumber']) || !isset($_POST['account']) || !isset($_POST['password']) || !isset($_POST['re_password']) || !isset($_POST['latitude']) || !isset($_POST['longitude']))
        {
            header("Location: ../sign-up.php");
            exit();
        }
        
        # 有東西是空的
        if (strlen($_POST['user_name']) == 0) throw new Exception('NAME ERROR: Please input something.');
        if (strlen($_POST['phonenumber']) == 0) throw new Exception('PHONENUMBER ERROR: Please input something.');
        if (strlen($_POST['account']) == 0) throw new Exception('ACCOUNT ERROR: Please input something.');
        if (strlen($_POST['password']) == 0) throw new Exception('PASSWORD ERROR: Please input something.');
        if (strlen($_POST['re_password']) == 0) throw new Exception('RE-PASSWORD ERROR: Please input something.');
        if (strlen($_POST['latitude']) == 0) throw new Exception('LATITUDE ERROR: Please input something.');
        if (strlen($_POST['longitude']) == 0) throw new Exception('LONGGITUDE ERROR: Please input something.');

        # 取得POST的資料
        $user_name = $_POST['user_name'];
        $phonenumber = $_POST['phonenumber'];
        $account = $_POST['account'];
        $password = $_POST['password'];
        $re_password = $_POST['re_password'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $stmt = $conn->prepare("select account, password from user where account=:account");
        $stmt->execute(array(':account' => $account));  # 防SQL injection

        # 檢查輸入的資料
        if($stmt->rowCount() == 1) # 帳號已被註冊
            throw new Exception('ACCOUNT ERROR: The account has been used.');

        if (!preg_match("/^[a-zA-Z0-9]*$/",$account)) # 檢查account格式
            throw new Exception('ACCOUNT ERROR: Only letters and numbers allowed');

        if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) # 檢查password格式
            throw new Exception('PASSWORD ERROR: Only letters and numbers allowed');

        if (!preg_match("/^[a-zA-Z]*$/",$user_name)) # 檢查user_name格式
            throw new Exception('NAME ERROR: Only letters allowed');

        if (!preg_match("/^[0-9]*$/",$phonenumber) || strlen($phonenumber) != 10) # 檢查phonenumber格式
            throw new Exception('PHONENUMBER ERROR: Only 10-digit-number allowed');
            
        if (!preg_match("/^-?[0-9]*\.?[0-9]*$/",$latitude)) # 檢查latitude格式
            throw new Exception('LATITUDE ERROR: Only float-number allowed');
        
        $latitude = (float)$latitude;

        if(!($latitude <= 90 && $latitude >= -90)) # 檢查latitude範圍 !!! 不確定南邊是不是負的
            throw new Exception('LATITUDE ERROR: Only -90~90 allowed');
            
        if (!preg_match("/^-?[0-9]*\.?[0-9]*$/",$longitude)) # 檢查longitude格式
            throw new Exception('LONGTITUDE ERROR: Only float-number allowed');

        $longitude = (float)$longitude;

        if(!($longitude <= 180 && $longitude >= -180)) # 檢查longitude範圍 !!! 不確定西邊是不是負的
            throw new Exception('LONGTITUDE ERROR: Only -180~180 allowed');

        if($password != $re_password) # 檢查兩次密碼一不一樣
            throw new Exception('PASSWORD ERROR: Password and re-password are not the same.');

        # 準備data
        $salt = strval(rand(1000,9999));
        $password = hash('sha256', $salt.$password);

        $money = 0;
        $identity = "normal";
        $location = "POINT($longitude $latitude)"; # 因為bindParam的第二個參數一定要放一個變數
      
        # SQL
        # UID用A_I(auto increment)，所以可以給NULL，會自己幫忙填
        $stmt=$conn->prepare("insert into user (UID, account, password, user_name, identity, user_location, phonenumber, money, salt) 
                              values (NULL, :account, :password, :user_name, :identity, 
                              ST_GeomFromText(:location,0), :phonenumber, :money, :salt)");
        $stmt->bindParam(':account', $account, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':identity', $identity, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);   # 因為bind後在sql指令中前後都會加上''，而ST_GeomFromText前後有''會出錯，而POINT要用''，所以填POINT的部分就好
        /*
        原本的寫法，會因為''而產生錯誤
        $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);
        */
        $stmt->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR);
        $stmt->bindParam(':money', $money, PDO::PARAM_STR);
        $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
        $stmt->execute();

        /*
        原本的寫法，改一下之後應該也是可以，但上面可以之後就懶得試了
        $stmt->execute(array(':account' => $account, ':password' => $password, ':user_name' => $user_name, ':identity' => $identity, 
                             ':latitude' => $latitude, ':longitude' => $longitude, ':phonenumber' => $phonenumber, ':money' => $money, ':salt' => $salt));
        */

        # 跳出alert顯示註冊成功，然後跳轉回登入頁面
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Register Sucess.");
                window.location.replace("..");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回登入頁面
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../sign-up.php");
            </script>
        </body>
        </html>
        EOT;
    }
?>