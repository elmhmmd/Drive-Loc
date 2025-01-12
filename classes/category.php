<?php

class Category {
    private $category_id;
    private $category_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    //Getters

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getCategoryName() {
        return $this->category_name;
    }

    //Setters
    
    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

    public function setCategoryName($category_name) {
        $this->category_name = $category_name;
    }

    public function filterByCategory($category_id) {
        $query = "SELECT v.* FROM vehicles v 
                 JOIN categories c ON v.category_id = c.category_id 
                 WHERE c.category_id = ?";
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
    
    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE category_id = ?";
         $stmt = $this->db->prepare($query);
         $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}