<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼
    # 取得POST的資料
    $shop_name = $_POST['shop'];
    $distance = $_POST['distance'];
    $lprice = $_POST['lprice'];
    $rprice = $_POST['rprice'];
    $meal = $_POST['meal'];
    $category = $_POST['category'];
    $order = $_POST['order'];
    $by = $_POST['by'];
    $_SESSION['order'] = $_POST['order'];
    $_SESSION['by'] = $_POST['by'];
    # create PDO
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 
    $acc=$_SESSION['account'];
    # SQL查詢
    if($distance=='')
    {
        if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category, st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%'");
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and meal_name like '%$meal%' and product.SID=shop.SID");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' 
            and shop_name like '%$shop_name%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category, st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc'");
        }
        $stmt->execute();
    }
    else if($distance=='near')
    {
        if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category, st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%'");
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and meal_name like '%$meal%' and shop.SID=product.SID");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop_name like '%$shop_name%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) < 5000");
        }
        $stmt->execute();
    }
    else if($distance=='far')
    {
        if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%'");
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and meal_name like '%$meal%' and shop.SID=product.SID");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop_name like '%$shop_name%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) > 20000");
        }
        $stmt->execute();
    }
    else
    {
        if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and meal_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and meal_name like '%$meal%'");
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and meal_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!(strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && !(strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_category like '%$category%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && !(strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and meal_name like '%$meal%' and shop.SID=product.SID");
        }
        else if(!(strlen($shop_name) == 0) && (strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop_name like '%$shop_name%'");
        }
        else if((strlen($shop_name) == 0) && (strlen($lprice) == 0) && !(strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if((strlen($shop_name) == 0) && !(strlen($lprice) == 0) && (strlen($rprice) == 0) && (strlen($meal) == 0) && (strlen($category) == 0))
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance, shop_category from shop, user, product where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop.SID, shop_name, shop_category,  st_distance_sphere(user_location, shop_location) as distance from shop, user where account='$acc' and st_distance_sphere(user_location, shop_location) <= 20000 and st_distance_sphere(user_location, shop_location) >= 5000");
        }
        $stmt->execute();
    }
    $_SESSION['shop'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($stmt->rowCount() >= 1)
    {
        $_SESSION['search']=1;
        foreach($_SESSION['shop'] as $row)
        {
            $sid = $row['SID'];
            $shop_name = $row['shop_name'];
            $category = $row['shop_category'];
            $distance = $row['distance'];
            $n[$sid]=$shop_name;
            $c[$sid]=$category;
            $d[$sid]=$distance;
        }
        if($order=="shop_name")
        {
            if($by=="increase") asort($n);
            else if($by=="decrease") arsort($n);
            $_SESSION['shop'] = $n;
        }
        else if($order=="shop_category")
        {
            if($by=="increase") asort($c);
            else if($by=="decrease") arsort($c);
            $_SESSION['shop'] = $c;
        }
        else if($order=="distance")
        {
            if($by=="increase") asort($d);
            else if($by=="decrease") arsort($d);
            $_SESSION['shop'] = $d;
        }
    }
    else $_SESSION['search'] = 0;

    header("Location: ../nav.php");
?>