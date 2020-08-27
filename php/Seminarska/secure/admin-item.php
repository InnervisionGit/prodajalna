<?php
    header('Content-type: text/html; charset=utf-8');
    require 'connect.php';
    
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    session_start();
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    
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
            $itemPrice = $row["Item_price"];
            $item_url = $row["Item_URL"];
            $itemDesc = $row["Item_description"];
            if($item_url === ""){$item_url = "http://localhost/netbeans/seminar/no-photo.jpg";}
        }
    } else {
        echo "Error.";
       }
?>

<html>
<head>
    <meta http-eqiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Item - <?php echo "$itemName"?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="artikelbody">
    <div id="header">     
        <div id="header-navbar">
            <ul><?php if(isset($_SESSION["usertype"]) && $_SESSION["usertype"] == "Staff") {?>
                <li class="index_li"><a href="admin-home.php">Home</a></li>
                <li class="index_li"><a href="admin-create-staff.php">Create staff</a></li>
                <li class="index_li"><a href="admin-update-staff.php">Update staff</a></li>
                <li class="index_li"><a href="admin-update.php">Update profile</a></li>
                <li class="index_li"><span class="error"><?php echo $error;?></li>
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
        <div id="content">
            <center>
            <table id="tabela">
                <tr>
                    <td><center><b><?php echo "$itemName";?></b></center></td>
                </tr>
                <tr>
                    <td><center><img width="150px" height="150px" src="<?php echo"$item_url";?>"></center></td>
                </tr>
                <tr>
                    <td><center><p>Cena:<?php echo"$itemPrice";?>&#8364</p></center></td>
                </tr>
                <tr>
                    <td><center><p><?php echo "$itemDesc"?></p></center></td>
                </tr>
            </table>
            </center>
        </div>
    
</body>
</html>