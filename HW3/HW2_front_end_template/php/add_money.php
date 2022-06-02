<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['value']))
        {
            header("Location: ../nav.php");
            exit();
        }

        # 有東西是空的
        #!!! 應該可以不用，input會幫忙檢查
        if (strlen($_POST['value']) == 0) throw new Exception('VALUE ERROR: Please input something.'); 

        # 取得POST的資料
        $value = intval($_POST['value']);

        # 檢查輸入的資料
        #!!! 應該可以不用，input會幫忙檢查
        if (!preg_match("/^[1-9][0-9]*$/",$value))  
            throw new Exception('VALUE ERROR: Only >0 integer allowed');

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # 取得原本的金額
        $stmt = $conn->prepare("select UID, money FROM user where account=:account"); # 取得原本的金額
        $stmt->execute(array(':account' => $_SESSION['account']));
        $row = $stmt->fetch();

        $new_money = intval($row["money"]) + $value;
        $uid = $row["UID"];
        # 更新money
        $stmt = $conn->prepare("update user set money=:new_money where account=:account");
        $stmt->execute(array(':new_money' => $new_money, ':account' => $_SESSION['account']));
        
        # 新增交易紀錄
        $action = "recharge";
        # foreign key可以是NULL，但要記得去資料庫調說可以NULL
        # time的type用timestamp所以可以給NULL，會自己幫忙填
        $stmt=$conn->prepare("insert into transaction_record (TID, action, time, trader, money_change, UID) values (NULL, :action, NULL, :trader, :value, :UID)"); 
        $stmt->execute(array(':action' => $action, ':trader' => $_SESSION['user_name'], 'value' => $value, 'UID' => $uid));

        # 跳出alert顯示更新成功，然後跳轉回主頁
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Recharge Sucess.");
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