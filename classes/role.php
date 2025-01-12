<?php

class Role {
    private $role_id;
    private $role_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getRoleId() {
        return $this->role_id;
    }

    public function getRoleName() {
        return $this->role_name;
    }

    // Setters
    public function setRoleId($role_id) {
        $this->role_id = $role_id;
    }

    public function setRoleName($role_name) {
        $this->role_name = $role_name;
    }

    public function getRoleById($id) {
        $query = "SELECT * FROM roles WHERE role_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}