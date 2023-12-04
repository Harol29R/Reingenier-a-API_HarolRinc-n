<?php
require_once "controller/routesController.php";
require_once "controller/userController.php";
require_once "controller/loginController.php";
require_once "model/userModel.php";

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');

$rutasArray = explode("/",$_SERVER['REQUEST_URI']) ;
$endPoint = (array_filter($rutasArray)[2]);

$routes = new RoutesController();
$routes->index();

?>