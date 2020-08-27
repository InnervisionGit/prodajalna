<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';

    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
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
    $priceErr = "";
    $approvedErr = "";
    
    $getDesc = "";
    $getUrl = "";
    
    
    if($showData)
    {
        if(empty($_POST["name"]))
        {
            $nameErr = "This field is mandatory.";
        } else {
            $getItemName = test_input($_POST["name"]);
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
            $priceErr = "This field is mandatory.";
        } else {
            $getPrice = test_input($_POST["price"]);
          }
        
        if($_POST["visible"] == "1") 
        {
            $isApproved = 1;
        }else {
            $isApproved = 0;
          }
        
        try{
            $query = $DBH->prepare("SELECT * FROM Item WHERE Item_name = '$getItemName';");
            $query->execute(); 
            $rows= $query->fetchAll();
            $num_rows = count($rows);
            
            if($num_rows == 0) 
            {
                $insertquery = $DBH->prepare("INSERT INTO `Item` (`Item_ID`, `Item_name`, `Item_description`, `Item_URL`, `Item_price`, `Item_isApproved`) VALUES (
                                         '0', '$getItemName', '$getDesc', '$getUrl', '$getPrice', '$isApproved');");
                $insertquery->execute();
                $query2 = $DBH->prepare("SELECT * FROM Item WHERE Item_name = '$getItemName';");
                $query2->execute();
                $rows= $query2->fetchAll();
                $num_rows = count($rows);
                
                if($num_rows == 1)
                {
                    Header("Location: ./staff-home.php");
                }
                else {
                    echo "Error. Item could not be created";
                    
                  }
            } else {
                $error = "Item name already in use.";
                
              }
        }
        catch (Exception $e){
        $error = "Error. Item could not be created";
          }
    }
?>
<html>
    <head>
        <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
        <title> Create item</title>
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
                }?>
                </ul>
            </ul>
        </div>
    </div>
        <div id="content">
            <div class=""><center><b>Create new item:</b></center></div><br>
            <form action="<?= $url ?>" method="post" id="newItem"><center>
            <span class="error" ><?php echo $error;?></span>    
            <table id="tabela">
                <tr>
                    <td>Item name:</td>
                    <td><input type="text" name="name"><span class="error" >* <?php echo $nameErr;?></span></td>
                </tr>
                <tr>
                    <td>Item description:</td>
                    <td><textarea rows="4" cols="50" name="desc" form="newItem"></textarea></td>
                </tr>
                <tr>
                    <td>Image URL:</td>
                    <td><input type="text" name="url"></td>
                </tr>
                <tr>
                    <td>Item price:</td>
                    <td><input type="text" name="price"><span class="error" >* <?php echo $priceErr;?></span></td>
                </tr>
                <tr>
                    <td>Make the item visible in store?:</td>
                    <td><input type="radio" name="visible" value="1">YES
                        <input type="radio" name="visible" value="0">NO
                    <span class="error" >* <?php echo $approvedErr;?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="create" value="Create item!"></td>
                </tr>

            </table></center>
            </form>
        </div>  
    </body>
</html>