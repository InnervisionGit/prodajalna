<?php
require_once(__DIR__.'/../DB/db-prodajalna.php');
header('Content-Type: application/json');

$http_method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
$server_addr = filter_input(INPUT_SERVER, "SERVER_ADDR", FILTER_SANITIZE_SPECIAL_CHARS);
$server_addr = "10.0.2.2";
 //'myIP' za dostop preko priklopljene naprave. Ne dela ce je povezava preko rooterja.
 //PC mora biti povezan direktno prekos switcha.
  
 //'localhost'
 //'10.0.2.2' kadar dostopamo preko Android emulatorja


$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$uri = substr($url, 0, strripos($url, "/"));
$request = filter_input(INPUT_GET, "request", FILTER_SANITIZE_SPECIAL_CHARS);




function returnError($message) 
{
    echo json_encode([
        "status" => "error",
        "payload" => $message
    ]);
    exit();
}


if ($request != null) 
{
    $path = explode("/", $request);
} else {
    returnError("Invalid request: missing request path.");
  }



if (isset($path[0])) 
{
    $resource = $path[0];
} else {
    returnError("Invalid request: missing resource.");
  }


if (isset($path[1])) 
{
    $param = $path[1];
} else {
    $param = null;
  }

 

switch ($resource)
{
    case "products":
        if ($http_method == "GET" && $param == null) 
        {
            $products = DBProdajalna::getAllAndroid();
            foreach ($products as $_ => &$product) 
            {
                $product["uri"] = "http://" . $server_addr .
                        $uri . "/products/" . $product["Item_ID"];
            }


            echo json_encode([
                "status" => "success",
                "payload" => $products
            ]);
        } else {
            echo returnError("Unknown request: [$http_method $resource]");
          }
        break;
        
    default:
        returnError("Invalid resource: " . $resource);
        break;
}

