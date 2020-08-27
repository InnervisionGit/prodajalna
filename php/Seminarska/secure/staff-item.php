<?php

    require 'connect.php';
    session_start();
    
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
        
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
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
            $itemDesc = $row["Item_description"];
            $itemurl = $row["Item_URL"];
            $itemPrice = $row["Item_price"];
            $isApproved = $row["Item_isApproved"];
        }
    } else {
        echo "Error";
      }
    
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    
    $error = "";
    $nameErr = "";
    $costErr = "";
    $approvedErr = "";
    
    $getDesc = "";
    $getUrl = "";
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    
    if($showData)
    {
        if(empty($_POST["name"]))
        {
            $nameErr = "This field is mandatory";
        } else {
            $getArtikelName = test_input($_POST["name"]);
           }
        
        if(!empty($_POST["desc"]))
        {
            $getDesc = test_input($_POST["desc"]);
        }
        
        if(!empty($_POST["url"]))
        {
            $getUrl = test_input($_POST["url"]);
        }
        
        if(empty($_POST["price"]))
        {
            $costErr = "This field is mandatory";
        } else {
            $getPrice = test_input($_POST["price"]);
           }
        
        if($_POST["visible"] == "true") 
        {
            $isActive = 1;
        }else {
            $isActive = 0;
          }
        
        try{
            $updateArtikelquery = $DBH->prepare("UPDATE Item
                                        SET Item_name = '$getArtikelName', Item_description = '$getDesc', Item_URL = '$getUrl', Item_price = '$getPrice',
                                        Item_isApproved = '$isActive' WHERE Item_ID = '$itemid'");
            
            $updateArtikelquery->execute();
            $query = $DBH->prepare("SELECT * FROM Item WHERE Item_ID = '$itemid' AND Item_isApproved = '$isActive'");
            $query->execute();
            $rows= $query->fetchAll();
            $num_rows = count($rows);
            
            if($num_rows == 1)
            {
                Header("Location: ./staff-home.php");
            }
            else {
                echo "Error. Could not update the item.";
            }
        }
        catch (Exception $e){
        $error = "Error. Could not update the item.";
        }
    }
?>

<html>
    <head>
        <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
        <title> Update item - <?php echo "$itemName";?></title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <div id="header">     
            <div id="header-navbar">
                <ul>
                    <?php 
                    if(isset($_SESSION["usertype"]) == "Staff") 
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
                    } ?>
                    </ul>
                </ul>
            </div>
        </div>
        <div id="content">
            <div class="napis"><center><b>Update item.</b></center></div><br>
            <form action="<?= $url."?item=$itemid" ?>" method="post" id="newItem"><center>
            <span class="error" ><?php echo $error;?></span>    
            <table id="tabela">
                <tr>
                    <td>Item name:</td>
                    <td><input type="text" name="name" value="<?php echo "$itemName"; ?>"><span class="error">* <?php echo $nameErr;?></span></td>
                </tr>
                <tr>
                    <td>Item description:</td>
                    <td><textarea rows="4" cols="50" name="desc" form="newItem"><?php echo "$itemDesc"; ?></textarea></td>
                </tr>
                <tr>
                    <td>Image URL:</td>
                    <td><input type="text" name="url" value="<?php echo "$itemurl"; ?>"></td>
                </tr>
                <tr>
                    <td>Item price:</td>
                    <td><input type="text" name="price" value="<?php echo "$itemPrice"; ?>"><span class="error">* <?php echo $costErr;?></span></td>
                </tr>
                <tr>
                    <td>Make item visible in store?:</td>
                    <td><input type="radio" name="visible" value="true" <?php if(isset($isApproved) && $isApproved == 1){ echo "checked";} ?>>YES
                        <input type="radio" name="visible" value="false"<?php if(isset($isApproved) && $isApproved == 0){ echo "checked";} ?>>NO
                    <span class="error" >* <?php echo $approvedErr;?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="create" value="Update item!"></td>
                </tr>

            </table></center>
            </form>
        </div>  
    </body>
</html>