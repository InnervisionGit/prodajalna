<?php

    require 'connect.php';
    session_start();
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $staffid = htmlspecialchars($_GET["staff"]);
    
    $query = $DBH->prepare("SELECT * FROM Staff WHERE Staff_ID = '$staffid'");
    $query->execute();
    $rows= $query->fetchAll();
    
    foreach ($rows as $row)
    {
        $staffid = $row["Staff_ID"];
        $firstName = $row["Staff_firstName"];
        $lastName = $row["Staff_lastName"];
        $email = $row["Staff_email"];
        $password = $rePass = $row["Staff_password"];
        $isApproved = $row["Staff_isApproved"];
        
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
    $approvedErr = "";
    
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
        
        if($_POST["visible"] == "true") {
            $isActive = 1;
        }else {
            $isActive = 0;
        }
        
        if(isset($getPass) && isset($getRetypePass)) 
        {
            if($getPass === $getRetypePass)
            {
                try{
                    $updateStrankaquery = $DBH->prepare("UPDATE Staff
                                                SET Staff_firstName = '$getName', Staff_lastName = '$getLast', Staff_email = '$getEmail', Staff_password = '$getPass', Staff_isApproved = '$isActive' WHERE Staff_ID = '$staffid'");
                    $updateStrankaquery->execute();
                    $query = $DBH->prepare("SELECT * FROM Staff WHERE Staff_ID = '$staffid' AND Staff_isApproved = '$isActive'");
                    $query->execute();
                    $rows= $query->fetchAll();
                    $num_rows = count($rows);
                    
                    if($num_rows == 1)
                    {
                        Header("Location: ./admin-home.php");
                    }
                    else {
                        echo "Error. Failed to update staff";
                     }
                }
                catch (Exception $e){
                $error = "Error. Failed to update staff";
                }
            } else {
                $passErr = "Passwords do not match.";
                $repassErr = "Passwords do not match.";  
            }
        }
    }
?>
<html>
<head>
    <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Update staff - <?php echo "$firstName";?></title>
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
    <div id="content"><center><b>Update staff:</b></center><br>
        <form action="<?= $url."?staff=$staffid" ?>" method="post"><center>
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
                <tr><td>Approve the staff member?</td>
                    <td><input type="radio" name="visible" value="true" <?php if(isset($isApproved) && $isApproved == 1){ echo "checked";} ?>>YES
                        <input type="radio" name="visible" value="false"<?php if(isset($isApproved) && $isApproved == 0){ echo "checked";} ?>>NO
                    <span class="error" >* <?php echo $approvedErr;?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="update" value="Update the staff member!"></td>
                </tr>

            </table></center>
        </form>
    </div>
</body>
</html>