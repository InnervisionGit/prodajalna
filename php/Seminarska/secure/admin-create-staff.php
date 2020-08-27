<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';

    // ugotovi in počisti url te skripte
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    $error = "";
    
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
        
        if($_POST["approval"] == "y") 
        {
            $approve = 1;
        }else {
            $approve = 0;
          }
        
        if(isset($getPass) && isset($getRetypePass)) 
        {
            if($getPass === $getRetypePass) 
            {
                $query = $DBH->prepare("SELECT * FROM Staff WHERE Staff_email = '$getEmail';");
                $query->execute(); 
                $rows= $query->fetchAll();
                $num_rows = count($rows);
                
                if($num_rows == 0) 
                {
                    $insertquery = $DBH->prepare("INSERT INTO `Staff` (`Staff_ID`, `Staff_firstName`, `Staff_lastName`, `Staff_email`, `Staff_password`, `Staff_isAdmin`, `Staff_isApproved`) VALUES (
                                            '0', '$getName', '$getLast', '$getEmail', '$getPass', 0, '$approve') ");
                    $insertquery->execute();
                    $query->execute();
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    
                    if($num_rows == 1)
                    {
                        Header("Location: ./admin-home.php");
                    }else {
                        echo "Error. Failed to create a customer.";
                      }
                } echo "Email already in use.";
            } else {
                $error = "Passwords do not match";
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
                <li class="index_li"><a href="admin-home.php">Home</a></li>
                <li class="index_li"><a href="admin-create-staff.php">Create staff</a></li>
                <li class="index_li"><a href="admin-update-staff.php">Update staff</a></li>
                <li class="index_li"><a href="admin-update.php">Update profile</a></li>
                <li class="index_li"><span class="error"><?php echo $error?></li>
                <ul>
                    <?php if(isset($_SESSION['userid'])){ ?>
                            <li class="index_li"><a href="logout.php">Log out</a></li> 
                    <?php }
                    }
                    ?>
                </ul>
            </ul>
        </div>
    </div>
    <div id="content"><center><b>Create new staff profile.</b></center><br>
        <form action="<?= $url ?>" method="post"><center>
            <span class="error" ><?php echo $error;?></span>    
            <table id="tabela">
                <tr>
                    <td>First name:</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td>Last name:</td>
                    <td><input type="text" name="lastname"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="text" name="email"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td>Password (confirm):</td>
                    <td><input type="password" name="retypepass"></td>
                </tr>
                <tr><td>Approve staff?</td>
                    <td><select name="approval">
                        <option value="y">NO</option>
                        <option value="n">YES</option>
                    </select></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="update" value="Create new staff member!"></td>
                </tr>

            </table></center>
        </form>
    </div>
</body>
</html>