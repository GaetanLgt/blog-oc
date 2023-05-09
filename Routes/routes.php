<?php
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\CommentController;
use App\Controller\ArticlesController;

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

$app->router->get('/supprimer', [CommentController::class, 'supprimer']);
