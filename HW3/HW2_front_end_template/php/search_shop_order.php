<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['shop_order_status']))
        {
            if ($_SESSION['cancel'] == NULL && $_SESSION['done'] == NULL)
            {
                header("Location: ../nav.php#shop_order");
                exit();
            }
        }

        # 取得POST的資料
        if (isset($_SESSION['cancel']) && $_SESSION['cancel'] != NULL)
        {
            $status = $_SESSION['cancel'];
            $_SESSION['cancel'] = NULL;
        }
        else if(isset($_SESSION['done']) && $_SESSION['done'] != NULL)
        {
            $status = $_SESSION['done'];
            $_SESSION['done'] = NULL;
        }
        else
        {
            $status = $_POST['shop_order_status'];
        }
        $uid = $_SESSION['UID'];

        $_SESSION['search_type'] = $status;

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # 取得
        $stmt = $conn->prepare("select identity from user where UID=:UID");
        $stmt->execute(array(':UID' => $uid));  # 防SQL injection

        $row = $stmt->fetch();
        if($row['identity'] == 'manager') # 檢查是否是店長
        {
            if($status == "all")
            {
                $stmt = $conn->prepare("select OID, shop.SID, status, create_time, finish_time, shop_name, order_type, price FROM order_, shop where shop.UID=:uid and shop.SID=order_.SID");
                $stmt->execute(array(':uid' => $uid));
            }
            else
            {
                $stmt = $conn->prepare("select OID, shop.SID, status, create_time, finish_time, shop_name, order_type, price FROM order_, shop where shop.UID=:uid and shop.SID=order_.SID and status=:status");
                $stmt->execute(array(':uid' => $uid, ':status' => $status));
            }
        }

        $_SESSION['shop_order_result'] = $stmt->fetchAll(PDO::FETCH_ASSOC); # 用relation中的column name當作array的index

        for($i = 0; $i < count($_SESSION['shop_order_result']); $i++)
        {
            if ($_SESSION['shop_order_result'][$i]['status'] == "not finished")
            $_SESSION['shop_order_result'][$i]['finish_time'] = NULL;
        }

        # 跳轉回
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                window.location.replace("../nav.php#shop_order");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回
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