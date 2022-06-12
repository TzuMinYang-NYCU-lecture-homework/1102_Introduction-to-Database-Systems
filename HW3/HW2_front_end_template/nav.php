<?php
  session_start();
  $dbservername = 'localhost';
  $dbname = 'order_system';
  $dbusername = 'root';
  $dbpassword = '';   # root沒設密碼

  try
  {
    if(!$_SESSION['Authenticated']) # 避免直接輸入網址跳過來
    {
      header("Location: index.php");
      exit();
    }

    $account = $_SESSION['account'];

    # create PDO
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # set the PDO error mode to exception 

    # SQL查詢
    $stmt = $conn->prepare("select UID, account, user_name, identity, phonenumber, ST_AsText(user_location) as location, money FROM user where account=:account");
    $stmt->execute(array(':account' => $account));  # 防SQL injection

    # 確認查詢結果
    if ($stmt->rowCount() != 1) # 因為account是unique的，非1的清況就有問題 !!!可能寫<1比較好?
        throw new Exception('Get user data error.');
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
              window.location.replace("index.php");
          </script>
      </body>
      </html>
      EOT;
  }
?>

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
  <title>Hello, world!</title>

  <script>
		function check_shop_name(shop_name) // 用AJAX確認shop name
		{
			if (shop_name != "")
			{
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					var message;
					if (this.readyState == 4 && this.status == 200) 
					{
						switch(this.responseText) 
						{
							case 'YES':
								message='The shop name is available.';
								break;
							case 'NO':
								message='The shop name has been used.';
								break;
							default:
								message='Oops. There is something wrong.';
								break;
						}
						document.getElementById("msg").innerHTML = message;
					}
				};
				xhttp.open("POST", "./php/check_shop_name.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("shop_name="+shop_name);
			}
			else document.getElementById("msg").innerHTML = "";
		}

    function trigger_my_order_search()  // 切過去my_order時自動觸發一次search
    {
      document.getElementById("my_order_search").click();
    }

    function trigger_shop_order_search()  // 切過去shop_order時自動觸發一次search
    {
      document.getElementById("shop_order_search").click();
    }

    function trigger_transaction_search()  // 切過去transaction時自動觸發一次search
    {
      document.getElementById("transaction_search").click();
    }
  </script>

