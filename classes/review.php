<?php

class Review {
    private $review_id;
    private $user_id;
    private $content;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addReview($data) {
        $query = "INSERT INTO reviews (user_id, content) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['user_id'],
            $data['content']
        ]);
    }

    public function DeleteReview($id) {
        $query = "DELETE FROM reviews WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function viewReview($user_id = null) {
        $query = "SELECT r.*, u.username 
                 FROM reviews r 
                 JOIN users u ON r.user_id = u.id";
        
        if ($user_id) {
            $query .= " WHERE r.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifyReview($id, $content) {
        $query = "UPDATE reviews SET content = ? WHERE review_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$content, $id]);
    }
}
