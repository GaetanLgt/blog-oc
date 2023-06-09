<?php
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\CommentController;
use App\Controller\ProfileController;
use App\Controller\ArticlesController;
use App\Controller\ContactController;

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

$app->router->get('/comment', [CommentController::class, 'add']);
$app->router->post('/comment', [CommentController::class, 'add']);

$app->router->get('/supprimercomment', [CommentController::class, 'supprimer']);

$app->router->get('/modifiercomment', [CommentController::class, 'modifier']);

$app->router->get('/publishComment', [CommentController::class, 'publish']);
$app->router->get('/unpublishComment', [CommentController::class, 'unpublish']);

$app->router->get('/publier', [ArticlesController::class, 'publish']);

$app->router->get('/depublier', [ArticlesController::class, 'unpublish']);

$app->router->get('/profil', [ProfileController::class, 'index']);

$app->router->get('/contact', [ContactController::class, 'index']);
$app->router->post('/contact', [ContactController::class, 'index']);
