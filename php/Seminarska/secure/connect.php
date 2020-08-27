<?php

try {
  $DBH = new PDO("mysql:host=localhost;dbname=Prodajalna", "root", "ep");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>


