<?php

class Theme {
    private $theme_id;
    private $theme_name;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getter
    public function getThemeId() {
        return $this->theme_id;
    }

    public function getThemeName() {
        return $this->theme_name;
    }

    // Setter
    public function setThemeId($theme_id) {
        $this->theme_id = $theme_id;
    }

    public function setThemeName($theme_name) {
        $this->theme_name = $theme_name;
    }


    public function viewThemes() {
        $query = "SELECT * FROM themes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function fetchThemeName($theme_id) {
        $query = "SELECT theme_name FROM themes WHERE theme_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$theme_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
         return $result ? htmlspecialchars($result['theme_name']) : 'Unknown Theme';
    }

    public function addThemes($theme_name) {
        $query = "INSERT INTO themes (theme_name) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$theme_name]);
    }

    public function deleteThemes($id) {
        $query = "DELETE FROM themes WHERE theme_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function modifyThemes($id, $theme_name ) {
        $query = "UPDATE themes SET theme_name=? WHERE theme_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$theme_name,$id]);
    }
}
?>