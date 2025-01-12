<?php

class Tag {
    private $tag_id;
    private $tag_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getTagId() {
        return $this->tag_id;
    }

    public function getTagName() {
        return $this->tag_name;
    }

    // Setters
    public function setTagId($tag_id) {
        $this->tag_id = $tag_id;
    }

    public function setTagName($tag_name) {
        $this->tag_name = $tag_name;
    }

    public function viewTags() {
        $query = "SELECT * from tags";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addTags($tag_name) {
        $query = "INSERT INTO tags (tag_name) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$tag_name]);
    }

    public function deleteTags ($id) {
        $query = "DELETE FROM tags WHERE tag_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    public function modifyTags($id, $tag_name ) {
        $query = "UPDATE tags SET tag_name=? WHERE tag_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$tag_name,$id]);
    }
}