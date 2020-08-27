<?php
    header('Content-type: text/html; charset=utf-8');
    require 'connect.php';
    
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    session_start();
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($_SESSION["userid"]))
    {
        $cart = $_SESSION["cart"];
        $amounts = $_SESSION["amounts"];
    }
    
    $itemid = htmlspecialchars($_GET["item"]);
    $query = $DBH->prepare("SELECT * FROM Item WHERE Item_ID = '$itemid'");
    $query->execute(); 
    $rows= $query->fetchAll();
    $num_rows = count($rows);
    if($num_rows == 1)
    {
        foreach ($rows as $row) 
        {
            $itemid = $row["Item_ID"];
            $itemName = $row["Item_name"];
            $itemPrice = $row["Item_price"];
            $item_url = $row["Item_URL"];
            $itemDesc = $row["Item_description"];
            if($item_url === ""){$item_url = "http://localhost/netbeans/Seminarska/no-photo.jpg";}
        }
    } else {
        echo "Error";
       }
    if(isset($_POST["cartadd"]))
    {
        $_SESSION["itemid"] = $itemid;
        if($_POST["cartadd"]) 
        {
            if(!empty($_POST["amount"]))
            {
                $kolicina = test_input($_POST["amount"]);
                array_push($_SESSION["cart"], $itemName);
                array_push($_SESSION["amounts"], $kolicina);
                array_push($_SESSION["prices"], $itemPrice);
                array_push($_SESSION["IDs"], $itemid);
                $_SESSION["total_price"] += $itemPrice * $kolicina;
            }
        }
    }?>

<html>
<head>
    <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Item - <?php echo "$itemName"?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="artikelbody">
    <div id="header">
        <div id="header-navbar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><span class="error"><?php //echo $error?></li>
                    <ul>
                        <?php 
                        if(isset($_SESSION['userid']))
                        {?>
                        <li><a href="logout.php">Log out</a></li>
                        <?php  
                        if($_SESSION["usertype"] == "Staff")
                        {
                                    if($_SESSION["isAdmin"] == 1) 
                                    {?>
                                    <li><a href="./secure/admin-update">My profile</li>    
                              <?php } else {?>
                                    <li><a href="./secure/staff-home.php">My profile</li>
                              <?php    }?>
                <?php   }
                                if($_SESSION["usertype"] == "Customer") 
                                {?>
                                    <li><a href="cart.php">My cart <?php $itemsInCart = count($_SESSION["cart"]); echo "($itemsInCart)";?></a></li>
                                    <li><a href="#">My profile</a></li>
                        <?php   }?>
                  <?php } 
                        else { ?>
                        <li></li>
                        <li><a href="register.php">Register</a></li>
                        <?php }
                        ?>
                    </ul>
                </ul>
        </div>
        <div id="background">
            <center>
            <table id="tabela">
                <tr>
                    <td><center><b><?php echo "$itemName";?></b></center></td>
                </tr>
                <tr>
                    <td><center><img width="150px" height="150px" src="<?php echo"$item_url";?>"></center></td>
                </tr>
                <tr>
                    <td><center><p>Price:<?php echo"$itemPrice";?>&#8364</p></center></td>
                </tr>
                <tr>
                    <td><center><p><?php echo "$itemDesc"?></p></center></td>
                </tr>
                <?php 
                if(isset($_SESSION["userid"])) 
                {
                    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
                    {
                    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    header("Location: $url");
                    exit();
                    }?>
                <tr>
                    <td>
                        <form action="<?= $url."?item=$itemid"?>" method="post">
                            <center>x<input type='text' name="amount" maxlength="2" size="2"></center>
                            <center><input type="submit" name="cartadd" value="Add to cart!"></center>
                        </form>
                    </td>
                </tr> 
          <?php } ?>
            </table>
            </center>
        </div>
    </div>
    
</body>
</html>