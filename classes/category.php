<?php

class Category {
    private $category_id;
    private $category_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function filterByCategory($category_id) {
        $query = "SELECT v.* FROM vehicles v 
                 JOIN vehicle_categories vc ON v.vehicle_id = vc.vehicle_id 
                 WHERE vc.category_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($category_name) {
        $query = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$category_name]);
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM categories WHERE category_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function viewCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
