<?php

namespace App\Models;

use DateTime;
use App\Core\Database;
use App\Core\Application;
use App\Repository\UserRepository;

class User
{
    public int $id;
    public string $email;
    public string $password;
    public string $username;
    public string $role;
    private $created_at;
    private $updated_at;

    public function __construct()
    {
        $this->created_at = new Datetime(date('Y-m-d H:i:s'));
        $this->updated_at = new Datetime(date('Y-m-d H:i:s'));
    }

    public function login(): void
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($this->password, $user['password'])) {
                session_start();
                Application::$session->set('username', $user->username);
                Application::$session->set('role', $user->role);
                Application::$session->set('user', $user);
                Application::$app->response->redirect('/articles');
            } else {
                $_SESSION['error'] = "Utilisateur inexistant ou mot de passe incorrect";
                Application::$app->response->redirect('/login');
            }
        } else {
            $_SESSION['error'] = "Utilisateur inexistant ou mot de passe incorrect";
            Application::$app->response->redirect('/login');
        }
    }

    public function lougout(): void
    {
        session_start();
        unset($_SESSION['user']);
        session_destroy();
        Application::$app->response->redirect('/articles');
    }

    public function save(): void
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO user (username, password, email, role, created_at, updated_at) VALUES (:username, :password, :email, :role, :created_at, :updated_at)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $_POST['username']);
        $stmt->bindValue(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
        $stmt->bindValue(':email', $_POST['email']);
        $stmt->bindValue(':role', 'user');
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $stmt->execute();
        $user = $stmt->fetch();
        $user_id = $db->lastInsertId();
        $userRepository = new UserRepository;
        $user = $userRepository->findOne($user_id);
        Application::$session->set('user_id', $user->id);
        Application::$session->set('username', $user->username);
        Application::$session->set('role', $user->role);
        Application::$app->response->redirect('/login');
    }
    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of role
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): Datetime
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt(): Datetime
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
