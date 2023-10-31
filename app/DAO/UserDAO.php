<?php
 
class UserDAO {

    private $connection;

    public function __construct() {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function getByUsernameAndPassword($username, $password){

        $query = "SELECT * FROM clients WHERE usuario = $1 and contrasena = $2 and estado = true";

        $passwordMD5 = md5($password);

        $result = pg_query_params($this->connection, $query, array($username, $passwordMD5));
        
        if (!($result && pg_num_rows($result) > 0))
            return null;
    
        $row = pg_fetch_assoc($result);
        
        $user = UserModel::getInstance();

        $user->setId($row['id_client']);
        $user->setUserName($row['usuario']);

        return $user->toArray();
    }

    public function getUserById($id){

        $query = "SELECT * FROM clients WHERE id_client = $1 and estado = true";

        $result = pg_query_params($this->connection, $query, array($id));
        
        if (!($result && pg_num_rows($result) > 0))
            return null;
    
        $row = pg_fetch_assoc($result);
        
        $user = UserModel::getInstance();

        $user->setId($row['id_client']);
        $user->setName($row['nombre']);
        $user->setUserName($row['usuario']);
        $user->setLastName($row['apellidos']);
        $user->setEmail($row['email']);
        $user->setPhone($row['telefono']);
        $user->setBirthDate($row['fecha_nacimiento']);

        return $user->toArray();
    }

    public function getAllUsers(){

        $query = "SELECT * FROM clients WHERE estado = true";
        $result = pg_query($this->connection, $query);
    
        $users = array();
    
        while ($row = pg_fetch_assoc($result)) {

            $user = UserModel::getInstance();

            $user->setId($row['id_client']);
            $user->setName($row['nombre']);
            $user->setUserName($row['usuario']);
            $user->setLastName($row['apellidos']);
            $user->setEmail($row['email']);
            $user->setPhone($row['telefono']);
            $user->setBirthDate($row['fecha_nacimiento']);
                        
            array_push($users, $user->toArray());
        }
    
        return $users;
    }

    public function create(UserModel $user){

        $query = "INSERT INTO clients (usuario, contrasena, nombre, apellidos, email, telefono, fecha_nacimiento) 
                  VALUES ($1, $2, $3, $4, $5, $6, $7)";
        
        $params = array(
            $user->getUsername(),$user->getPassword(), 
            $user->getName(), $user->getLastName(),
            $user->getEmail(), $user->getPhone(), $user->getBirthDate()
        );

        $result = pg_query_params($this->connection, $query, $params);
        
        return $result;
    }
    
    public function update(UserModel $user){

        $query = "UPDATE clients SET 
                contrasena = $1,
                nombre = $2,
                apellidos = $3,
                email = $4,
                telefono = $5,
                fecha_nacimiento = $6
                WHERE usuario = $7";
        
        $params = array(
            md5($user->getPassword()),
            $user->getName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPhone(),
            $user->getBirthDate(),
            $user->getUsername()
        );

        $result = pg_query_params($this->connection, $query, $params);
        
        return $result;
    }

    public function softDelete($id){

        $query = "UPDATE clients SET estado = false WHERE id_client = $1";
        $result = pg_query_params($this->connection, $query, array($id));
        
        return $result;
    }
}