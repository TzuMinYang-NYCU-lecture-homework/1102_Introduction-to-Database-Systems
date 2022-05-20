<?php
    session_start();
    $_SESSION['Authenticated'] = false;
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['account']) || !isset($_POST['password']))
        {
            header("Location: ..");
            exit();
        }

        # 有東西是空的
        if (strlen($_POST['account']) == 0) throw new Exception('ACCOUNT ERROR: Please input something.');
        if (strlen($_POST['password']) == 0) throw new Exception('PASSWORD ERROR: Please input something.');

        # 取得POST的資料
        $account = $_POST['account'];
        $password = $_POST['password'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $stmt = $conn->prepare("select account, password, salt from user where binary account=:account");
        $stmt->execute(array(':account' => $account));  # 防SQL injection

        # 確認查詢結果
        if ($stmt->rowCount() == 1)
        {
            $row = $stmt->fetch();
            if ($row['password'] == hash('sha256', $row['salt'].$password)) # hash('sha256', $row['salt'].$password)
            {
                $_SESSION['Authenticated'] = true;
                $_SESSION['account'] = $row[0];
                header("Location: ../nav.php"); # redirection
                exit();
            }
            else
                throw new Exception('Login failed.');
        }
        else
        {
            throw new Exception('Login failed.');
        }
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回登入頁面
    {
        $msg = $e->getMessage();
        # 記得要清除session !!!好像不清也不會怎樣?
        session_unset();
        session_destroy();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("..");
            </script>
        </body>
        </html>
        EOT;
    }
?>