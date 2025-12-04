<?php
require_once 'includes/config.php';
require_once 'includes/Router.php';

$router = new Router();

// Define Routes
$router->add('home', 'PageController', 'home');
$router->add('login', 'AuthController', 'login');
$router->add('logout', 'AuthController', 'logout');
$router->add('dashboard', 'AppointmentController', 'dashboard');
$router->add('agenda', 'AppointmentController', 'agenda');
$router->add('marcacoes', 'PageController', 'booking');
$router->add('sobre', 'PageController', 'about');
$router->add('contacto', 'PageController', 'contact');
// Add more routes as needed

// Get route from URL or default to home
$route = $_GET['route'] ?? 'home';

$router->dispatch($route);
?>