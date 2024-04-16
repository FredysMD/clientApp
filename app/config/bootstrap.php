<?php 
define("PROJECT_ROOT_PATH", __DIR__ . "/../");  
// include main configuration file 
require_once PROJECT_ROOT_PATH . "./index.php";    
// include main jwt class  
require_once PROJECT_ROOT_PATH . "/utils/JWT.php";
require_once PROJECT_ROOT_PATH . "/utils/helper.php"; 
// include the base controller file   
require_once PROJECT_ROOT_PATH . "/controllers/BaseController.php";
// include the base models file 
require_once PROJECT_ROOT_PATH . "/models/UserModel.php";
require_once PROJECT_ROOT_PATH . "/models/Database.php";
// include the use DAO file   
require_once PROJECT_ROOT_PATH . "/DAO/UserDAO.php";
?>   