<?php


class UserController extends BaseController{
    
    private $userDAO;

    public function __construct(){
        $this->userDAO = new UserDAO();
    }

    public function login(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) !== 'POST') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if ($data !== null) {
            $username = $data['username'];
            $password = $data['password'];

            $user = $this->userDAO->getByUsernameAndPassword($username, $password);

            if (!$user) {
                $this->sendOutput(
                    json_encode(array('error' => 'Invalid username or password')),
                    array('Content-Type: application/json', 'HTTP/1.1 401 Unauthorized')
                );
            }
            
            $payload = [
                'user_id' => $user['id'],
                'username' => $user['username']
            ];

            $expiration = 60; // 1 hora
            
            $token = createToken($payload, 60);

            $response = [
                "userId" => $user["id"],
                "username" => $user["username"],
                "token" => $token
            ];

            $this->sendOutput(
                json_encode($response),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }
    }

    public function getAllUsers(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) !== 'GET') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }
        
        $users = $this->userDAO->getAllUsers();

        if (!$users) {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $this->sendOutput(
            json_encode($users),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function getUserById(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        
        if (strtoupper($requestMethod) !== 'GET') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if($data != null){

            $id = $data["id"];

            $user = $this->userDAO->getUserById($id);
            
            if (!$user) {
                http_response_code(404);
                $menssage = ['error' => 'User not found'];
            }

            $this->sendOutput(
                json_encode($user ?: $menssage),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK') 
            );
        }

    }

    public function createUser(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) !== 'POST') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if($data != null){

            $user = UserModel::getInstance();
            
            $user->setUserName($data['username']);
            $user->setPassword(md5($data['password']));
            $user->setName($data['name']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);    
            $user->setPhone($data['phone']);
            $user->setBirthDate($data['birthDate']);
    
            $this->userDAO->create($user);

            http_response_code(201);

            $this->sendOutput(
                json_encode(['message' => 'User created successfully']),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }
    }

    public function updateUser(){
        
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) !== 'PUT') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if($data != null){

            $user = $this->userDAO->getUserById($data["id"]);
    
            if (!$user) {
                http_response_code(404);
                $this->sendOutput(
                    json_encode(array('error' => 'User not found')),
                    array('Content-Type: application/json', 'HTTP/1.1 404 Not found')
                );
            } 
            
            $user = UserModel::getInstance();

            $user->setUserName($data['username']);
            $user->setPassword(md5($data['password']));
            $user->setName($data['name']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);    
            $user->setPhone($data['phone']);
            $user->setBirthDate($data['birthDate']);

            $this->userDAO->update($user);
            
            $this->sendOutput(
                json_encode(['message' => 'User updated successfully']),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }
    }

    public function deleteUser(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) !== 'PUT') {
            $this->sendOutput(
                json_encode(array('error' => 'Invalid request method')),
                array('Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed')
            );
        }

        
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        
        if($data != null){
            
            $user = $this->userDAO->getUserById($data['id']);

            if (!$user) {
                http_response_code(404);
                $this->sendOutput(
                    json_encode(array('error' => 'User not found')),
                    array('Content-Type: application/json', 'HTTP/1.1 404 Not found')
                );
            } 
            
            $this->userDAO->softDelete($user["id"]);
 
            $this->sendOutput(
                json_encode(['message' => 'User deleted successfully']),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }
    }
}