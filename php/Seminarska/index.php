<?php
    header('Content-type: text/html; charset=utf-8');
    
    require 'connect.php';
    //vsebuje povezavo na podatkovno bazo
    
    $url = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS);
    //varna hramba url-ja -$_SERVER['PHP_SELF']
    
    $showData = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';
    //ce je zahtevek po metodi POST vrne TRUE. varnejsa oblika -$SERVER['REQUEST_METHOD']

    function test_input($data)
    {
        //ocisti podatke od presledkov, posevnic in posebnih znakov
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $error = "";
    
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
        //ce seja ne obstaja, jo zacni...
    }else
    {
        //... ce seja obstaja, vzemi email in vlogo od uporabnika
        $email = $_SESSION["email"];
        $usertype = $_SESSION["usertype"];
    }
    if($showData)
    {

        if(!empty($_POST["username"]))
        {
            $loggedEmail = test_input($_POST["username"]);
            //ce polje username v $_POST ni prazno, ga ocisti in shrani v spremenljivko
            
        }
        if(!empty($_POST["password"]))
        {
            $loggedPass = test_input($_POST["password"]);
            //ce polje password v $_POST ni prazno, ga ocisti in shrani v spremenljivko
        }
        
        if(isset($loggedEmail) && isset($loggedPass))
        {
            //ce sta pass in email nastavljena naredi...

            $query = $DBH->prepare("SELECT * FROM Customer WHERE Customer_email = '$loggedEmail' AND Customer_password = '$loggedPass' AND Customer_isApproved = 1;");
            //pripravljen stavek za izbiro določene stranke iz podatkovne baze
            
            $query->execute();
            //izvršitev zgornjega stavka
            
            $rows= $query->fetchAll();
            //vrne vrstico, ki zadošča poizvedbi in jo shrani v spremenljivko, ki je array
            
            $num_rows = count($rows);
            //preštej koliko vrstic je bilo vrnjenih z zgornjo poizvedbo in število shrani v spremenljivko
            //Koliko strank s podanimi podatki obstaja. (naj bi bilo najvec ena ali nic.)
            
            
            if($num_rows == 1)
            {
                //ce stranka obstaja
                
                foreach ($rows as $row)
                {
                    //vrednosti v vrnjenem seznamu (array-u) prepiši v spremenljivke..
                    
                    $usertype = "Customer";
                    //vloga uporabnika (Customer ali Staff)
                    
                    $userid = $row["Customer_ID"];
                    //ID stranke iz nastalega arraya shrani v spremenljivko
                    
                    $firstName = $row["Customer_firstName"];
                    //ime stranke iz nastalega arraya shrani v spremenljivko
                    
                    $lastName = $row["Customer_lastName"];
                    //priimek stranke iz nastalega arraya shrani v spremenljivko
                    
                    $email = $row["Customer_email"];
                    //email stranke iz nastalega arraya shrani v spremenljivko
                    
                    //$password = $row["Customer_password"];
                    //geslo stranke iz nastalega arraya shrani v spremenljivko
                    //mogoce lahko predstavlja varnostno luknjo?
                    
                    //nastavi spremenljivke seje..
                    $_SESSION["usertype"] = $usertype;
                    $_SESSION["userid"] = $userid;
                    $_SESSION["firstName"] = $firstName;
                    $_SESSION["lastName"] = $lastName;
                    $_SESSION["email"] = $email;
                    //podatki stranke
                    
                    $_SESSION["total_price"] = 0;
                    //intiger za prikaz skupne cene izdelkov v kosarici
                    
                    $_SESSION["cart"] = array();
                    $_SESSION["amounts"] = array();
                    $_SESSION["prices"] = array();
                    $_SESSION["IDs"] = array();
                    //za zadaj spremenljivke, ki so prazni seznami. za namene kosarice.
                    
                    //ustvarjena seja ima zdaj določene spremenljivke, ki bodo uporabljene za applikacijo.
                    
                    if (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' )
                    {
                        //ce https ni nastavljen ali je ugasnjen...
                        
                        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        //spremeni url spremenljivko.
                        
                        header("Location: $url");
                        //spremeni glavo zahtevka, in sicer za lokacijo uporabi novonastali URL.
                        
                        exit();
                    }
                }
                
            //.. $num_rows je 0, kar pomeni, da stranka z podanimi parametri ne obstaja..
            }else if($num_rows == 0)
            {
                //..zato preverimo, če ti parametri pripadajo zaposlenemu.
                
                $query = $DBH->prepare("SELECT * FROM Staff WHERE Staff_email = '$loggedEmail' AND Staff_password = '$loggedPass' AND Staff_isApproved = 1;");
                $query->execute(); 
                $rows= $query->fetchAll();
                $num_rows = count($rows);
                
                if($num_rows == 1) 
                {
                    //Zaposleni obstaja.. naredimo isto kot za stranko. 
                    foreach ($rows as $row)
                    {
                        //vrednosti iz arraja zapisemo v spsremenljivke, in ustvarimo nove spremenljivke za sejo.
                        $usertype = "Staff";
                        $userid = $row["Staff_ID"];
                        $firstName = $row["Staff_firstName"];
                        $lastName = $row["Staff_lastName"];
                        $email = $row["Staff_email"];
                        //$password = $row["Staff_password"];
                        //varnost?
                        $isAdmin = $row["Staff_isAdmin"];
                        $_SESSION["usertype"] = $usertype;
                        $_SESSION["userid"] = $userid;
                        $_SESSION["firstName"] = $firstName;
                        $_SESSION["lastName"] = $lastName;
                        $_SESSION["email"] = $email;
                        $_SESSION["isAdmin"] = $isAdmin;
                    }
                }
            }else
            {
                //uporabnika ne najdemo v tabelah 'Customer' in 'Staff' v podatkovni bazi.. ali pa je zastavica 'isApproved' nastavljena na 0
                
                $error = "User does not exist or is not yet approved.";
            }
        }
       // $userid = $_SESSION['userid'];
       // $username = $_SESSION['username'];
    }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Online Store</title>	
        <link rel="stylesheet" type="text/css" href="styles.css">
</head>


<body>
    <div id="header">     
        <div id="header-navbar">
            <ul>
                <?php 
                if(isset($_SESSION["usertype"]) && $_SESSION["usertype"] == "Customer")
                {?>
                <!-- Spletni vmesnik za stranko -->
                
                <li class="index_li"><a href="<?php echo "$url";?>">Home</a></li>
                <li class="index_li"><a href="customer-orders.php">Shipped orders</a></li>
                <li class="index_li"><a href="customer-update.php">Update account</a></li>
                
                <ul>
                    <?php
                    if(isset($_SESSION['userid']))
                    {?>
                    
                    <li class="index_li"><a href="logout.php">Log out</a></li>
                    
                        <?php
                        if($_SESSION["usertype"] == "Customer") 
                        {?>
                    
                            <li class="index_li"><a href="cart.php">My cart <?php $itemsInCart = count($_SESSION["cart"]); echo "($itemsInCart)";?></a></li>
                        <?php 
                        }?>
                    <?php
                    }                    
                }else if(isset($_SESSION["usertype"]) && $_SESSION["usertype"] == "Staff") 
                    {
                        //spletni vmesnik za prodajalca/admina + preklop na zavarovani kanal
                        
                        if($_SESSION["isAdmin"] == 1) 
                        {
                            //preveri zastavico 'isAdmin'
                            
                            header("Location: ./secure/admin-home.php");                           
                        }else {
                            header("Location: ./secure/staff-home.php");
                         }
                    }
                    else {
                        //spletni vmesnik za anonimnega odjemalca
                        ?>
                    <li><form id="form-ilogin" action="<?= $url ?>" method="post">
                            <input type="text" name="username" value="Email">
                            <input type="password" name="password" value="">
                            <input type="submit" name="loginbtn" value="Log in">
                        </form></li>
                    <li class="index_li"><a href="register.php">Register</a></li>
                    <?php 
                    }?>
                </ul>
            </ul>
        </div>
    </div>

  <div id="content">
    <?php
    require 'connect.php';
    $query = $DBH->prepare("SELECT * FROM Item");
    $query->execute(); 
    $rows= $query->fetchAll();
    $num_rows = count($rows);
    if ($num_rows > 0) {
        //ce v tabeli 'Item' obstajajo vnosi jih prikaži
        
        foreach ($rows as $row) {
            if($row["Item_isApproved"] == true){
                $itemID = $row["Item_ID"];
                $itemName = $row["Item_name"];
                $itemPrice = $row["Item_price"];
                $item_url = $row["Item_URL"];
                            
                if($item_url === ""){$item_url ="http://localhost/netbeans/Seminarska/no-photo.jpg";}
                
                ?>
      
                <div class="wrapper">
                <p><b><?php echo "$itemName";?></b></p>
                <p><a href="item.php?item=<?php echo "$itemID"?>"><img  width="150px" height="150px" src="<?php echo"$item_url";?>"></a></p>
                <p><?php echo"$itemPrice";?>&#8364</p>
                </div>

                        
                <?php  
            }
                        
        }
                    
    }else{
        //ce v tabeli 'Item' ni vnosov..
        
        echo "0 results";
    }
    
    ?>
</body>