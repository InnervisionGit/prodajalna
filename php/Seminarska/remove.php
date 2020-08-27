<?php 

session_start();

$ItemID = isset($_POST['ItemID'])?intval($_POST['ItemID']):-1;

if($ItemID<0) die("Invalid ID");

if(isset($_SESSION["cart"][$ItemID]) && isset($_SESSION["amounts"]) && isset($_SESSION["prices"])) {
    
    $_SESSION["total_price"] -= $_SESSION["prices"][$ItemID] * $_SESSION["amounts"][$ItemID];
    unset($_SESSION["cart"][$ItemID]);
    unset($_SESSION["amounts"][$ItemID]);
    unset($_SERVER["prices"][$ItemID]);
    unset($_SESSION["IDs"][$ItemID]);
    echo 'success';
}

?>