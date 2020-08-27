<?php

    session_start();
    require 'connect.php';
    setlocale(LC_ALL, 'fr_CA.utf-8');
    header('Content-type: text/html; charset=utf-8');
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
    }
    
?>
<html>
    <head>
        <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
        <title> Uredi prodajalca </title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <div id="header">     
        <div id="header-navbar">
            <ul>
                <?php 
                if(isset($_SESSION["usertype"]) == "Staff") 
                {?>
                <li class="index_li"><a href="admin-home.php">Home</a></li>
                <li class="index_li"><a href="admin-create-staff.php">Create staff</a></li>
                <li class="index_li"><a href="admin-update-staff.php">Update staff</a></li>
                <li class="index_li"><a href="admin-update.php">Update profile</a></li>
                <li class="index_li"><span class="error"><?php //echo $error?></li>
                
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
            <table id="tabela">
                <caption>Approved staff members:</caption>
                <?php 
                    $approvedStaff = array();
                    $query_one = $DBH->prepare("SELECT * FROM Staff WHERE Staff_isApproved = 1 AND Staff_isAdmin = 0");
                    $query_one->execute();
                    $rows1 = $query_one->fetchAll();
                    $num_rows = count($rows1);
                    
                    if($num_rows > 0)
                    { ?>
                <tr>
                    <td>Staff ID</td>
                    <td>Staff name</td>
                </tr> <?php
                        foreach($rows1 as $row1)
                        {
                            array_push($approvedStaff, $row1["Staff_ID"]);
                            $staffid = $row1["Staff_ID"];
                            $staffName = $row1["Staff_firstName"]." ".$row1["Staff_lastName"]; ?>
                <tr>
                    <td><?php echo "$staffid";?></td>
                    <td><a href="admin-staff.php?staff=<?php echo "$staffid";?>"><?php echo "$staffName";?></a></td>
                </tr>
               <?php    }
                    } else { ?>
                <tr>
                    <td><b>No approved staff members.</b></td>
                </tr>   
                   <?php }
                ?>
            </table>
            <table id="tabela">
                <caption>Not approved staff members:</caption>
                <?php 
                    $neaktivirani_prod = array();
                    $query_one = $DBH->prepare("SELECT * FROM Staff WHERE Staff_isApproved = 0 AND Staff_isAdmin = 0");
                    $query_one->execute();
                    $rows1 = $query_one->fetchAll();
                    $num_rows = count($rows1);
                    
                    if($num_rows > 0)
                    { ?>
                <tr>
                    <td>Staff ID</td>
                    <td>Staff name</td>
                </tr><?php
                        foreach($rows1 as $row1)
                        {
                            array_push($neaktivirani_prod, $row1["Staff_ID"]);
                            $staffid = $row1["Staff_ID"];
                            $staffName = $row1["Staff_firstName"]." ".$row1["Staff_lastName"];  ?>
                <tr>
                    <td><?php echo "$staffid";?></td>
                    <td><a href="admin-staff.php?staff=<?php echo "$staffid";?>"><?php echo "$staffName";?></a></td>
                </tr>
               <?php    }
                    } else { ?>
                <tr>
                    <td><b>No non-approved staff members.</b></td>
                </tr>   
                   <?php }
                ?>
            </table>
        </div>
    </body>
</html>