<?php
    session_start();
    $dbservername = 'localhost';
    $dbname = 'order_system';
    $dbusername = 'root';
    $dbpassword = '';   # root沒設密碼
    # 取得POST的資料
    $shop_name = $_POST['shop'];
    $distance = $_POST['distance'];
    $_SESSION['distance']=$_POST['distance'];
    $lprice = $_POST['lprice'];
    $rprice = $_POST['rprice'];
    $meal = $_POST['meal'];
    $category = $_POST['category'];
    # create PDO
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 
    $acc=$_SESSION['account'];
    # SQL查詢
    if($distance=='near')
    {
        if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop_name like '%$shop_name%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) < 1000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) < 1000");
        }
        $stmt->execute();
    }
    else if($distance=='far')
    {
        if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop_name like '%$shop_name%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) > 5000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) > 5000");
        }
        $stmt->execute();
    }
    else
    {
        if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%' and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and commodity_name like '%$meal%' and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :rprice>=price and shop_category like '%$category%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and shop_category like '%$category%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and shop_category like '%$category%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price and commodity_name like '%$meal%'");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
           and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(!empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%' and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price and :rprice>=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && !empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_category like '%$category%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && !empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and commodity_name like '%$meal%'");
        }
        else if(!empty($_POST['shop']) && empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop_name like '%$shop_name%'");
        }
        else if(empty($_POST['shop']) && empty($_POST['lprice']) && !empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :rprice>=price");
            $stmt->bindParam(':rprice', $rprice, PDO::PARAM_STR);
        }
        else if(empty($_POST['shop']) && !empty($_POST['lprice']) && empty($_POST['rprice']) && empty($_POST['meal']) && empty($_POST['category']))
        {
            $stmt = $conn->prepare("select shop_name, shop_category, shop_category from shop, user, product where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000 
            and shop.SID=product.SID and :lprice<=price");
            $stmt->bindParam(':lprice', $lprice, PDO::PARAM_STR);
        }
        else
        {
            $stmt = $conn->prepare("select shop_name, shop_category from shop, user where account='$acc' and ST_Distance(user.location, shop.location) <= 5000 and ST_Distance(user.location, shop.location) >= 1000");
        }
        $stmt->execute();
    }
    $_SESSION['shop'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Location: ../nav.php");
?>