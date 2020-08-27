<?php
    
    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $userid = $_SESSION["userid"];
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $query = $DBH->prepare("SELECT * FROM Customer WHERE Customer_ID = '$userid'");
    $query->execute();
    $rows= $query->fetchAll();
    foreach ($rows as $row)
    {
        $firstName = $row["Customer_firstName"];
        $lastName = $row["Customer_lastName"];
        $email = $row["Customer_email"];
        $address = $row["Customer_address"];
        $city = $row["Customer_city"];
        $postNumber = $row["Customer_postNumber"];
        $phoneNumber = $row["Customer_phoneNumber"];
        $password = $retypePass = $row["Customer_password"];
        
    }
    
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $error = "";
    
    
    
    if($showData)
    { 
        if(!empty($_POST["name"])) 
        {
            $getName = test_input($_POST["name"]);  
        }
        
        if(!empty($_POST["lastname"])) 
        {
            $getLast = test_input($_POST["lastname"]);  
        }
        
        if(!empty($_POST["email"]))
        {
            $getEmail = test_input($_POST["email"]);
        }
        
        if(!empty($_POST["password"]))
        {
            $getPass = test_input($_POST["password"]);
        }
        
        if(!empty($_POST["retypepass"])) 
        {
            $getRetypePass = test_input($_POST["retypepass"]);
        }
        
        if(!empty($_POST["phnumber"]))
        {
            $getPhNumber = test_input($_POST["phnumber"]);
        }
        
        if(!empty($_POST["address"]))
        {
            $getAddr = test_input($_POST["address"]);
        }
        
        if(!empty($_POST["city"])) 
        {
            $getCity = test_input($_POST["city"]);
        }
        
        if(!empty($_POST["post"]))
        {
            $getPost = test_input($_POST["post"]);
        }
        
        if(isset($getPass) && isset($getRetypePass))
        {
            if($getPass === $getRetypePass) 
            {
                try{
                $updatequery = $DBH->prepare("UPDATE Customer
                                            SET Customer_firstName = '$getName', Customer_lastName = '$getLast', Customer_email = '$getEmail', Customer_address = '$getAddr',
                                            Customer_city = '$getCity', Customer_postNumber = '$getPost', Customer_phoneNumber = '$getPhNumber', Customer_password = '$getPass'
                                            WHERE Customer_ID = '$userid'");
                $updatequery->execute();
                Header("Location: ./customer-update.php");
                }
                catch (Exception $e){
                    $error = "Error. Failed to update your account";
                }
            }
            else {
                $error = "Passwords do not match.";
              }
        }
    }
    
?>
<html>
<head>
    <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Updating - <?php echo "$firstName";?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
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
        <center><b>Enter values you wish to change.</b></center><br>
        <form action="<?= $url ?>" method="post"><center>
            <span class="error" ><?php echo $error;?></span>    
            <table id="tabela">
                <tr>
                    <td>First name:</td>
                    <td><input type="text" name="name" value="<?= $firstName ?>"></td>
                </tr>
                <tr>
                    <td>Last name:</td>
                    <td><input type="text" name="lastname" value="<?= $lastName ?>"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email" value="<?= $email ?>"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" value="<?= $password ?>"></td>
                </tr>
                <tr>
                    <td>Password (confirm):</td>
                    <td><input type="password" name="retypepass" value="<?= $retypePass ?>"></td>
                </tr>
                <tr>
                    <td>Phone number:</td>
                    <td><input type="text" name="phnumber" value="<?= $phoneNumber ?>"></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="<?= $address ?>"></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" value="<?= $city ?>"></td>
                </tr>
                <tr>
                    <td>Postal number:</td>
                    <td><input type="text" name="post" value="<?= $postNumber ?>"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="update" value="Update account"></td>
                </tr>

            </table></center>
        </form>
    </div>
</body>
</html>