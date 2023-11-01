<?php

class UserModel {
 
    private static $instance = null;
    private $id;
    private $username;
    private $password;
    private $name;
    private $lastName;
    private $email;
    private $phone;
    private $birthDate;
    
    // No permitir instanciación a través del constructor -> Singleton

    private function __construct() {

    }

    // Instancía desde la clase -> Singleton

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new UserModel();
        }
        return self::$instance;
    }
    
    // Getters

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getName(){
        return $this->name;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getBirthDate(){
        return $this->birthDate;
    }
    
    // Setters
    public function setId($id){
        $this->id = $id;
    }

    public function setUserName($username){
        $this->username = $username;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function setBirthDate($birthDate){
        $this->birthDate = $birthDate;
    }

    public function toArray() {
        $data = array(
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'birthDate' => $this->birthDate,
        );

        
        return $data;
    }

    // Evitar la clonación del objeto
    public function __clone() {}

    // Evitar la deserialización del objeto
    public function __wakeup() {}
}