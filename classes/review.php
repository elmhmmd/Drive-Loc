<?php

class Review {
    private $review_id;
    private $user_id;
    private $content;
    private $db;
    private $rating;
    private $is_deleted = 0;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Getters
    public function getReviewId() {
        return $this->review_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getRating() {
        return $this->rating;
    }
    public function getIsDeleted() {
        return $this->is_deleted;
    }

    // Setters
    public function setReviewId($review_id) {
        $this->review_id = $review_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }
      public function setIsDeleted($is_deleted) {
        $this->is_deleted = $is_deleted;
    }

    public function addReview($data) {
        $query = "INSERT INTO reviews (user_id, content, rating, is_deleted) VALUES (?, ?, ?, 0)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['user_id'],
            $data['content'],
            $data['rating']
        ]);
    }

    public function DeleteReview($id) {
        $query = "UPDATE reviews SET is_deleted = 1 WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
      public function realDeleteReview($id) {
        $query = "DELETE FROM reviews WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
      public function  viewUserReviews($user_id, $limit = null, $offset = null) {
          $query = "SELECT r.*, u.username 
                 FROM reviews r 
                 JOIN users u ON r.user_id = u.user_id WHERE r.user_id = ? AND is_deleted = 0";
            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT ?, ?";
            }
          $stmt = $this->db->prepare($query);
                if ($limit !== null && $offset !== null) {
                    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
                     $stmt->bindValue(2, (int) $limit, PDO::PARAM_INT);
                    $stmt->bindValue(3, (int) $offset, PDO::PARAM_INT);
                     $stmt->execute();
                } else {
                     $stmt->execute([$user_id]);
               }
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewReview($user_id = null) {
        $query = "SELECT r.*, u.username 
                 FROM reviews r 
                 JOIN users u ON r.user_id = u.user_id WHERE is_deleted = 0";
        
        if ($user_id) {
            $query .= " AND r.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifyReview($id, $content, $rating) {
        $query = "UPDATE reviews SET content = ?, rating = ? WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$content, $rating, $id]);
    }
     public function getReviewById($id)
    {
        $query = "SELECT * FROM reviews WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getTotalReviewsCount($user_id) {
          $query = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND is_deleted = 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
            return $stmt->fetchColumn();
        }

}