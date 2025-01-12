<?php

class Favorite {
    private $favorite_id;
    private $user_id;
    private $article_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getFavoriteId() {
        return $this->favorite_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getArticleId() {
        return $this->article_id;
    }

    // Setters
    public function setFavoriteId($favorite_id) {
        $this->favorite_id = $favorite_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setArticleId($article_id) {
        $this->article_id = $article_id;
    }

    public function addFavorite($user_id, $article_id) {
        $query = "INSERT INTO favorites (user_id, article_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
          try {
             return $stmt->execute([$user_id, $article_id]);
        } catch(PDOException $e) {
               return false; // Return false on duplicate key error
           }
    }

       public function removeFavorite($user_id, $article_id) {
        $query = "DELETE FROM favorites WHERE user_id = ? AND article_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$user_id, $article_id]);
    }
    
    public function viewFavorites($user_id) {
        $query = "SELECT f.*, a.article_title, a.image
                  FROM favorites f
                  JOIN articles a ON f.article_id = a.article_id
                  WHERE f.user_id = ?";
         $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
      public function isFavorited($user_id, $article_id) {
        $query = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND article_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $article_id]);
        return $stmt->fetchColumn() > 0;
    }
}