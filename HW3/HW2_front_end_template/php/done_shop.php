<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['done_order_id']))
        {
            header("Location: ../nav.php");
            exit();
        }
        
        $OID = $_POST['done_order_id'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception

        #取得 order
        $stmt = $conn->prepare("select status, SID, UID, price FROM order_ where OID=:oid");
        $stmt->execute(array(':oid' => $OID));

        $order_detail = $stmt->fetch();

        if ($order_detail['status'] != "not finished")
            throw new Exception('ERROR: Order cannot be done.'); 
            
        # 取得店家name, 店長uid
        $stmt = $conn->prepare("select shop_name, UID FROM shop where sid=:sid");
        $stmt->execute(array(':sid' => $order_detail['SID']));  # 防SQL injection
        $row = $stmt->fetch();
        $shop_name = $row['shop_name'];
        $shop_manager_uid = $row['UID'];

        # 取得user name
        $stmt = $conn->prepare("select user_name FROM user where uid=:uid");
        $stmt->execute(array(':uid' => $order_detail['UID']));  # 防SQL injection
        $row = $stmt->fetch();
        $user_name = $row['user_name'];

        #更新order status跟finish time
        $stmt = $conn->prepare("update order_ set status='finished', finish_time=current_timestamp() where OID=:OID");
        $stmt->execute(array(':OID' => $OID));
    

        $_SESSION['done'] = $_SESSION['search_type'];
            
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Done Sucess.");
                window.location.replace("../php/search_shop_order.php");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回my_order
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../nav.php#shop_order");
            </script>
        </body>
        </html>
        EOT;
    }
?>