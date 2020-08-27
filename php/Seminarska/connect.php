<?php

try {
    //povezava na podatkovno bazo: ime sheme = Prodajalna, uporbniško ime = root, geslo = ep
  $DBH = new PDO("mysql:host=localhost;dbname=Prodajalna", "root", "ep");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>


