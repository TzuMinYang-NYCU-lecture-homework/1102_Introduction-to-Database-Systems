<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['cancel_order_id']) && !isset($_POST['checkbox']))
        {
            header("Location: ../nav.php");
            exit();
        }
        
        if (isset($_POST['checkbox']))
        {
            for ($i = 0; $i < count($_POST['checkbox']); $i++)
            {
                $OID = $_POST['checkbox'][$i];
                $uid = $_SESSION['UID'];

                # create PDO
                $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception

                #取得 order
                $stmt = $conn->prepare("select status, SID, price FROM order_ where OID=:oid");
                $stmt->execute(array(':oid' => $OID));

                $order_detail = $stmt->fetch();

                if ($order_detail['status'] != "not finished")
                    throw new Exception('ERROR: Order cannot be canceled.'); 
                
                # 取得店家name, 店長uid
                $stmt = $conn->prepare("select shop_name, UID FROM shop where sid=:sid");
                $stmt->execute(array(':sid' => $order_detail['SID']));  # 防SQL injection
                $row = $stmt->fetch();
                $shop_name = $row['shop_name'];
                $shop_manager_uid = $row['UID'];

                # 取得user name
                $stmt = $conn->prepare("select user_name FROM user where uid=:uid");
                $stmt->execute(array(':uid' => $_SESSION['UID']));  # 防SQL injection
                $row = $stmt->fetch();
                $user_name = $row['user_name'];

                # 新增user的交易紀錄
                $action = "receive";
                $total_price = $order_detail['price'];
                # foreign key可以是NULL，但要記得去資料庫調說可以NULL
                $stmt=$conn->prepare("insert into transaction_record (TID, action, time, trader, money_change, UID) values (NULL, :action, current_timestamp(), :trader, :price, :UID)"); 
                $stmt->execute(array(':action' => $action, ':trader' => $shop_name, ':price' => $total_price, ':UID' => $uid));

                # 取得user的餘額
                $stmt = $conn->prepare("select money FROM user where uid=:uid");
                $stmt->execute(array(':uid' => $uid));  # 防SQL injection
                $row = $stmt->fetch();
                $user_money = $row['money'];

                # 更新user的餘額
                $user_new_money = $user_money + $total_price; # 現在$total_price是正的
                $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
                $stmt->execute(array(':new_money' => $user_new_money, ':UID' => $uid));

                # 新增shop_mangager的交易紀錄
                $action = "payment";
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
                $shop_manager_new_money = $shop_manager_money + $total_price; # 現在$total_price是負的
                $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
                $stmt->execute(array(':new_money' => $shop_manager_new_money, ':UID' => $shop_manager_uid));

                #商品數量還原
                # SQL查詢
                $stmt = $conn->prepare("select PID, product_quantity FROM order_product where OID=:oid");
                $stmt->execute(array(':oid' => $OID));  # 防SQL injection
                
                while($row = $stmt->fetch())
                {
                $stmt2 = $conn->prepare("select quantity FROM product where PID=:pid");
                $stmt2->execute(array(':pid' => $row['PID']));  # 防SQL injection

                $product = $stmt2->fetch();
                
                $quantity = $row["product_quantity"] + $product["quantity"];

                $stmt2 = $conn->prepare("update product set quantity=:quantity where PID=:pid");
                $stmt2->execute(array(':quantity' => $quantity, ':pid' => $row['PID']));
                }


                #更新order status跟finish time
                $stmt = $conn->prepare("update order_ set status='cancel', finish_time=current_timestamp() where OID=:OID");
                $stmt->execute(array(':OID' => $OID));
            }
        }

        else 
        {
            $OID = $_POST['cancel_order_id'];

            # create PDO
            $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception

            #取得 order
            $stmt = $conn->prepare("select status, SID, UID, price FROM order_ where OID=:oid");
            $stmt->execute(array(':oid' => $OID));

            $order_detail = $stmt->fetch();
            $uid = $order_detail['UID'];
            if ($order_detail['status'] != "not finished")
                throw new Exception('ERROR: Order cannot be canceled.'); 
            
            # 取得店家name, 店長uid
            $stmt = $conn->prepare("select shop_name, UID FROM shop where sid=:sid");
            $stmt->execute(array(':sid' => $order_detail['SID']));  # 防SQL injection
            $row = $stmt->fetch();
            $shop_name = $row['shop_name'];
            $shop_manager_uid = $row['UID'];

            # 取得user name
            $stmt = $conn->prepare("select user_name FROM user where uid=:uid");
            $stmt->execute(array(':uid' => $uid));  # 防SQL injection
            $row = $stmt->fetch();
            $user_name = $row['user_name'];

            # 新增user的交易紀錄
            $action = "receive";
            $total_price = $order_detail['price'];
            # foreign key可以是NULL，但要記得去資料庫調說可以NULL
            $stmt=$conn->prepare("insert into transaction_record (TID, action, time, trader, money_change, UID) values (NULL, :action, current_timestamp(), :trader, :price, :UID)"); 
            $stmt->execute(array(':action' => $action, ':trader' => $shop_name, ':price' => $total_price, ':UID' => $uid));

            # 取得user的餘額
            $stmt = $conn->prepare("select money FROM user where uid=:uid");
            $stmt->execute(array(':uid' => $uid));  # 防SQL injection
            $row = $stmt->fetch();
            $user_money = $row['money'];

            # 更新user的餘額
            $user_new_money = $user_money + $total_price; # 現在$total_price是正的
            $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
            $stmt->execute(array(':new_money' => $user_new_money, ':UID' => $uid));

            # 新增shop_mangager的交易紀錄
            $action = "payment";
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
            $shop_manager_new_money = $shop_manager_money + $total_price; # 現在$total_price是負的
            $stmt = $conn->prepare("update user set money=:new_money where UID=:UID");
            $stmt->execute(array(':new_money' => $shop_manager_new_money, ':UID' => $shop_manager_uid));

            #商品數量還原
            # SQL查詢
            $stmt = $conn->prepare("select PID, product_quantity FROM order_product where OID=:oid");
            $stmt->execute(array(':oid' => $OID));  # 防SQL injection
            
            while($row = $stmt->fetch())
            {
            $stmt2 = $conn->prepare("select quantity FROM product where PID=:pid");
            $stmt2->execute(array(':pid' => $row['PID']));  # 防SQL injection

            $product = $stmt2->fetch();
            
            $quantity = $row["product_quantity"] + $product["quantity"];

            $stmt2 = $conn->prepare("update product set quantity=:quantity where PID=:pid");
            $stmt2->execute(array(':quantity' => $quantity, ':pid' => $row['PID']));
            }


            #更新order status跟finish time
            $stmt = $conn->prepare("update order_ set status='cancel', finish_time=current_timestamp() where OID=:OID");
            $stmt->execute(array(':OID' => $OID));
        }

        $_SESSION['cancel'] = $_SESSION['search_type'];
        
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
                alert("Cancel Sucess.");
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