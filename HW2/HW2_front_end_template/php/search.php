<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['shop']) || !isset($_POST['distance']) || !isset($_POST['lprice']) || !isset($_POST['rprice']) || !isset($_POST['meal']) || !isset($_POST['category']))
        {
            header("Location: ../index.php");
            exit();
        }
        # 取得POST的資料
        $shop_name = $_POST['shop'];
        $distance = $_POST['distance'];
        $lprice = $_POST['lprice'];
        $rprice = $_POST['rprice'];
        $meal = $_POST['meal'];
        $category = $_POST['category'];

        $account = $_SESSION['account'];        
        $_SESSION['distance'] = $_POST['distance']; #!!!        

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # SQL查詢
        $conditions = [];
        $parameters = [];
         
        // conditional statements
        if (strlen($shop_name) != 0)
        {
            $conditions[] = 'name LIKE ?';
            $parameters[] = "%$shop_name%";
        }
         
        if (strlen($distance) != 0)
        {
            $conditions[] = 'category = ?';
            $parameters[] = $_GET['category'];
        }
         
        if (strlen($lprice) != 0 && strlen($rprice) != 0)
        {
            // BETWEEN
            $conditions[] = 'created_at BETWEEN ? AND ?';
            $parameters[] = $_GET['date_start'];
            $parameters[] = $_GET['date_end'];
        }

        if (strlen($meal) != 0)
        {
            $conditions[] = 'category = ?';
            $parameters[] = $_GET['category'];
        }

        if (strlen($category) != 0)
        {
            $conditions[] = 'shop_category = ?';
            $parameters[] = $_GET['category'];
        }
         
        // the main query
        $sql = "SELECT * FROM products";
         
        // 把條件組合成 query 語法
        if ($conditions)
        {
            $sql .= " WHERE ".implode(" AND ", $conditions);
        }
         
        // the usual prepare/execute/fetch routine
        $stmt = $pdo->prepare($sql);
        //丟入參數
        $stmt->execute($parameters);
        $data = $stmt->fetchAll();

        $_SESSION['shop'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Location: ../nav.php");
    }

    
    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回登入頁面
    {
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("$msg");
                window.location.replace("../sign-up.php");
            </script>
        </body>
        </html>
        EOT;
    }
?>