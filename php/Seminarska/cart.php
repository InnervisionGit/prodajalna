<?php
    session_start();
    require 'connect.php';
    
    if(isset($_SESSION["userid"]))
    {
        $cart = $_SESSION["cart"];
        $amounts = $_SESSION["amounts"];
        $prices = $_SESSION["prices"];
        $total = $_SESSION["total_price"];
    }
    
    if(isset($_POST["kupibtn"]))
    {
        if($_POST["kupibtn"])
        {
            $_SESSION["total_price"] = $total;
            Header("Location: ./prebill.php");
        }
    }
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' )
    {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
    }
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Cart - <?php echo $_SESSION["firstName"]?></title>	
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script>                
                function RemoveItem(id, o)
                {
                    $.post('remove.php',
                        { ItemID: id },
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
                
                function UpdateItem(index, sender) 
                {
                    $.post('update.php',
                        { Index: index, Value: sender.value },
                        function(data) {
                            if(data==='success') 
                            {
                                alert("success");
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
                        <li><a href="index.php">Home</a></li>
                        <li class="index_li"><a href="customer-orders.php">Shipped orders</a></li>
                        <li class="index_li"><a href="customer-update.php">Update account</a></li>
                        <li><span class="error"><?php //echo $error?></li>
                        <ul>
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
                            <?php
                            } 
                            else { ?>
                            <li></li>
                            <li><a href="register.php">Register</a></li>
                            <?php 
                            }
                            ?>
                        </ul>
                    </ul>
            </div>
        </div>
        <div id="content">
                
                        <?php
                    if(count($cart) > 0) 
                    {?>
                        <center>
                            <table id="tabela">
                                <caption><b>Your cart:</b></caption><?php
                        $i = 0;
                        foreach ($cart as $index => $cell) 
                        {?>
                                <tr id="<?php echo $index?>"><td><?php echo "$cell"?></td>
                                <td> x<input id="updatetext" type='text' name="kolicina" maxlength="2" size="2" value="<?php echo "$amounts[$index]";?>" onchange="UpdateItem(<?php echo $index ;?>, this)"></td>
                                <td><input type="button" value="Remove" onclick="RemoveItem(<?php echo $index ;?>, this)"></td></tr>
                  <?php $i++;
                        } ?>
                            <tr>
                                <td>
                                    <center><b>Total:</b> <?php echo $total?>&#8364</center>
                                </td>
                            </tr>
                            </table>
                        </center>
            
            <form action="<?= $url ?>" method="post">
                <center><input type="submit" name="kupibtn" value="Finish order"></center>
            </form>
              <?php }
                    else { ?>
            <center><p>Cart is empty</p></center>
                    <?php } ?>
            
        </div>
    </body>
</html>