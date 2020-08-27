<?php

    require 'connect.php';
    header('Content-type: text/html; charset=utf-8');
    
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
        //ustvarimo zavarovan kanal
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
    }
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Orders - <?php echo $_SESSION["firstName"]?></title>	
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    </head>
    <body>
        <div id="header">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="index_li"><a href="customer-orders.php">Shipped orders</a></li>
                        <li class="index_li"><a href="customer-update.php">Update account</a></li>
                        <li><span class="error"><?php //echo $error?></li>
                        
                            <?php  
                            if($_SESSION["usertype"] == "Customer") 
                            {?>
                                        <li class="index_li"><a href="logout.php">Log out</a></li>
                                        <li><a href="cart.php">My cart <?php $itemsInCart = count($_SESSION["cart"]); echo "($itemsInCart)";?></a></li>
                      <?php }?>

                    </ul>
        </div>
        
        <div id="content">
            <center><table id="tabela">
                <caption>Shipped orders:</caption>
                <tr>
                    <td>Order ID</td>
                    <td>Item (amount)</td>
                </tr>
                <?php 
                    
                    $query = $DBH->prepare("SELECT `Order_ID` FROM `Order` WHERE `Customer_ID` = '$userid' AND Order_state = 'Shipped';");
                    $query->execute(); 
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    if($num_rows > 0) 
                    {
                        foreach ($rows as $row)
                        {
                            $orderid = $row["Order_ID"];
                            $items = array();
                            $amount = array();
                            $querytwo = $DBH->prepare("SELECT `Item_ID`, `Price` FROM `contains` WHERE `Order_ID` = '$orderid';");
                            $querytwo->execute();
                            $rows2 = $querytwo->fetchAll();
                            foreach($rows2 as $row2)
                            {
                                $itemid = $row2["Item_ID"];
                                $subprice = $row2["Price"];
                                $querythree = $DBH->prepare("SELECT `Item_name`, `Item_price` FROM `Item` WHERE `Item_ID` = '$itemid';");
                                $querythree->execute();
                                $rows3 = $querythree->fetchAll();
                                foreach($rows3 as $row3)
                                {
                                    array_push($items, $row3["Item_name"]);
                                    array_push($amount, $subprice / $row3["Item_price"]);
                                }
                            }
                            ?><tr>
                                <td><?php echo "$orderid"?></td>
                                <?php
                                foreach($items as $index => $cell)
                                {?>
                                <td><?php echo "$items[$index]";?>(<?php echo "$amount[$index]"?>)</td>
                         <?php  } ?>
                            </tr>
                  <?php }
                    }else {
                        ?><tr>
                            <td><b>You have no shipped orders.</b></td>
                        </tr>
                <?php }?>
            </table></center>
            <center><table id="tabela">
                <caption>Approved orders:</caption>
                <tr>
                    <td>Order ID</td>
                    <td>Item (amount)</td>
                </tr>
                <?php 
                    
                    $query = $DBH->prepare("SELECT `Order_ID` FROM `Order` WHERE `Customer_ID` = '$userid' AND `Order_state` = 'Potrjeno';");
                    $query->execute(); 
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    if($num_rows > 0)
                    {
                        foreach ($rows as $row)
                        {
                            $orderid = $row["Order_ID"];
                            $items = array();
                            $amount = array();
                            $querytwo = $DBH->prepare("SELECT `Item_ID`, `Price` FROM `contains` WHERE `Order_ID` = '$orderid';");
                            $querytwo->execute();
                            $rows2 = $querytwo->fetchAll();
                            foreach($rows2 as $row2)
                            {
                                $itemid = $row2["Item_ID"];
                                $subprice = $row2["Price"];
                                $querythree = $DBH->prepare("SELECT `Item_name`, `Item_price` FROM `Item` WHERE `Item_ID` = '$itemid';");
                                $querythree->execute();
                                $rows3 = $querythree->fetchAll();
                                foreach($rows3 as $row3)
                                {
                                    array_push($items, $row3["Item_name"]);
                                    array_push($amount, $subprice / $row3["Item_price"]);
                                }
                            }?>
                            <tr>
                                <td><?php echo "$orderid"?></td>
                                <?php 
                                foreach($items as $index => $cell)
                                {?>
                                <td><?php echo "$items[$index]";?>(<?php echo "$amount[$index]"?>)</td><?php
                                }?>
                            </tr>
                  <?php }
                    }else {
                        ?><tr>
                            <td><b>You have no approved orders.</b></td>
                        </tr>
                <?php }?>
            </table></center>
            <center><table id="tabela">
                <caption>Canceled orders:</caption>
                <tr>
                    <td>Order ID</td>
                    <td>Item (amount)</td>
                </tr>
                <?php 
                    
                    $query = $DBH->prepare("SELECT `Order_ID` FROM `Order` WHERE `Customer_ID` = '$userid' AND `Order_state` = 'Stornirano';");
                    $query->execute(); 
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    if($num_rows > 0)
                    {
                        foreach ($rows as $row)
                        {
                            $orderid = $row["Order_ID"];
                            $items = array();
                            $amount = array();
                            $querytwo = $DBH->prepare("SELECT `Item_ID`, `Price` FROM `contains` WHERE `Order_ID` = '$orderid';");
                            $querytwo->execute();
                            $rows2 = $querytwo->fetchAll();
                            foreach($rows2 as $row2)
                            {
                                $itemid = $row2["Item_ID"];
                                $subprice = $row2["Price"];
                                $querythree = $DBH->prepare("SELECT `Item_name`, `Item_price` FROM `Item` WHERE `Item_ID` = '$itemid';");
                                $querythree->execute();
                                $rows3 = $querythree->fetchAll();
                                foreach($rows3 as $row3)
                                {
                                    array_push($items, $row3["Item_name"]);
                                    array_push($amount, $subprice / $row3["Item_price"]);
                                }
                            }?> 
                            <tr>
                                <td><?php echo "$orderid"?></td>
                                <?php
                                foreach($items as $index => $cell)
                                {?>
                                <td><?php echo "$items[$index]";?>(<?php echo "$amount[$index]"?>)</td><?php
                                }?>
                            </tr>
                  <?php }
                    }else {
                        ?><tr>
                            <td><b>You have no canceled orders.</b></td>
                        </tr>
                 <?php }
                ?>
            </table></center>
        </div>
        
    </body>
</html>