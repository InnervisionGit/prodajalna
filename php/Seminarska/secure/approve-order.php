<?php 
session_start();
require 'connect.php';

$staffid = $_SESSION["userid"];
$index = isset($_POST['Index'])?intval($_POST['Index']):-1;
$orderid = isset($_POST['Order'])?intval($_POST['Order']):-1;

if($index<0) die("Invalid ID");

if(isset($orderid)) 
{
    $query = $DBH->prepare("UPDATE `Order` SET Staff_ID = '$staffid', Order_state = 'Approved', Order_approvalDate = CURRENT_TIMESTAMP WHERE Order_ID = '$orderid';");
    $query->execute();
    echo 'success';
}

?>