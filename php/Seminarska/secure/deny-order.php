<?php 
session_start();
require 'connect.php';

$index = isset($_POST['Index'])?intval($_POST['Index']):-1;
$orderid = isset($_POST['Order'])?intval($_POST['Order']):-1;

if($index<0) die("Invalid ID");

if(isset($orderid)) 
{
    $drop_query = $DBH->prepare("DELETE FROM `contains` WHERE Order_ID = '$orderid';");
    $drop_query_two = $DBH->prepare("DELETE FROM `Order` WHERE Order_ID = '$orderid';");
    $drop_query->execute();
    $drop_query_two->execute();
    echo 'success';
}