<?php

class Database {

    private static $instance;
    private $connection;

    public function __construct(){

        try {
            $this->connection = pg_connect("
                host = localhost 
                port = 5432 
                dbname = clientapi 
                user = root 
                password =
            ");
            
        } catch (\Throwable $e) {
            echo "No hemos podido conectarnos a la base de datos";   
        }
    }

    public static function getInstance(){
 
        if(!self::$instance){
            self::$instance = new Database();
        }
        
        return self::$instance;
    } 

    public function getConnection(){
        return $this->connection;
    }

    // Evitar la clonación del objeto
    public function __clone() {}

    // Evitar la deserialización del objeto
    public function __wakeup() {}

}
