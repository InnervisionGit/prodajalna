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
        <title> Update item </title>
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
                    <li class="index_li"><a href="staff-update-customer.php">Update custoemr</a></li>
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
            <table id="tabela">
                <caption>Approved items:</caption>
                <tr>
                    <td>Item ID</td>
                    <td>Item name</td>
                </tr>
                <?php 
                    $approvedItems = array();
                    $query_one = $DBH->prepare("SELECT * FROM Item WHERE Item_isApproved = 1");
                    $query_one->execute();
                    $rows1 = $query_one->fetchAll();
                    $num_rows = count($rows1);
                    
                    if($num_rows > 0)
                    {
                        foreach($rows1 as $row1)
                        {
                            array_push($approvedItems, $row1["Item_ID"]);
                            $itemid = $row1["Item_ID"];
                            $itemName = $row1["Item_name"]; ?>
                <tr>
                    <td><?php echo "$itemid";?></td>
                    <td><a href="staff-item.php?item=<?php echo "$itemid";?>"><?php echo "$itemName";?></a></td>
                </tr>
               <?php    }
                    } else { ?>
                <tr>
                    <td><b>No approved items.</b></td>
                </tr>   
                   <?php }
                ?>
            </table>
            <table id="tabela">
                <caption>Non-approved items:</caption>
                <?php 
                    $approvedItems = array();
                    $query_one = $DBH->prepare("SELECT * FROM Item WHERE Item_isApproved = 0");
                    $query_one->execute();
                    $rows1 = $query_one->fetchAll();
                    $num_rows = count($rows1);
                    
                    if($num_rows > 0)
                    { ?>
                <tr>
                    <td>Item ID</td>
                    <td>Item name</td>
                </tr>
                    <?php
                        foreach($rows1 as $row1)
                        {
                            array_push($approvedItems, $row1["Item_ID"]);
                            $itemid = $row1["Item_ID"];
                            $itemName = $row1["Item_name"]; ?>
                <tr>
                    <td><?php echo "$itemid";?></td>
                    <td><a href="staff-item.php?item=<?php echo "$itemid";?>"><?php echo "$itemName";?></a></td>
                </tr>
               <?php    }
                    } else { ?>
                <tr>
                    <td><b>No non-approved items.</b></td>
                </tr>   
                   <?php }
                ?>
            </table>
        </div>
    </body>
</html>