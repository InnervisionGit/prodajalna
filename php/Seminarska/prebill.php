<?php
    require 'connect.php';
    header('Content-type: text/html; charset=utf-8');
    
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $url");
        exit();
    }
    session_start();
    if(isset($_SESSION["userid"]))
    {
        $cart = $_SESSION["cart"];
        $amounts = $_SESSION["amounts"];
        $prices = $_SESSION["prices"];
        $total = $_SESSION["total_price"];
        $userid = $_SESSION["userid"];
        $idji = $_SESSION["IDs"];
        
        
        
        $query = $DBH->prepare("SELECT Customer_address, Customer_city, Customer_postNumber FROM Customer WHERE Customer_ID = '$userid';");
        $query->execute(); 
        $rows= $query->fetchAll();
        foreach ($rows as $row)
        {
            $address = $row["Customer_address"];
            $city = $row["Customer_city"];
            $postNum = $row["Customer_postNumber"];
        }
    }
    if(isset($_POST["shipbtn"]))
    {
        if($_POST["shipbtn"])
        {
            $status = "Shipped";
            $insertquery = $DBH->prepare("INSERT INTO `Order` (`Customer_ID`, `Order_state`) VALUES ('$userid', '$status')");
            $insertquery->execute();
            
            
            $query = $DBH->prepare("SELECT `Order_ID` FROM `Order` ORDER BY `Order_ID` DESC LIMIT 1");
            $query->execute();
            $rows = $query->fetchAll();
            $num_rows = count($rows);
            if($num_rows == 1)
            {
                foreach($rows as $row)
                {
                    $orderid = $row["Order_ID"];
                    foreach ($cart as $index => $item)
                    {
                        $amount = $amounts[$index];
                        $itemName = $cart[$index];
                        $itemPrice = $prices[$index];
                        $itemid = $idji[$index];
                        
                        $subprice = $itemPrice * $amount;
                        $insertquery = $DBH->prepare("INSERT INTO `contains` (`Item_ID`, `Order_ID`, `Price`) VALUES (
                                                '$itemid', '$orderid', '$subprice')");
                        $insertquery->execute();
                      
                    }
                    
                }
                
            } else {
                echo "Error. Unsuccessful order.";
              }
            
        } 
        
    }
    
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Prebill - <?php echo $_SESSION["firstName"]?></title>	
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    </head>
    <body>
        <div id="header">
            <div id="header-navbar">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="index_li"><a href="customer-orders.php">Shipped orders</a></li>
                        <li class="index_li"><a href="customer-update.php">Update profile</a></li>
                        <li><span class="error"><?php //echo $error?></li>
                        <ul style="float:right;list-style-type:none;">
                            <?php 
                            if(isset($_SESSION['userid']))
                            { ?>
                            <li><a href="logout.php">Log out</a></li>
                                 <?php  
                                 if($_SESSION["usertype"] == "Customer") 
                                 {?>
                            <li><a href="cart.php">My cart <?php $itemsInCart = count($_SESSION["cart"]); echo "($itemsInCart)";?></a></li> 
                            <?php  
                                 }?>
                      <?php } ?>
                        </ul>
                    </ul>
            </div>
        </div>
        <div id="content">
            <center><table id="tabela">
                <caption>Items:</caption>
                <?php 
                foreach($cart as $index => $cell) 
                {?>
                <tr id="<?php echo $index?>"><td><?php echo "$cell"?></td>
                                             <td>x<?php echo "$amounts[$index]";?></td>
                <?php $subprice = $amounts[$index] * $prices[$index]; ?>
                                             <td>=><?php echo "$subprice";?>&#8364</td>
                </tr>
          <?php } ?>
                <tr>
                    <td><center><b>Total:</b> <?php echo $total?>&#8364</center></td>
                </tr>
                <tr>
                    <td>Payment method: </td>
                    <td><select>
                        <option value="cash">Cash</option>
                        <option value="credit">Plastic</option>
                        <option value="paypal">Paypal</option>
                        <option value="bitcoin">Bitcoin</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Shipping address:</td>
                    <td><?php echo "$address";?>,</td>
                    <td><?php echo "$postNum $city";?></td>
                </tr>
            </table></center>
            <center><form action="<?= $url ?>" method="post">
                <input type="submit" name="shipbtn" value="Ship it!">
            </form></center>
        </div>
    </body>
</html>