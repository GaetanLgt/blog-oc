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


$app = new Application();

$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/article', [ArticlesController::class, 'show']);

$app->router->get('/articles', [ArticlesController::class, 'index']);
$app->router->get('/ajouter', [ArticlesController::class, 'add']);
$app->router->post('/ajouter', [ArticlesController::class, 'add']);
$app->router->get('/supprimer', [ArticlesController::class, 'supprimer']);
$app->run();
