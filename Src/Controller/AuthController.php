<?php

namespace App\Controller;

use App\Models\User;
use App\Core\Request;
use App\Core\Controller;
use App\Core\Application;
use App\Core\Response;
use App\Repository\UserRepository;

class AuthController extends Controller
{
    public function login()
    {
        return $this->renderView('Auth/login.html.twig');
    }

    public function register()
    {
        return $this->renderView('Auth/register.html.twig');
    }

    public function handleLogin()
    {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = UserRepository::where('email', $email);
        if (!$user) {
            return $this->renderView('Auth/login.html.twig', [
                'errors' => ['email' => 'User does not exist']
            ]);
        }
        if (!password_verify($password, $user->password)) {
            return $this->renderView('Auth/login.html.twig', [
                'errors' => ['password' => 'Password is incorrect']
            ]);
        }
        $_SESSION['user'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        Application::$app->response->redirect('/');
    }

    public function handleRegister()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $passwordConfirm = trim($_POST['passwordConfirm']);
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        }
        if (empty($username)) {
            $errors['username'] = 'Username is required';
        }
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        if (empty($passwordConfirm)) {
            $errors['passwordConfirm'] = 'Password confirmation is required';
        }
        if ($password !== $passwordConfirm) {
            $errors['passwordConfirm'] = 'Passwords do not match';
        }
        if (UserRepository::where('email', $email)) {
            $errors['email'] = 'Email already exists';
        }
        if (!empty($errors)) {
            return $this->renderView('Auth/register.html.twig', [
                'errors' => $errors
            ]);
        }
        $user = new User();
        $user->email = $email;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();
        $_SESSION['user'] = $user->username;
        Application::$app->response->redirect('/');
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        Application::$app->response->redirect('/');
    }
}
