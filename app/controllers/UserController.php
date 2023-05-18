<?php


class UserController extends BaseController{
    
    private $userDAO;
    private $resquestMethod;

    public function login(){

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->userDAO = new UserDAO();

        if (strtoupper($this->requestMethod) == 'POST') {

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
                
                $jwt = JWT::getInstance('F-JaNdRgUkXp2r5u8x/A?D(G+KbPeShVmYq3t6w9y$B&E)H@McQfTjWnZr4u7x!A');

                $payload = [
                    'user_id' => $user['id'],
                    'username' => $user['username']
                ];

                $expiration = time() + (60 * 60); // 1 hora
                $token = $jwt->generateToken($payload, $expiration);

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
    }

    public function getAllUsers(){

        $this->resquestMethod = $_SERVER["REQUEST_METHOD"];
        $this->userDAO = new UserDAO();

        if (strtoupper($this->resquestMethod) == 'GET'){
            
            $users = $this->userDAO->getAllUsers();
    
            if ($users) {
                $this->sendOutput(
                    json_encode($users),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        } 
    }

    public function getUserById($id){

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if($data != null){
            $id = $data["id"];

            $user = $this->userDAO->getUserById($id);
            
            if (!$user) {
                http_response_code(404);
                $menssage = ['error' => 'User not found'];
            }

            return json_encode($user || $menssage);
        }

    }

    public function createUser($data){

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        $user = new UserModel();
        
        $user->setName($data['name']);
        $user->setEmail($data['email']);    

        $this->userDAO->createUser($user);

        http_response_code(201);
        return json_encode(['message' => 'User created successfully']);
    }

    public function updateUser($id, $data){
        
        $user = $this->userDAO->getUserById($id);

        if ($user) {
            $user->setName($data['name']);
            $user->setEmail($data['email']);

            $this->userDAO->updateUser($user);

            return json_encode(['message' => 'User updated successfully']);
        } else {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }
    }

    public function deleteUser($id){

        $user = $this->userDAO->getUserById($id);

        if ($user) {
            $this->userDAO->deleteUser($id);

            return json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }
    }
}