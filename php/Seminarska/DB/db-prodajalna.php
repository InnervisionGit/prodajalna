<?php
class DBProdajalna 
{
    private static $host = "localhost";
    private static $user = "root";
    private static $password = "ep";
    private static $schema = "Prodajalna";
    private static $instance = null;
    
protected static $dbh = null;

    private function __construct() {    }
    private function __clone() {    }
    
    public static function getInstance()
    {
        if (!self::$instance) 
        {
           $config = "mysql:host=" . self::$host
                    . ";dbname=" . self::$schema;
           $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            );
            self::$instance = new PDO($config, self::$user, self::$password, $options);
        }
        return self::$instance;
    }




    public static function getAllAndroid() 
    {
        return self::queryAndroid("SELECT Item_ID, Item_name, Item_price, Item_description, Item_URL"
                        . " FROM Item;"
                        //. " WHERE Item_isApproved == 1"
                       //. " ORDER BY Item_ID ASC"
                );

    }




    public static function getConnectionAndroid() 
    {
        if (is_null(self::$dbh))
        {
            self::$dbh = DBProdajalna::getInstance();
        }
        return self::$dbh;
    }
    
    protected static function queryAndroid($sql, array $params = array()) 
    {
        $stmt = self::getConnectionAndroid()->prepare($sql);
        $params_filtered = self::filterParamsAndroid($sql, $params);
        $stmt->execute($params_filtered);
        return $stmt->fetchAll();
    } 
    
    protected static function filterParamsAndroid($sql, array $params) 
    {
        $params_altered = self::alterKeysAndroid($params);
        $sql_split = preg_split("/[\(\) ,]/", $sql);
        $sql_params = array_values(preg_grep('/^:/', $sql_split));
        $result = array();
        
        foreach ($sql_params as $key => $value) 
        {
            if (isset($params_altered[$value])) 
            {
                $result[$value] = $params_altered[$value];
            }

        }
        
        if (count($sql_params) != count($result))
        {
            $message = "Podani in zahtevani parametri se ne ujemajo: "
                    . "zahtevani: (" . implode(", ", $sql_params) . "), "
                    . "podani: (" . implode(", ", array_keys($params)) . ")";
            
            throw new Exception($message);
        }
        return $result;
    }


    
    protected static function alterKeysAndroid(array $params) 
    {
        $result = array();
        	foreach ($params as $key => $value) 
                {
            	$result[':' . $key] = $value;
        	}
       		 return $result;
    }


}

