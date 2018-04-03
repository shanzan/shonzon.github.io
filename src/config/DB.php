<?php

// create a database class
class DB{

    //properties
    private $dbhost=DB_HOST;
    private $dbuser=DB_USER;
    private $dbpass=DB_PASSWORD;
    private $dbname=DB_NAME;



    //database connect
    public function connect(){
        $mysql_connect_str="mysql:host=$this->dbhost;dbname=$this->dbname";
        $dbConnection=new PDO($mysql_connect_str,$this->dbuser,$this->dbpass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }

}

?>
