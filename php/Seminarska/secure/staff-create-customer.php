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

    
    $nameErr = "";
    $lastErr = "";
    $emailErr = "";
    $passErr = "";
    $repassErr = "";
    $phnrErr = "";
    $addrErr = "";
    $cityErr = "";
    $postErr = "";
    $error = "";
    
    
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
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
            $getLast = test_input($_POST["lastname"]);
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
        
        if($_POST["apporval"] == "y") 
            {
            $approve = 1;
        }else {
            $approve = 0;
          }
        
        if(!empty(isset($getPass)) && !empty(isset($getRetypePass))) 
        {
            if($getPass === $getRetypePass)
            {
                //gremo na bazo, preverimo če že obstaja
                $query = $DBH->prepare("SELECT * FROM Customer WHERE Customer_email = '$getEmail';");
                $query->execute(); 
                $rows= $query->fetchAll();
                $num_rows = count($rows);
                
                if($num_rows == 0)
                {
                    $insertquery = $DBH->prepare("INSERT INTO `Customer`
                        (`Customer_ID`, `Customer_firstName`, `Customer_lastName`, `Customer_email`, `Customer_address`, `Customer_city`, `Customer_postNumber`, `Customer_phoneNumber`, `Customer_password`, `Customer_isApproved`)
                        VALUES ('0', '$getName', '$getLast', '$getEmail', '$getAddr', '$getCity', '$getPost', '$getPhNumber', '$getPass', '$approve') ");
                    
                    $insertquery->execute();
                    $query->execute();
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    
                    if($num_rows == 1)
                    {
                        Header("Location: ./staff-home.php");
                    }
                    else {
                        echo "Error. Customer could not be created";
                      }
                } echo "Email already in use.";
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
    <title>Spletna prodajalna - Create customer</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
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
                            <li class="index_li"><a href="logout.php">Lof out</a></li> 
              <?php }
                }?>
                </ul>
            </ul>
        </div>
    </div>
    <div id="content"><center><b>Create new customer:</b></center>
        <div><form action="<?= $url ?>" method="post"><center>
            <table id="tabela">
                <tr>
                    <td>First name:</td>
                    <td><input type="text" name="name"><span class="error" >* <?php echo $nameErr;?></span></td>
                </tr>
                <tr>
                    <td>Last name:</td>
                    <td><input type="text" name="lastname"><span class="error" >* <?php echo $lastErr;?></span></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email"><span class="error" >* <?php echo $emailErr;?></span></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password"><span class="error" >* <?php echo $passErr;?></span></td>
                </tr>
                <tr>
                    <td>Password (confirm):</td>
                    <td><input type="password" name="retypepass"><span class="error" >* <?php echo $repassErr;?></span></td>
                </tr>
                <tr>
                    <td>Phone number:</td>
                    <td><input type="text" name="phnumber"><span class="error" >* <?php echo $phnrErr;?></span></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address"><span class="error" >* <?php echo $addrErr;?></span></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city"><span class="error" >* <?php echo $cityErr;?></span></td>
                </tr>
                <tr>
                    <td>Post number:</td>
                    <td><input type="text" name="post"><span class="error" >* <?php echo $postErr;?></span></td>
                </tr>
                <tr><td>Approve customer?</td>
                    <td><select name="aktivacija">
                        <option value="n">NO</option>
                        <option value="y">YES</option>
                    </select></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="registerbtn" value="Create customer!"></td>
                </tr>

            </table></center>
            </form>
        </div>
    </div>
</body>
</html>