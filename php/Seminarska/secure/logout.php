<?php
//logout.php
session_start();
session_destroy(); 
header("location: https://localhost/netbeans/Seminarska/index.php");
?>