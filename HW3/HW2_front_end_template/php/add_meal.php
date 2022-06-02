<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['meal_name']) || !isset($_POST['price']) || !isset($_POST['quantity'])) # 在from加上Enctype="multipart/form-data"就不會有_POST的myFile了
        {
            header("Location: ../nav.php#shop");
            exit();
        }
        
        # 有東西是空的
        if (strlen($_POST['meal_name']) == 0) throw new Exception('NAME ERROR: Please input something.');
        if (strlen($_POST['price']) == 0) throw new Exception('PRICE ERROR: Please input something.');
        if (strlen($_POST['quantity']) == 0) throw new Exception('QUANTITY ERROR: Please input something.');
        if (strlen($_SESSION['sid']) == 0) throw new Exception('SHOP ERROR: You don\'t have a shop.');

        # 取得POST的資料
        $meal_name = $_POST['meal_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $sid = $_SESSION['sid'];

        # 檢查輸入的資料
        # !!!助教說不會測meal_name重複的情況，所以就沒檢查了，但應該要不能重複
        if (!preg_match("/^[0-9]*$/",$price)) # 檢查price格式
            throw new Exception('PRICE ERROR: Only >0 integer allowed');

        if (!preg_match("/^[0-9]*$/",$quantity)) # 檢查quantity格式
            throw new Exception('QUANTITY ERROR: Only >0 integer allowed');

        # 處理圖片 (來自助教給的reference)
        # 開啟圖片檔
        $file = fopen($_FILES["myFile"]["tmp_name"], "rb"); # 在from加上Enctype="multipart/form-data"後才取的到_FILE的myFile
        # 讀入圖片檔資料
        $picture = fread($file, filesize($_FILES["myFile"]["tmp_name"])); 
        # 關閉圖片檔
        fclose($file);
        # 讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
        $picture = base64_encode($picture);
        $picture_type = $_FILES["myFile"]["type"]; #!!! 之後再去改名字

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 
        # SQL
        $stmt=$conn->prepare("insert into `product` (`PID`, `meal_name`, `price`, `picture`, `picture_type`, `quantity`, `SID`) 
                              values (NULL, :meal_name, :price, :picture, :picture_type, :quantity, :sid)");
        $stmt->bindParam(':meal_name', $meal_name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':picture', $picture, PDO::PARAM_STR);  # !!!好像有''沒關係? 之後看顯示才知道
        $stmt->bindParam(':picture_type', $picture_type, PDO::PARAM_STR);
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->execute();

        # 跳出alert顯示新增成功，然後跳轉回商店頁面
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Add Sucess.");
                window.location.replace("../nav.php#shop");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回商店頁面
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../nav.php#shop");
            </script>
        </body>
        </html>
        EOT;
    }
?>