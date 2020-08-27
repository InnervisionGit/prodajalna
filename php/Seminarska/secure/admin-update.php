<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $userid = $_SESSION["userid"]; //dobimo s sessionom
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $query = $DBH->prepare("SELECT * FROM Staff WHERE Staff_ID = '$userid'");
    $query->execute();
    $rows= $query->fetchAll();
    
    foreach ($rows as $row)
    {
        $firstName = $row["Staff_firstName"];
        $lastName = $row["Staff_lastName"];
        $email = $row["Staff_email"];
        $password = $rePass = $row["Staff_password"];
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
        
        if(isset($getPass) && isset($getRetypePass)) 
        {
            if($getPass === $getRetypePass) 
            {
                try{
                $updatequery = $DBH->prepare("UPDATE Staff
                                            SET Staff_firstName = '$getName', Staff_lastName = '$getLast', Staff_email = '$getEmail', Staff_password = '$getPass'
                                            WHERE Staff_ID = '$userid'");
                $updatequery->execute();
                Header("Location: ./admin-home.php");
                }
                catch (Exception $e){
                    $error = "Error. Could not update account";
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
    <title> Updating profile - <?php echo "$firstName";?></title>
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
    <div id="content"><center><b>Enter new information you wish to update.</b></center><br>
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
                    <td><input type="password" name="retypepass" value="<?= $rePass ?>"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="update" value="Update account!"></td>
                </tr>

            </table></center>
        </form>
    </div>
</body>
</html>