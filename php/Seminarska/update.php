<?php

    session_start();
    if(isset($_SESSION["cart"])){
        $cart = $_SESSION["cart"];
        $index = isset($_POST['Index'])?intval($_POST['Index']):-1;
        $newvalue = isset($_POST['Value'])?intval($_POST['Value']):-1;
        if($index<0) die("Invalid ID");
        if(isset($_SESSION["amounts"][$index]) && isset($_SESSION["total_price"]) && isset($_SESSION["prices"][$index])){
            $_SESSION["amounts"][$index] = $newvalue;
            $total = 0;
            for($i = 0; $i < count($cart); $i++){
                $total += $_SESSION["amounts"][$i] * $_SESSION["prices"][$i];
            }
            $_SESSION["total_price"] = $total;
            echo 'success';
        }
    }

?>