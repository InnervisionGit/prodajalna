<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $customerid = htmlspecialchars($_GET["customer"]);
    
    $query = $DBH->prepare("SELECT * FROM Customer WHERE Customer_ID = '$customerid'");
    $query->execute();
    $rows= $query->fetchAll();
    
    foreach ($rows as $row)
    {
        $customerid = $row["Customer_ID"];
        $firstName = $row["Customer_firstName"];
        $lastName = $row["Customer_lastName"];
        $email = $row["Customer_email"];
        $address = $row["Customer_address"];
        $city = $row["Customer_city"];
        $postNum = $row["Customer_postNumber"];
        $phnNum = $row["Customer_phoneNumber"];
        $password = $rePass = $row["Customer_password"];
        $isApproved = $row["Customer_isApproved"];
        
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
    $lastErr = "";
    $emailErr = "";
    $passErr = "";
    $repassErr = "";
    $phnrErr = "";
    $addrErr = "";
    $cityErr = "";
    $postErr = "";
    $approvedErr = "";
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    
    if($showData)
    {
        if(empty($_POST["name"])) 
        {
            $nameErr = "This field is mandatory.";
        } else {
            $getName = test_input($_POST["name"]);
          }
        
        if(empty($_POST["lastname"])) 
        {
            $lastErr = "This field is mandatory.";
        } else {
            $getLast = test_input($_POST["surname"]);
          }
        
        if(empty($_POST["email"])) 
        {
            $emailErr = "This field is mandatory.";
        } else {
            $getEmail = test_input($_POST["email"]);
          }
        
        if(empty($_POST["password"])) 
        {
            $passErr = "This field is mandatory.";
        } else {
            $getPass = test_input($_POST["password"]);
          }
        
        if(empty($_POST["retypepass"])) 
        {
            $repassErr = "This field is mandatory.";
        } else {
            $getRetypePass = test_input($_POST["retypepass"]);
           }
        
        if(empty($_POST["phnumber"])) 
        {
            $phnrErr = "This field is mandatory.";
        } else {
            $getPhNumber = test_input($_POST["phnumber"]);
          }
        
        if(empty($_POST["address"])) 
        {
            $addrErr = "This field is mandatory.";
        } else {
            $getAddr = test_input($_POST["address"]);
          }
        
        if(empty($_POST["city"])) 
        {
            $cityErr = "This field is mandatory.";
        } else {
            $getCity = test_input($_POST["city"]);
           }
        
        if(empty($_POST["post"])) 
        {
            $postErr = "This field is mandatory.";
        } else {
            $getPost = test_input($_POST["post"]);
           }
        if($_POST["visible"] == "true") 
        {
            $isActive = 1;
        }else {
            $isActive = 0;
          }
        
        if(!empty(isset($getPass)) && !empty(isset($getRetypePass))) 
        {
            if($getPass === $getRetypePass)
            {
                try{
                    $updateStrankaquery = $DBH->prepare("UPDATE Customer
                                                SET Customer_firstName = '$getName', Customer_lastName = '$getLast', Customer_email = '$getEmail', Customer_address = '$getAddr',
                                                Customer_city = '$getCity', Customer_postNumber = '$getPost', Customer_phoneNumber = '$getPhNumber', Customer_password = '$getPass', Customer_isApproved = '$isActive' WHERE Customer_ID = '$customerid'");
                    $updateStrankaquery->execute();
                    $query = $DBH->prepare("SELECT * FROM Customer WHERE Customer_ID = '$customerid' AND Customer_isApproved = '$isActive'");
                    $query->execute();
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    
                    if($num_rows == 1)
                    {
                        Header("Location: ./staff-home.php");
                    }
                    else {
                        echo "Error. Could not update customer";
                      }
                }
                catch (Exception $e){
                $error = "Error. Could not update customer.";
                 }
            } else {
                $passErr = "Passwords do not match";
                $repassErr = "Passwords do not match";  
               }
        }
    }

?>
<html>
    <head>
        <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
        <title> Update customer - <?php echo "$firstName". " ". "$lastName";?></title>
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
            <div class="napis"><center><b>Update customer:</b></center></div><br>
            <form action="<?= $url."?customer=$customerid" ?>" method="post" id="novIzdelek"><center>
            <span class="error" ><?php echo $error;?></span>    
            <table id="tabela">
                <tr>
                    <td>First name:</td>
                    <td><input type="text" name="name" value="<?= $firstName ?>"><span class="error" >* <?php echo $nameErr;?></span></td>
                </tr>
                <tr>
                    <td>Last name:</td>
                    <td><input type="text" name="lastname" value="<?= $lastName ?>"><span class="error" >* <?php echo $lastErr;?></span></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email" value="<?= $email ?>"><span class="error" >* <?php echo $emailErr;?></span></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" value="<?= $password ?>"><span class="error" >* <?php echo $passErr;?></span></td>
                </tr>
                <tr>
                    <td>Password (confirm):</td>
                    <td><input type="password" name="retypepass" value="<?= $rePass ?>"><span class="error" >* <?php echo $repassErr;?></span></td>
                </tr>
                <tr>
                    <td>Phone number:</td>
                    <td><input type="text" name="phnumber" value="<?= $phnNum ?>"><span class="error" >* <?php echo $phnrErr;?></span></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="<?= $address ?>"><span class="error" >* <?php echo $addrErr;?></span></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" value="<?= $city ?>"><span class="error" >* <?php echo $cityErr;?></span></td>
                </tr>
                <tr>
                    <td>Post number:</td>
                    <td><input type="text" name="post" value="<?= $postNum ?>"><span class="error" >* <?php echo $postErr;?></span></td>
                </tr>
                <tr><td>Approve customer?</td>
                    <td><input type="radio" name="visible" value="true" <?php if(isset($isApproved) && $isApproved == 1){ echo "checked";} ?>>YES
                        <input type="radio" name="visible" value="false"<?php if(isset($isApproved) && $isApproved == 0){ echo "checked";} ?>>NO
                    <span class="error" >* <?php echo $approvedErr;?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="update" value="Update customer!"></td>
                </tr>

            </table></center>
            </form>
        </div>
    </body>
</html>