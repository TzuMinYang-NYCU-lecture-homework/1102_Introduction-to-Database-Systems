<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['meal_name']))
        {
            header("Location: ../nav.php");
            exit();
        }

        # meal_name不會是空的，因為是我們自己設定的

        # 取得POST的資料
        $meal_name = $_POST['meal_name']; # 注意html那邊input如果disabled的話就不會傳值

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL
        $stmt = $conn->prepare("delete from product where product.meal_name=:meal_name");
        $stmt->bindParam(':meal_name', $meal_name, PDO::PARAM_STR);
        $stmt->execute();

        # 跳出alert顯示刪除成功，然後跳轉回主頁
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Delete Sucess.");
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