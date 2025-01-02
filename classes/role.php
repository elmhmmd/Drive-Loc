<?php

class Role {
    private $role_id;
    private $role_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createRole($role_name) {
        $query = "INSERT INTO roles (role_name) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$role_name]);
    }

    public function getRoles() {
        $query = "SELECT * FROM roles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id) {
        $query = "SELECT * FROM roles WHERE role_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRole($id, $role_name) {
        $query = "UPDATE roles SET role_name = ? WHERE role_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$role_name, $id]);
    }

    public function deleteRole($id) {
        $query = "DELETE FROM roles WHERE role_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function hasPermission($role_id, $permission) {
        $query = "SELECT COUNT(*) FROM role_permissions 
                 WHERE role_id = ? AND permission = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$role_id, $permission]);
        return $stmt->fetchColumn() > 0;
    }
}
