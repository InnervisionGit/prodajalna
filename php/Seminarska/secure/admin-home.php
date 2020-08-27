<?php

session_start();
    header('Content-type: text/html; charset=utf-8');
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    $error = "";
    
    if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) 
    {
        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $url");
        exit();
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Spletna prodajalna</title>	
        <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div id="header">     
        <div id="header-navbar">
            <ul>
                <?php 
                if(isset($_SESSION["usertype"]) == "Staff") 
                {?>
                <li class="index_li"><a href="<?php echo "$url";?>">Home</a></li>
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

  <div id="content">
    <?php
    require 'connect.php';
    $query = $DBH->prepare("SELECT * FROM Item ");
    $query->execute(); 
    $rows= $query->fetchAll();
    $num_rows = count($rows);
    
    if ($num_rows > 0)
    {
        foreach ($rows as $row) 
        {
            if($row["Item_isApproved"] == true)
            {
                $itemid = $row["Item_ID"];
                $itemName = $row["Item_name"];
                $itemPrice = $row["Item_price"];
                $item_url = $row["Item_URL"];
                            
                if($item_url === ""){$item_url ="https://localhost/netbeans/Seminarska/no-photo.jpg";}
                ?>
                <div class="wrapper">
                <p><b><?php echo "$itemName";?></b></p>
                <p><a href="admin-item.php?item=<?php echo "$itemid"?>"><img  width="150px" height="150px" src="<?php echo"$item_url";?>"></a></p>
                <p><?php echo"$itemPrice";?>&#8364</p>
                </div>
                        
                <?php  
            }
                        
        }
                    
    }else{
        echo "0 results";
       }
    
    ?>
</body>