<?php

namespace App\Models;

use DateTime;
use App\Core\Database;

class User
{
    public int $id;
    public string $username;
    public string $password;
    public string $email;
    private array $role;
    private DateTime $created_at;
    private Datetime $updated_at;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function login()
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $this->username);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($this->password, $user['password'])) {
                session_start();
                $_SESSION['user']['profil'] = $user->username . $user->role;
                header('Location: /Articles');
            } else {
                $_SESSION['error'] = "Utilisateur inexistant ou mot de passe incorrect";
                header('Location: /Login');
            }
        } else {
            $_SESSION['error'] = "Utilisateur inexistant ou mot de passe incorrect";
            header('Location: /Login');
        }
        //header('Location: /Articles');
    }

    public function lougout()
    {
        session_start();
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /Articles');
    }

    public static function where($column, $value)
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE $column = :$column";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":$column", $value);
        $stmt->execute();
        $user = $stmt->fetchObject('App\Core\User');
        return $user;
    }

    public function save()
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO users (username, password, email, role, created_at, updated_at) VALUES (:username, :password, :email, :role, :created_at, :updated_at)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $this->username);
        $stmt->bindValue(':password', password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':role', $this->role);
        $stmt->bindValue(':created_at', $this->created_at);
        $stmt->bindValue(':updated_at', $this->updated_at);
        $stmt->execute();
    }

    public static function first()
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $user = $stmt->fetchObject('App\Core\User');
        return $user; 
    }
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}