</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">WebSiteName</a>
      </div>
    </div>
  </nav>
  
  <div class="container">

    <ul class="nav nav-tabs" id="main_nav">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#shop">Shop</a></li>
      <li onclick="trigger_my_order_search()"><a href="#my_order">My Order</a></li>
      <li onclick="trigger_shop_order_search()"><a href="#shop_order">Shop Order</a></li>
      <li onclick="trigger_transaction_search()"><a href="#transaction_record">Transaction Record</a></li>
      <li><a href="./php/logout.php">Logout</a></li>
    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
            <?php
              ### 顯示使用者資料 ###
    
              $row = $stmt->fetch();
              
              $location = rtrim(ltrim($row["location"], "POINT("),")"); # 處理location格式

              $_SESSION['phonenumber'] = $row["phonenumber"];
              $_SESSION['UID'] = $row["UID"];
              $_SESSION['identity'] = $row["identity"];
              $_SESSION['money'] = $row["money"];
              $_SESSION['user_name'] = $row["user_name"]; # add money和add order要用，因為新增交易紀錄需要

              echo "Account: " . $row["account"] . " User: " . $row["user_name"] . " PhoneNumber: " . $row["phonenumber"] ." Location: " . $location;
            ?>
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!-- 編輯經緯度 -->
            <form action="./php/edit_location.php" method="POST">
              <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog  modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">edit location</h4>
                    </div>
                      <div class="modal-body">
                        <label class="control-label " for="latitude">latitude</label>
                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="enter latitude">
                          <br>
                        <label class="control-label " for="longitude">longitude</label>
                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="enter longitude">
                      </div>
                      <div class="modal-footer">
                        <input type="submit" class="btn btn-default" value="Edit">
                      </div>
                  </div>
                </div>
              </div>
            </form>


            <!--  -->
            walletbalance: <?php echo $_SESSION['money']?>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Recharge</button>
            <form action="./php/add_money.php" method="POST">
              <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog  modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Recharge</h4>
                    </div>
                    <div class="modal-body">
                      <input type="number" min="1" value="1" class="form-control" name="value" id="value" placeholder="enter add value">
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-default">Add</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- 
                
             -->
        <form action="./php/search.php" method="POST">
          <h3>Search</h3>
          <div class=" row  col-xs-8">
            <form class="form-horizontal" action="/action_page.php">
              <div class="form-group">
                <label class="control-label col-sm-1" for="Shop">Shop</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="shop" placeholder="Enter Shop name">
                </div>
                <label class="control-label col-sm-1" for="distance">distance</label>
                <div class="col-sm-5">
                  <select class="form-control" name="distance" id="sel1">
                    <option></option>
                    <option>near</option>
                    <option>medium </option>
                    <option>far</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-1" for="Price">Price</label>
                <div class="col-sm-2">
                  <input type="text" name="lprice" class="form-control">
                </div>
                <label class="control-label col-sm-1" for="~">~</label>
                <div class="col-sm-2">
                  <input type="text" name="rprice" class="form-control">
                </div>
                <label class="control-label col-sm-1" for="Meal">Meal</label>
                <div class="col-sm-5">
                  <input type="text" list="Meals" class="form-control" name="meal" id="Meal" placeholder="Enter Meal">
                  <datalist id="Meals">
                    <option value="Hamburger">
                    <option value="coffee">
                  </datalist>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-1" for="category"> category</label>
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" name="category" id="category" placeholder="Enter shop category">
                  <datalist id="categorys">
                    <option value="fast food">
                  </datalist>
                </div>
                <label class="control-label col-sm-1" for="distance">sort</label>
                <div class="col-sm-5">
                  <select class="form-control" name="order" id="sel1">
                    <option>shop_name</option>
                    <option>shop_category</option>
                    <option>distance</option>
                  </select>
                  <select class="form-control" name="by" id="sel1">
                    <option>increase</option>
                    <option>decrease</option>
                  </select>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
              </div>
            </form>
          </div>
        </form>
        <div class="row">
          <div class="  col-xs-8">
            <ul class="nav nav-pills">
              <li class="active"><a href="#tab1" data-toggle="tab">1</a></li>
            <?php
              ### 顯示店家搜尋結果 ###
              if(isset($_SESSION['search']) && $_SESSION['search'] > 0)
              {
                $acc=$_SESSION['account'];
                $tab_count = ceil(count($_SESSION['shop']) / 5); # e.g. 9間10間都要是兩頁

                for($i = 2; $i <= $tab_count; $i++) # 分頁數量
                  echo "<li><a href=\"#tab$i\" data-toggle=\"tab\">$i</a></li>";
                echo "</ul>";

                echo "<div id=\"myTabContent\" class=\"tab-content\">"; # 分頁內容

                $count = 0;
                $cur_tab_num = 0;
                foreach($_SESSION['shop'] as $index=>$single_row)
                {
                  $count++;
                  
                  $sid = $index;
                  $stmt = $conn->prepare("select shop_name, shop_category, st_distance_sphere(user_location, shop_location) as distance FROM shop, user where SID=:sid and account='$acc'");
                  $stmt->execute(array(':sid' => $sid));
                  $row = $stmt->fetch();
                  $shop_name = $row['shop_name'];
                  $category = $row['shop_category'];
                  $distance = $row['distance'];

                  if($count % 5 == 1) # 五個中的第一個的資料開始前要先建tab
                  {
                    $cur_tab_num++;
                    if($count == 1) $active = "active";
                    else $active = "";
                    echo <<<EOT
                    <div class="tab-pane $active" id="tab$cur_tab_num">
                      <table class="table" style=" margin-top: 15px;">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">shop name</th>
                          <th scope="col">shop category</th>
                          <th scope="col">Distance</th>
                          <th scope="col">menu</th>
                        </tr>
                      </thead>
                      <tbody>
                    EOT;
                  }

                  echo <<<EOT
                      <tr>
                        <th scope="row"> $count </th>
                        <td> $shop_name </td>
                        <td> $category </td>
                        <td> $distance </td>
                        <td> <button type="button" class="btn btn-info " data-toggle="modal" data-target="#shop$sid">Open menu</button> </td>
                      </tr>
                  EOT;


                  if($count % 5 == 0) # 五個中的第五個的資料結束後補上
                  {
                    echo <<<EOT
                          </tbody>
                        </table>
                      </div>
                    EOT;
                  }
                }

                if($count % 5 != 0) # 最後一頁未滿五個還是要補上
                {
                  echo <<<EOT
                        </tbody>
                      </table>
                    </div>
                  EOT;
                }
                
                echo "</div>";
              }
            ?>
          </div>
        </div>

            <?php
              ### 每個店家的餐點的modal ###
              if(isset($_SESSION['shop']))
              {
                foreach($_SESSION['shop'] as $index=>$single_row)
                {
                  $sid = $index;
                  echo <<<EOT
                  <form action="calculate_order_price.php" method="POST">
                  <input class="form-control" name="sid" value="$sid" type="hidden">
                  <!-- Modal -->
                  <div class="modal fade" id="shop$sid"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">menu</h4>
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
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Order check</th>
                                  </tr>
                                </thead>
                                
                                <tbody>
                  EOT;

                  $count = 0;
                  # SQL查詢
                  $stmt = $conn->prepare("select picture, picture_type, meal_name, price, quantity FROM product where sid=:sid");
                  $stmt->execute(array(':sid' => $sid));  # 防SQL injection

                  
                  while($row = $stmt->fetch())
                  {
                    
                    $picture = $row["picture"];
                    $picture_type = $row["picture_type"];
                    $meal_name = $row["meal_name"];
                    $price = $row["price"];
                    $quantity = $row["quantity"];

                    $input_meal_number = $sid."_".$count; # 每個meal的index，後面php有辦法取得這種可變數量的資料
                    $input_quantity_name = $input_meal_number."quantity";
                    $input_meal_name_name = $input_meal_number."meal_name";
                    $input_picture_name = $input_meal_number."picture";
                    $input_picture_type_name = $input_meal_number."picture_type";
                    $input_price_name = $input_meal_number."price";
                    
                    $count++;
                    echo <<<EOT
                      <tr>
                        <th scope="row">$count</th>
                        <td><img src="data:$picture_type; base64, $picture" width="50" heigh="10" /></td>
                        <td>$meal_name</td>
                        <td>$price </td>
                        <td>$quantity </td>
                        <td><input class="form-control" name="$input_quantity_name" type="number" value="0" min="0" max="$quantity" style="width:80px"></input> </td>
                        <td><input class="form-control" name="$input_meal_name_name" value="$meal_name" type="hidden"></td>
                        <td><input class="form-control" name="$input_picture_name" value="$picture" type="hidden"></td>
                        <td><input class="form-control" name="$input_picture_type_name" value="$picture_type" type="hidden"></td>
                        <td><input class="form-control" name="$input_price_name" value="$price" type="hidden"></td>
                      </tr> 
                    EOT;
                  }

                  echo <<<EOT
                                </tbody>
                              </table>
                              <input class="form-control" name="meal_count" value="$count" type="hidden">
                              <label for="order_type">Order Type</label>
                              <select name="order_type" id="order_type">
                                <option value="delivery">Delivery</option>
                                <option value="pick_up">Pick-up</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="modal-footer">
                          <button type="submit" class="btn btn-default">Calculate the price</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  </form>
                  EOT;
                }
              }
            ?>
    </div>
      <?php
        ### 查詢自己擁有的店家資訊 ###

        # 確認使用者是否為店長
        $is_manager = true;
        # SQL查詢
        $stmt = $conn->prepare("select identity FROM user where account=:account");
        $stmt->execute(array(':account' => $account));  # 防SQL injection

        $row = $stmt->fetch();
        if($row["identity"] == "normal") $is_manager = false;
        else
        {
          # SQL查詢
          $stmt = $conn->prepare("select SID, shop_name, shop_category, ST_AsText(shop_location) as location FROM shop, user where shop.uid=user.uid and account=:account");
          $stmt->execute(array(':account' => $account));  # 防SQL injection

          $row = $stmt->fetch();

          # 處理location格式
          $location = rtrim(ltrim($row["location"], "POINT("),")"); 
          $location = explode(" ", $location); # 用空白切成兩個字串
          $longtitude = $location[0];
          $latitude = $location[1];      
          
          $name = $row["shop_name"];
          $category = $row["shop_category"];

          # 後面add meal在php需要sid資訊
          $_SESSION['sid'] = $row["SID"];
        }
      ?>
      <div id="shop" class="tab-pane fade">
        <form action="./php/register_shop.php" method="POST">
          <h3> Start a business </h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="ex5">shop name</label>
                <input class="form-control" name="name" id="ex5" placeholder="macdonald" type="text" <?php echo ($is_manager) ? "value=\"$name\" disabled" : ""; ?> oninput="check_shop_name(this.value);"><label id="msg"></label><br>
              </div>
              <div class="col-xs-2">
                <label for="ex5">shop category</label>
                <input class="form-control" name="category" id="ex5" placeholder="fast food" type="text" <?php echo ($is_manager) ? "value=\"$category\" disabled" : ""; ?>>
              </div>
              <div class="col-xs-2">
                <label for="ex6">latitude</label>
                <input class="form-control" name="latitude" id="ex6" placeholder="24.78472733371133" type="text" <?php echo ($is_manager) ? "value=\"$latitude\" disabled" : ""; ?>>
              </div>
              <div class="col-xs-2">
                <label for="ex8">longitude</label>
                <input class="form-control" name="longitude" id="ex8" placeholder="121.00028167648875" type="text" <?php echo ($is_manager) ? "value=\"$longtitude\" disabled" : ""; ?>>
              </div>
            </div>
          </div>
          <div class=" row" style=" margin-top: 25px;">
            <div class=" col-xs-3">
              <button type="submit" class="btn btn-primary" <?php echo ($is_manager) ? "disabled" : ""; ?> >register</button>
            </div>
          </div>
        </form>
        <hr>
        <h3>ADD</h3>

        <div class="form-group ">
          <form action="./php/add_meal.php" method="POST" Enctype="multipart/form-data">

            <div class="row">
              <div class="col-xs-6">
                <label for="meal_name">meal name</label>
                <input class="form-control" id="meal_name" name="meal_name" type="text">
              </div>
            </div>

            <div class="row" style=" margin-top: 15px;">
              <div class="col-xs-3">
                <label for="price">price</label>
                <input class="form-control" id="price" name="price" type="text">
              </div>
              <div class="col-xs-3">
                <label for="quantity">quantity</label>
                <input class="form-control" id="quantity" name="quantity" type="text">
              </div>
            </div>

            <div class="row" style=" margin-top: 25px;">
              <div class=" col-xs-3">
                <label for="myFile">上傳圖片</label>
                <input id="myFile" type="file" name="myFile" multiple class="file-loading">
              </div>
              <div class=" col-xs-3">
                <button style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
              </div>
            </div>

          </form>
        </div>

        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                  <th scope="col">meal name</th>
              
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Edit</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  ### 顯示自己店家內商品 ###
                  if($is_manager)
                  {
                    $count = 0;
                    # SQL查詢
                    $stmt = $conn->prepare("select picture, picture_type, meal_name, price, quantity FROM product where sid=:sid");
                    $stmt->execute(array(':sid' => $_SESSION['sid']));  # 防SQL injection
                    
                    while($row = $stmt->fetch())
                    {
                      #!!! data-target和id有空白的話edit按鈕會無法打開modal，不知道原因
                      $count++;
                      $modal_id = $_SESSION['sid']."_".$count;
                      $picture = $row["picture"];
                      $picture_type = $row["picture_type"];
                      $meal_name = $row["meal_name"];
                      $price = $row["price"];
                      $quantity = $row["quantity"];
                      
                      echo <<<EOT
                        <tr>
                          <th scope="row">$count</th>
                          <td><img src="data:$picture_type; base64, $picture" width="50" heigh="10" /></td>
                          <td>$meal_name</td>
                          <td>$price </td>
                          <td>$quantity </td>
                          <td>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#$modal_id">
                              Edit
                            </button>
                          </td>

                          <form action="./php/edit_meal.php" method="POST">
                            <div class="modal fade" id="$modal_id" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">$meal_name Edit</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>

                                  <div class="modal-body">
                                    <div class="row" >
                                      <div class="col-xs-6">
                                        <label for="$meal_name price">price</label>
                                        <input class="form-control" id="$meal_name price" name="price" type="text">
                                      </div>

                                      <div class="col-xs-6">
                                        <label for="$meal_name quantity">quantity</label>
                                        <input class="form-control" id="$meal_name quantity" name="quantity" type="text">
                                      </div>
                                    </div>
                                  </div>

                                  <div class="modal-footer">
                                    <input class="form-control" name="meal_name" value="$meal_name" type="hidden">
                                    <button type="submit" class="btn btn-secondary">Edit</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                          
                          <td>
                            <form action="./php/delete_meal.php" method="POST">
                              <input class="form-control" name="meal_name" value="$meal_name" type="hidden">
                              <button type="sumit" class="btn btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr> 
                      EOT;
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div id="my_order" class="tab-pane fade">
        <div class="row">
          <div class="col-xs-12">
            <form action="./php/search_my_order.php" method="POST">
              </br>
              <label for="my_order_action">Status</label>
              <select class="form-control" name="my_order_action" id="my_order_action" style="width:120px;"><!--切換搜尋選項-->
                <option value="all">ALL</option>
                <option value="finished">Finished</option>
                <option value="not finished">Not Finish</option>
                <option value="cancel">Cancel</option>
              </select>
              <button type="submit" id="my_order_search" class="btn btn-default">Search</button>
            </form>
          </div>
        </div>

        <div class="row">
          <?php if(isset($_SESSION['my_order_result'])): ?>
            <form action="./php/cancel.php" method="POST">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                <th scope="col"></th>
                  <th scope="col">Order ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Start</th>
                  <th scope="col">End</th>
                  <th scope="col">Shop name</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Order Detail</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $count = 0;
                  foreach($_SESSION['my_order_result'] as $single_row)
                  {
                    $count++;
                    $status = $single_row['status'];
                    $create_time = $single_row['create_time'];
                    $finish_time = $single_row['finish_time'];
                    $SID = $single_row['SID'];
                    $OID = $single_row['OID'];
                    $price = $single_row['price'];

                    $stmt = $conn->prepare("select shop_name FROM shop where sid=:sid");
                    $stmt->execute(array(':sid' => $SID));  # 防SQL injection
                    $shop = $stmt->fetch();
                    $shop_name = $shop['shop_name'];

                    echo <<<EOT
                      <tr>
                    EOT;

                        if ($status != "not finished") 
                        {
                          echo '<td></td><th scope="row">';
                          echo $count;
                          echo '</th>';
                        }
                        else 
                        {
                          echo '<td><input type="checkbox" name="checkbox[]" value="';
                          echo $OID;
                          echo '"></td><td>';
                          echo $count;
                          echo '</td>';
                        }

                    echo <<<EOT
                        <td> $status </td>
                        <td> $create_time </td>
                        <td> $finish_time </td>
                        <td> $shop_name </td>
                        <td> $price </td>
                        <td>
                          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#order$OID">
                            order details
                          </button>
                        </td>
                        <td>
                    EOT;
                    
                    if ($status == "not finished") {
                      echo '<button type="submit" name="cancel_order_id" class="btn btn-danger" value="';
                      echo $OID;
                      echo '">Cancel</button>';
                    }
                    echo <<<EOT
                        </td>
                      </tr>
                    EOT;
                  }
                ?>
              </br>
                <button type="submit" class="btn btn-danger" name="cancel_order_id_many" value = "from_my_order">Cancel selected orders</button>
              </br>
            </form>
              </tbody>
            </table>
          <?php endif ?>
        </div>
            <?php
              ### 每個order的modal ###
              if(isset($_SESSION['my_order_result']))
              {
                foreach($_SESSION['my_order_result'] as $single_row)
                {
                  $oid = $single_row['OID'];
                  echo <<<EOT
                  <!-- Modal -->
                  <div class="modal fade" id="order$oid"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Order</h4>
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
                  # SQL查詢
                  $stmt = $conn->prepare("select PID, product_quantity, product_price FROM order_product where OID=:oid");
                  $stmt->execute(array(':oid' => $oid));  # 防SQL injection
                  
                  while($row = $stmt->fetch())
                  {

                    $stmt2 = $conn->prepare("select picture, picture_type, meal_name, price FROM product where PID=:pid");
                    $stmt2->execute(array(':pid' => $row['PID']));  # 防SQL injection

                    $product = $stmt2->fetch();
                    
                    $picture = $product["picture"];
                    $picture_type = $product["picture_type"];
                    $meal_name = $product["meal_name"];
                    $price = $row["product_price"];
                    $quantity = $row["product_quantity"];
                    
                    $subtotal += $price * $quantity;
                    $count++;
                    echo <<<EOT
                      <tr>
                        <th scope="row">$count</th>
                        <td><img src="data:$picture_type; base64, $picture" width="50" heigh="10" /></td>
                        <td>$meal_name</td>
                        <td>$price </td>
                        <td>$quantity </td>
                      </tr> 
                    EOT;
                  }

                  # 取distance
                  $stmt = $conn->prepare("select st_distance_sphere(user_location, shop_location) as distance from shop, user where sid=:sid and user.uid=:uid");
                  $stmt->execute(array(':sid' => $SID, ':uid' => $_SESSION['UID']));  # 防SQL injection
                  $row = $stmt->fetch();
                  $distance = $row['distance'];
                  $delivery_fee = 0;

                  if($single_row['order_type'] == 'delivery')
                  {
                      $delivery_fee = round($distance / 1000 * 10);
                      if($delivery_fee < 10) $delivery_fee = 10;
                  }

                  $total_price = $subtotal + $delivery_fee;
                  
                  echo <<< EOT
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          Subtotal $subtotal<br>
                          Delivery fee $delivery_fee<br>
                          Total price $total_price<br>
                        </div>
                      </div>
                    </div>
                  </div>
                  EOT;
                }
              }
            ?>
      </div>

      <div id="shop_order" class="tab-pane fade">
      </div>

      <div id="transaction_record" class="tab-pane fade">
        <div class="row">
          <div class="col-xs-12">
            <h3> Transaction Record </h3>
            <form action="./php/search_transaction_record.php" method="POST">
              <label for="transaction_record_action">Action</label>
              <select class="form-control" name="transaction_record_action" id="transaction_record_action" style="width:120px;"><!-- onchange="select_trigger_transaction_search()"-->
                <option value="all">ALL</option>
                <option value="payment">Payment</option>
                <option value="receive">Receive</option>
                <option value="recharge">Recharge</option>
              </select>
              <button type="submit" id="transaction_search" class="btn btn-default">Search</button> <!-- 要hidden的話，class="btn btn-default"要去掉-->
            </form>
          </div>
        </div>

        <div class="row">
          <?php if(isset($_SESSION['transaction_record_result'])): ?>
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">Record ID</th>
                  <th scope="col">Action</th>
                  <th scope="col">Time</th>
                  <th scope="col">Trader</th>
                  <th scope="col">Amount Change</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $count = 0;
                  foreach($_SESSION['transaction_record_result'] as $single_row)
                  {
                    $count++;
                    $action = $single_row['action'];
                    $time = $single_row['time'];
                    $trader = $single_row['trader'];
                    $money_change = $single_row['money_change'];
                    echo <<<EOT
                      <tr>
                        <th scope="row"> $count </th>
                        <td> $action </td>
                        <td> $time </td>
                        <td> $trader </td>
                        <td> $money_change </td>
                      </tr>
                    EOT;
                  }
                ?>
              </tbody>
            </table>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
    });

    // 跳到網址定位的#tab_name頁面，例如網址後面多打#shop，就會跳到shop頁面
    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
      $('.nav-tabs a[href="#' + hash + '"]').tab('show'); 
      setTimeout(() => {scroll(0,0);}, 50);
    }
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>