<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['my_order_action']))
        {
            header("Location: ../nav.php");
            exit();
        }

        # 取得POST的資料
        $my_order_action = $_POST['my_order_action'];
        $uid = $_SESSION['UID'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # 取得my_order
        if($my_order_action == "all")
        {
            $stmt = $conn->prepare("select status, create_time, finish_time, SID, price, OID, order_type FROM order_ where UID=:uid");
            $stmt->execute(array(':uid' => $uid));
        }

        else
        {
            $stmt = $conn->prepare("select status, create_time, finish_time, SID, price, OID, order_type FROM order_ where UID=:uid and status=:action");
            $stmt->execute(array(':uid' => $uid, ':action' => $my_order_action));
        }

        $_SESSION['my_order_result'] = $stmt->fetchAll(PDO::FETCH_ASSOC); # 用relation中的column name當作array的index

        # 跳轉回transaction_record
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                window.location.replace("../nav.php#my_order");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回transaction_record
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../nav.php#my_order");
            </script>
        </body>
        </html>
        EOT;
    }
?>