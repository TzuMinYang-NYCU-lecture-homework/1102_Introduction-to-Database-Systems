<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['sid']) || !isset($_POST['meal_count']) || !isset($_POST['delivery_fee']))
        {
            header("Location: ../nav.php");
            exit();
        }
        
        # 取得POST的資料
        $sid = $_POST['sid'];
        $meal_count = $_POST['meal_count'];
        $delivery_fee = $_POST['delivery_fee'];
        $uid = $_SESSION['UID'];
        $distance = $_SESSION['distance'];
        $order_type = $_SESSION['order_type'];

        # 取user點餐時的所有meal name, quantity
        $input_meal_name_array = array();
        $input_quantity_array = array();
        for ($i = 0; $i < $meal_count; $i++)
        {
            $input_meal_number = $sid."_".$i;
            $input_meal_name_array[] = $_POST[$input_meal_number."meal_name"];
            $input_quantity_array[] =  $_POST[$input_meal_number."quantity"];
        }

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 
        # SQL
        $stmt = $conn->prepare("select meal_name, quantity, price, PID FROM product where sid=:sid");
        $stmt->execute(array(':sid' => $sid));  # 防SQL injection

        # 取出所有shop的meal name, quantity, price 這樣做是因為這樣比較好檢查餐點存不存在，且訂單中的餐點順序和店家的餐點順序不一定一樣
        $shop_meal_name_array = array();
        $shop_quantity_array = array();
        $shop_price_array = array();
        $shop_PID_array = array();
        while($row = $stmt->fetch())
        {
            $shop_meal_name_array[] = $row["meal_name"];
            $shop_quantity_array[] = $row["quantity"];
            $shop_price_array[] = $row["price"];
            $shop_PID_array[] = $row["PID"];
        }

        # 取得user餘額
        $stmt = $conn->prepare("select user_name, money FROM user where uid=:uid");
        $stmt->execute(array(':uid' => $uid));  # 防SQL injection
        $row = $stmt->fetch();
        $user_money = $row['money'];
        $user_name = $row['user_name']; # 後面shop manager的交易紀錄的trader要用

        # 檢查錯誤 & 計算餐點金額，產生訂單時要重算一次金額，避免店家有改價格
        $quantity_error_msg = "";
        $subtotal = 0;
        $meal_in_user_map_to_meal_in_shop = array();
        for ($i = 0; $i < $meal_count; $i++)
        {
            if(!in_array($input_meal_name_array[$i], $shop_meal_name_array)) # 有餐點不存在
                throw new Exception('MEAL ERROR: There are meals that do not exist.');
            $meal_in_user_map_to_meal_in_shop[] = array_search($input_meal_name_array[$i], $shop_meal_name_array); # 注意因為false會被當作0，所以如果find到的index是0的話可能會被當false，所以才要先用inarray檢查
            # 現在$input_XXX_array[$i] 對應到 $XXX_array[meal_in_user_map_to_meal_in_shop[$i]]，一個是user看到or輸入的資料，一個是目前資料庫中的資料
                
            if(intval($input_quantity_array[$i]) > intval($shop_quantity_array[$meal_in_user_map_to_meal_in_shop[$i]])) # 訂單數量 > 店家庫存
                $quantity_error_msg = $quantity_error_msg.$input_meal_name_array[$i].'\n'; # 紀錄有哪些商品的訂單數量>庫存，注意\n要用''，用""的話下面輸出時會變成在html換行，會造成alert無效

            $subtotal += $shop_price_array[$meal_in_user_map_to_meal_in_shop[$i]] * $input_quantity_array[$i]; # += 資料庫中的金額 * user要點的數量
        }

        if($quantity_error_msg != "")  # 利出all訂單數量 > 店家庫存的餐點
            throw new Exception("QUANTITY ERROR: These meals' quantity are more than shop's quantity.".'\n'.$quantity_error_msg); # 注意\n要用''，用""的話下面輸出時會變成在html換行，會造成alert無效

        $total_price = $subtotal + $delivery_fee;
        if(intval($total_price) > intval($user_money))  # 訂單金額 > 餘額
            throw new Exception("MONEY ERROR: You don\'t have enough money.");

        # 記得用transaction進行取OID的動作，不然有可能取完OID後另一邊先建立訂單，結果這邊建立的訂單的OID和取到的OID不同。transaction要用try catch包住
        try
        {
            # 開始transaction
            $conn->beginTransaction();

            # 取得等等要建立的訂單的OID
            $stmt=$conn->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'order_'");
            $stmt->execute();
            $row = $stmt->fetch();
            $OID = $row['AUTO_INCREMENT'];

            # 建立訂單
            # time的type用timestamp所以可以給NULL，會自己幫忙填
            # finish_time先讓他照填，之後再更新就好
            $stmt=$conn->prepare("insert into order_ (OID, status, create_time, finish_time, distance, price, order_type, UID, SID) 
                                VALUES (NULL, 'not finished', current_timestamp(), current_timestamp(), :distance, :price, :order_type, :UID, :SID)"); 
            $stmt->execute(array(':distance' => $distance, ':price' => $total_price, ':order_type' => $order_type, ':UID' => $uid, ':SID' => $sid)); # 注意price要用資料庫算的

            # 結束transaction
            $conn->commit();
        }

        catch(Exception $e) # 若transaction有問題就roll back
        {
            $conn->rollBack(); # roll back transaction
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

        # 建立訂單內有哪些商品
        # 現在所有餐點都有在店家內了，因為剛才有檢查過
        for ($i = 0; $i < $meal_count; $i++)
        {
            $stmt=$conn->prepare("insert into order_product (OID, PID, product_quantity, product_price) VALUES (:OID, :PID, :quantity, :price)"); 
            $stmt->execute(array('OID' => $OID, 'PID' => $shop_PID_array[$meal_in_user_map_to_meal_in_shop[$i]], ':quantity' => $input_quantity_array[$i], ':price' => $shop_price_array[$meal_in_user_map_to_meal_in_shop[$i]]));
        }

        # 取得店家name, 店長uid
        $stmt = $conn->prepare("select shop_name, UID FROM shop where sid=:sid");
        $stmt->execute(array(':sid' => $sid));  # 防SQL injection
        $row = $stmt->fetch();
        $shop_name = $row['shop_name'];
        $shop_manager_uid = $row['UID'];

        # 新增user的交易紀錄
        $action = "payment";
        $total_price *= -1;
        # foreign key可以是NULL，但要記得去資料庫調說可以NULL
        $stmt=$conn->prepare("insert into transaction_record (TID, action, time, trader, money_change, UID) values (NULL, :action, current_timestamp(), :trader, :price, :UID)"); 
        $stmt->execute(array(':action' => $action, ':trader' => $shop_name, ':price' => $total_price, ':UID' => $uid));

        # 前面有取過user的餘額了
        # 更新user的餘額
        $user_new_money = $user_money + $total_price; # 現在$total_price是負的
        $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
        $stmt->execute(array(':new_money' => $user_new_money, ':UID' => $uid));

        # 新增shop_mangager的交易紀錄
        $action = "receive";
        $total_price *= -1;
        # foreign key可以是NULL，但要記得去資料庫調說可以NULL
        $stmt=$conn->prepare("insert into transaction_record (TID, action, time, trader, money_change, UID) values (NULL, :action, current_timestamp(), :trader, :price, :UID)"); 
        $stmt->execute(array(':action' => $action, ':trader' => $user_name, ':price' => $total_price, ':UID' => $shop_manager_uid));

        # 取得shop_mangager的餘額
        $stmt = $conn->prepare("select money FROM user where uid=:uid");
        $stmt->execute(array(':uid' => $shop_manager_uid));  # 防SQL injection
        $row = $stmt->fetch();
        $shop_manager_money = $row['money'];

        # 更新shop_mangager的餘額
        $shop_manager_new_money = $shop_manager_money + $total_price; # 現在$total_price是正的
        $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
        $stmt->execute(array(':new_money' => $shop_manager_new_money, ':UID' => $shop_manager_uid));

        # 跳出alert顯示新增成功，然後跳轉回HOME
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Order Sucess.");
                window.location.replace("../nav.php");
            </script>
        </body>
        </html>
        EOT;
    }

    catch(Exception $e) # 跳出alert顯示錯誤訊息，然後跳轉回HOME
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