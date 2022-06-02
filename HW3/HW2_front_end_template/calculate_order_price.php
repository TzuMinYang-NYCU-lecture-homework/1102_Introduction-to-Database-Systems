<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼

    try
    {
        # 避免直接輸入網址跳過來
        if (!isset($_POST['sid']) || !isset($_POST['meal_count']))
        {
            header("Location: ../nav.php#menu1");
            exit();
        }
        
        # 取得POST的資料
        $sid = $_POST['sid'];
        $meal_count = $_POST['meal_count'];
        $uid = $_SESSION['UID'];

        # create PDO
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

        # 計算price並用modal顯示內容，透過javascript讓modal在頁面載入時自己打開，按叉叉時跳回home
        echo <<<EOT
        <!doctype html>
        <html lang="en">

        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Bootstrap CSS -->

            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        </head>
        <body>
        <script>
            $(window).on('load', function() {
                $('#shop$sid').modal('show');
            });

            function back_to_home()
            {
                window.location.replace("nav.php");
            }
        </script>
        <form action="./php/add_order.php" method="POST">
        <input class="form-control" name="sid" value="$sid" type="hidden">
        <!-- Modal -->
        <div class="modal fade" id="shop$sid"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="back_to_home()">&times;</button>
                <h4 class="modal-title">order</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                <div class="  col-xs-12">
                    <table class="table" style=" margin-top: 15px;">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Picture</th>
                        <th scope="col">meal name</th>
                        <th scope="col">price</th>
                        <th scope="col">Order Quantity</th>
                        </tr>
                    </thead>
                    
                    <tbody>
        EOT;

        $subtotal = 0;
        $count = 0;
        # 取user點餐時的所有資料, 資料都從user那邊取是因為怕資料庫的內容已經被刪, 但不想在這邊檢查, 在下一步檢查就好
        for ($i = 0; $i < $meal_count; $i++)
        {
            $input_meal_number = $sid."_".$i;
            $picture = $_POST[$input_meal_number."picture"];
            $picture_type = $_POST[$input_meal_number."picture_type"];
            $meal_name = $_POST[$input_meal_number."meal_name"];
            $price = $_POST[$input_meal_number."price"];
            $quantity =  $_POST[$input_meal_number."quantity"];

            if(intval($quantity) <= 0) continue; # quantity = 0的meal不用出現在order中
            $subtotal += intval($price) * intval($quantity);

            
            # count是新的index，因為quantity是0的meal不會顯示
            $input_meal_name_name = $sid."_".$count."meal_name";
            $input_quantity_name = $sid."_".$count."quantity";

            $count++;
            echo <<<EOT
                        <tr>
                        <th scope="row">$count</th>
                        <td><img src="data:$picture_type; base64, $picture" width="50" heigh="10" /></td>
                        <td>$meal_name</td>
                        <td>$price </td>
                        <td>$quantity </td>
                        <td><input class="form-control" name="$input_quantity_name" value="$quantity" type="hidden"></td>
                        <td><input class="form-control" name="$input_meal_name_name" value="$meal_name" type="hidden"></td>
                        </tr> 
            EOT;
        }
        
        if ($count == 0) throw new Exception('ORDER ERROR: Please order something.'); # 什麼都沒點，也就是每個quantity input都是0
        
        # 取distance
        $stmt = $conn->prepare("select st_distance_sphere(user_location, shop_location) as distance from shop, user where sid=:sid and user.uid=:uid");
        $stmt->execute(array(':sid' => $sid, ':uid' => $uid));  # 防SQL injection
        $row = $stmt->fetch();
        $distance = $row['distance'];
        $_SESSION['distance'] = $distance; # order要用
        $_SESSION['order_type'] = $_POST['order_type']; # order要用

        $delivery_fee = 0;
        if($_POST['order_type'] == 'delivery')
        {
            $delivery_fee = round($distance / 1000 * 10);
            if($delivery_fee < 10) $delivery_fee = 10;
        }

        $total_price = $subtotal + $delivery_fee;

        echo <<<EOT
                        </tbody>
                        </table>
                        <input class="form-control" name="meal_count" value="$count" type="hidden">
                    </div>
                    </div>
                </div>

                <div class="modal-footer">
                    Subtotal $subtotal<br>
                    Delivery fee $delivery_fee<br>
                    Total price $total_price<br>
                    <button type="submit" class="btn btn-default">Order</button>
                </div>
                </div>
            </div>
            </div>
            <input class="form-control" name="delivery_fee" value="$delivery_fee" type="hidden">
            </form>
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
                window.location.replace("nav.php");
            </script>
        </body>
        </html>
        EOT;
    }
?>

</body>
</html>