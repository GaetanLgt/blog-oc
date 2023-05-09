<?php

namespace App\Controller;

use App\Models\User;
use App\Core\Request;
use App\Core\Controller;
use App\Core\Application;


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

    public function handleLogin(Request $request)
    {
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        $user = User::where('email', $email);
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
        Application::$app->response->redirect('/');
    }

    public function handleRegister(Request $request)
    {
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        $passwordConfirm = $request->getPost('passwordConfirm');
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required';
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
        if (User::where('email', $email)) {
            $errors['email'] = 'Email already exists';
        }
        if (!empty($errors)) {
            return $this->renderView('Auth/register.html.twig', [
                'errors' => $errors
            ]);
        }
        $user = new User();
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();
        $_SESSION['user'] = $user->id;
        Application::$app->response->redirect('/');
    }

    public function logout()
    {
        unset($_SESSION['user']);
        Application::$app->response->redirect('/');
    }

}