<?php

class User {
    private $user_id;
    private $username;
    private $email;
    private $password;
    private $role_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getUserId() {
        return $this->user_id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
     public function getRoleId() {
        return $this->role_id;
    }

    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
       public function setRoleId($role_id) {
        $this->role_id = $role_id;
    }


    public function Signup($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        try {
            return $stmt->execute([$data['username'], $data['email'], $hashedPassword, $data['role_id']]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Unique constraint violation
                return false;
            }
            throw $e;
        }
    }

    public function Login($email, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
       public function getAuthor($user_id) {
        $query = "SELECT username FROM users WHERE user_id = ?";
         $stmt = $this->db->prepare($query);
           $stmt->execute([$user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
          return $result ? htmlspecialchars($result['username']) : 'Unknown';
        }
}
?>