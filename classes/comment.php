<?php

    class Comment {
        private $comment_id;
        private $db;
        private $comment_content;
        private $article_id;
        private $user_id;

        public function __construct() {
            $this->db = Database::getInstance()->getConnection();
        }

        // Getters
        public function getCommentId() {
            return $this->comment_id;
        }

        public function getCommentContent() {
            return $this->comment_content;
        }

        public function getArticleId() {
            return $this->article_id;
        }

        public function getUserId() {
            return $this->user_id;
        }

        // Setters
        public function setCommentId($comment_id) {
            $this->comment_id = $comment_id;
        }

        public function setCommentContent($comment_content) {
            $this->comment_content = $comment_content;
        }

        public function setArticleId($article_id) {
            $this->article_id = $article_id;
        }

        public function setUserId($user_id) {
            $this->user_id = $user_id;
        }

         public function ViewArticleComments ($article_id) {
            $query = "SELECT * FROM comments WHERE article_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$article_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

         public function addComment($comment_content, $article_id, $user_id) {
            $query = "INSERT INTO comments (comment_content, article_id, user_id) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$comment_content, $article_id, $user_id]);
        }

        public function ViewComments() {
            $query = "SELECT * FROM comments";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
         public function getArticleTitleByCommentId($article_id) {
          $query = "SELECT article_title FROM articles WHERE article_id = ?";
          $stmt = $this->db->prepare($query);
          $stmt->execute([$article_id]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
           return $result['article_title'] ?? 'Unknown';
        }

        public function DeleteComment($comment_id) {
            $query = "DELETE FROM comments WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$comment_id]);
        }
          public function modifyComment($comment_id, $comment_content) {
            $query = "UPDATE comments SET comment_content = ? WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$comment_content, $comment_id]);
        }
         public function getCommentById($comment_id) {
            $query = "SELECT * FROM comments WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$comment_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>