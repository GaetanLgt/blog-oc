<?php
/**
 * Affiche les erreurs en PHP
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

require_once dirname(dirname(__FILE__)) . '/Config/config.php';
use App\Controller\HomeController;
use App\Core\Application;
use App\Controller\ArticlesController;
use App\Controller\AuthController;

$app = new Application();

$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'handleLogin']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'handleRegister']);

$app->router->get('/article', [ArticlesController::class, 'show']);

$app->router->get('/articles', [ArticlesController::class, 'index']);

$app->router->get('/ajouter', [ArticlesController::class, 'add']);
$app->router->post('/ajouter', [ArticlesController::class, 'add']);

$app->router->get('/supprimer', [ArticlesController::class, 'supprimer']);

$app->router->get('/modifier', [ArticlesController::class, 'modifier']);
$app->router->post('/modifier', [ArticlesController::class, 'modifier']);

$app->run();
