<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $role_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function Signup($data) {
        // Validate email
        if ($this->emailExists($data['email'])) {
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $hashedPassword,
            $data['role_id'] ?? 2  // Default role_id for regular users
        ]);
    }

    public function Login($email, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            return true;
        }
        return false;
    }

    private function emailExists($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function getUserById($id) {
        $query = "SELECT id, username, email, role_id FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data) {
        $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $id
        ]);
    }
}
