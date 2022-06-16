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
        $stmt = $conn->prepare("select pid from product where product.meal_name=:meal_name and sid=:sid");
        $stmt->bindParam(':meal_name', $meal_name, PDO::PARAM_STR);
        $stmt->bindParam(':sid', $_SESSION['sid'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        $pid = $row['pid'];
        
        $stmt = $conn->prepare("select oid from order_product where pid=:pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        $oid = $row['oid'];

        $stmt = $conn->prepare("select oid from order_product where pid=:pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->execute();
        $_SESSION['result'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($_SESSION['result'] as $single_row)
        {
            $oid=$single_row['oid'];
            $stmt = $conn->prepare('select status from order_ where oid=:oid');
            $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
            $stmt->execute();
            $row=$stmt->fetch();
            if($row['status']=="not finished")
            {
                throw new Exception('ERROR: product cannot be deleted.(order not finished)'); 
            }
        }

        $stmt = $conn->prepare("delete from product where product.meal_name=:meal_name and sid=:sid");
        $stmt->bindParam(':meal_name', $meal_name, PDO::PARAM_STR);
        $stmt->bindParam(':sid', $_SESSION['sid'], PDO::PARAM_STR);
        $stmt->execute();

        # 跳出alert顯示刪除成功，然後跳轉回SHOP
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Delete Sucess.");
                window.location.replace("../nav.php#shop");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回SHOP
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