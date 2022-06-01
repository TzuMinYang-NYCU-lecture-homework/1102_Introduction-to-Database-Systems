<?php

    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 取得REQUEST的資料
        $shop_name = strval($_REQUEST['shop_name']);

        # 避免直接輸入網址跳過來
        if (!isset($_REQUEST['shop_name']))
        {
            echo 'FAILED';
            exit();
        }
        
        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $stmt = $conn->prepare("select shop_name from shop where shop_name=:shop_name");
        $stmt->execute(array(':shop_name' => $shop_name));  # 防SQL injection
        
        if ($stmt->rowCount() == 0) echo 'YES';
        else echo 'NO';
    }

    catch(Exception $e)
    {
        echo 'FAILED';
    }
?>