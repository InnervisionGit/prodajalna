<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    //$showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';

    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
    }

    $idorders = array();
    $idcustomers = array();
    $customernames = array();
    $customeraddresses = array();

?>
<html>
    <head>
        <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Spletna prodajalna - Pending orders</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script>
                function Deny(id, o, order)
                {
                    $.post('deny-order.php',
                        { Index: id, Order: order },
                        function(data) 
                        {
                            if(data==='success') 
                            {
                                var p=o.parentNode.parentNode;
                                p.parentNode.removeChild(p); 
                            } else alert("There was an error!");
                        }
                    );
                    location.reload();
                }
                
                function Approve(index, o, order) 
                {
                    $.post('approve-order.php',
                        { Index: index, Order: order },
                        function(data) 
                        {
                            if(data==='success') 
                            {
                                var p=o.parentNode.parentNode;
                                p.parentNode.removeChild(p); 
                            } else {
                                alert("Error updating");
                              }
                        }
                    );
                    location.reload();
                }
                
        </script>
    </head>
    <body>
        <div id="header">     
            <div id="header-navbar">
                <ul>
                    <?php 
                    if(isset($_SESSION["usertype"]) && $_SESSION["usertype"] == "Staff") 
                    {?>
                    <li class="index_li"><a href="staff-home.php">Home</a></li>
                    <li class="index_li"><a href="staff-pending-orders.php">Pending orders</a></li>
                    <li class="index_li"><a href="staff-approved-orders.php">Approved orders</a></li>
                    <li class="index_li"><a href="staff-create-customer.php">Create customer</a></li>
                    <li class="index_li"><a href="staff-update-customer.php">Update customer</a></li>
                    <li class="index_li"><a href="staff-create-item.php">Create item</a></li>
                    <li class="index_li"><a href="staff-update-item.php">Update item</a></li>
                    <li class="index_li"><a href="staff-update.php">Update profile</a></li>
                    <li class="index_li"><span class="error"><?php echo $error?></li>
                    <ul>
                        <?php 
                        if(isset($_SESSION['userid']))
                        { ?>
                                <li class="index_li"><a href="logout.php">Log out</a></li> 
                  <?php }
                    }?>
                    </ul>
                </ul>
            </div>
        </div>
        <div id="content">
            <center><table id="tabela">
                    <caption>Pending orders:</caption>
                <tr>
                    <td>Order ID</td>
                    <td>Customer</td>
                    <td>Customer address</td>
                    <td>Item (amount)</td>
                </tr>
                <?php 
                    $query = $DBH->prepare("SELECT * FROM `Order` o, `Customer` c WHERE Order_state = 'Shipped' AND o.Customer_ID = c.Customer_ID;");
                    $query->execute();
                    $rows = $query->fetchAll();
                    $num_rows = count($rows);
                    
                    if($num_rows > 0)
                    {
                        $index = 0;
                        foreach($rows as $row) 
                        {
                            array_push($idorders, $row["Order_ID"]);
                            $orderid = $row["Order_ID"];
                            array_push($idcustomers, $row["Customer_ID"]);
                            array_push($customernames, $row["Customer_firstName"] ." ". $row["Customer_lastName"]);
                            $nameOfCustomer = $row["Customer_firstName"] ." ". $row["Customer_lastName"];
                            array_push($customeraddresses, $row["Customer_address"]. ", ".$row["Customer_postNumber"]." ".$row["Customer_city"]);
                            $customerLocation = $row["Customer_address"]. ", ".$row["Customer_postNumber"]." ".$row["Customer_city"];

                            $query_items = $DBH->prepare("SELECT Item_ID, Price FROM `contains` WHERE Order_ID = '$orderid'");
                            $query_items->execute();
                            $rows1 = $query_items->fetchAll();

                            $items = array();
                            $amounts = array();

                            foreach($rows1 as $row1)
                            {
                                $itemid = $row1["Item_ID"];
                                $subprice = $row1["Price"];
                                $query3 = $DBH->prepare("SELECT Item_name, Item_price FROM Item WHERE Item_ID = '$itemid'");
                                $query3->execute();
                                $rows3 = $query3->fetchAll();
                                
                                foreach($rows3 as $row3)
                                {
                                    array_push($items, $row3["Item_name"]);
                                    array_push($amounts, $subprice / $row3["Item_price"]);
                                }
                            }?>
                <tr>
                    <td><?php echo "$idorders[$index]";?></td>
                    <td><?php echo "$nameOfCustomer";?></td>
                    <td><?php echo "$customerLocation"?></td>
                    <?php 
                            foreach($items as $i => $cell)
                            { ?>
                            <td><?php echo "$items[$i]";?>(<?php echo "$amounts[$i]";?>)</td><?php
                            }?>
                    <td><input type="button" value="Approve!" onclick="Approve(<?php echo $index ;?>, this, <?php echo "$idorders[$index]";?>)"></td>
                    <td><input type="button" value="Deny!" onclick="Deny(<?php echo $index ;?>, this, <?php echo "$idorders[$index]";?>)"></td>
                </tr>
                    <?php 
                        $index++;
                        }
                    } else {
                        echo "No pending orders";
                      }
                ?>
            
                    
                </table></center>
        </div>
    </body>
</html>