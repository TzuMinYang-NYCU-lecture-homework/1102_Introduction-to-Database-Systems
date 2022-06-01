<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['price']) || !isset($_POST['quantity']) || !isset($_POST['meal_name']))
        {
            header("Location: ../nav.php");
            exit();
        }

        # 有東西是空的
        if (strlen($_POST['price']) == 0) throw new Exception('PRICE ERROR: Please input something.');
        if (strlen($_POST['quantity']) == 0) throw new Exception('QUANTITY ERROR: Please input something.');
        # meal_name不會是空的，因為是我們自己設定的

        # 取得POST的資料
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $meal_name = $_POST['meal_name']; # 注意html那邊input如果disabled的話就不會傳值

        # 檢查輸入的資料
        if (!preg_match("/^[0-9]*$/",$price)) # 檢查price格式
            throw new Exception('PRICE ERROR: Only >=0 integer allowed');

        if (!preg_match("/^[0-9]*$/",$quantity)) # 檢查quantity格式
            throw new Exception('QUANTITY ERROR: Only >=0 integer allowed');

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL
        $stmt = $conn->prepare("update product set price=:price, quantity=:quantity where meal_name=:meal_name");
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindParam(':meal_name', $meal_name, PDO::PARAM_STR);
        $stmt->execute();

        # 跳出alert顯示更新成功，然後跳轉回主頁
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Edit Sucess.");
                window.location.replace("../nav.php");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回主頁
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../nav.php");
            </script>
        </body>
        </html>
        EOT;
    }
?>