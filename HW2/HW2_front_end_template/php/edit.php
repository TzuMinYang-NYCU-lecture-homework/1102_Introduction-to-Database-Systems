<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['latitude']) || !isset($_POST['longitude']))
        {
            header("Location: ..");
            exit();
        }

        # 有東西是空的
        if (empty($_POST['latitude'])) throw new Exception('ACCOUNT ERROR: Please input something.');
        if (empty($_POST['longitude'])) throw new Exception('PASSWORD ERROR: Please input something.');

        # 取得POST的資料
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        if(!($latitude <= 90 && $latitude >= -90)) # 檢查latitude範圍 !!! 不確定南邊是不是負的
        throw new Exception('LATITUDE ERROR: Only -90~90 allowed');
        
        if (!preg_match("/^[0-9]*\.?[0-9]*$/",$longitude)) # 檢查longitude格式
            throw new Exception('LONGTITUDE ERROR: Only float-number allowed');

        $longitude = (float)$longitude;

        if(!($longitude <= 180 && $longitude >= -180)) # 檢查longitude範圍 !!! 不確定西邊是不是負的
            throw new Exception('LONGTITUDE ERROR: Only -180~180 allowed');
        $location = "POINT($longitude $latitude)";
        $acc=$_SESSION['account'];
        $stmt = $conn->prepare("update user set location=ST_GeomFromText(:location,0) where account='$acc'");
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->execute();

        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Edit Sucess.");
                window.location.replace("../nav.php");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e)
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