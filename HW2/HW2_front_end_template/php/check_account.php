<?php

    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 取得REQUEST的資料
        $account = $_REQUEST['account'];

        if (!isset($_REQUEST['account']))
        {
            echo 'FAILED';
            exit();
        }
        
        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $stmt = $conn->prepare("select account from user where account=:account");
        $stmt->execute(array(':account' => $account));  # 防SQL injection
        
        if ($stmt->rowCount() == 0) echo 'YES';
        else echo 'NO';
    }

    catch(Exception $e)
    {
        echo 'FAILED';
    }
?>