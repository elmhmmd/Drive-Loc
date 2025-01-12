<?php

class Article {
    private $article_id;
    private $article_title;
    private $article_content;
    private $status;
    private $image;
    private $user_id;
    private $theme_id;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    //Getters

    public function getArticleId() {
        return $this->article_id;
    }

    public function getArticleTitle() {
        return $this->article_title;
    }

    public function getArticleContent() {
        return $this->article_content;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getImage() {
        return $this->image;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getThemeId() {
        return $this->theme_id;
    }

    //Setters

    public function setArticleId($article_id) {
        $this->article_id = $article_id;
    }

    public function setArticleTitle($article_title) {
        $this->article_title = $article_title;
    }

    public function setArticleContent($article_content) {
        $this->article_content = $article_content;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setThemeId($theme_id) {
        $this->theme_id = $theme_id;
    }
    
    public function filterByTheme($theme_id) {
        $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id WHERE theme_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$theme_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
     public function searchArticles($keyword) {
         $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id WHERE article_title LIKE ? ";
         $stmt = $this->db->prepare($query);
          $stmt->execute(["%$keyword%"]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchArticlesByTheme($keyword, $theme_id) {
        $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id WHERE (article_title LIKE ? OR article_content LIKE ?) AND theme_id = ?";
        $stmt = $this->db->prepare($query);
         $stmt->execute(["%$keyword%", "%$keyword%", $theme_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
        public function getArticlesByTag($tag_name) {
            $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id
                      JOIN article_tags at ON a.article_id = at.article_id
                      JOIN tags t ON at.tag_id = t.tag_id
                      WHERE t.tag_name = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tag_name]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function addArticleTags($article_id, $tag_id) {
            $query = "INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$article_id, $tag_id]);
        }

    public function publishArticle($article_title, $article_content, $image, $user_id, $theme_id) {
        $query = "INSERT INTO articles (article_title, article_content, image, user_id, theme_id, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$article_title, $article_content, $image, $user_id, $theme_id]);
         return $this->db->lastInsertId();
    }

    public function viewArticles() {
        $query = "SELECT * FROM articles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

      public function getArticlesWithAuthors($limit = null, $offset = null) {
        $query = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id ORDER BY a.article_id DESC";
           if ($limit !== null && $offset !== null) {
                 $query .= " LIMIT :limit OFFSET :offset";
            }
        $stmt = $this->db->prepare($query);
        if ($limit !== null && $offset !== null) {
                 $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getArticleTags($article_id) {
         $query = "SELECT t.tag_name FROM article_tags at JOIN tags t ON at.tag_id = t.tag_id WHERE at.article_id = ?";
          $stmt = $this->db->prepare($query);
          $stmt->execute([$article_id]);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteArticle($id) {
        $query = "DELETE FROM articles where article_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
      public function fetchArticleAuthor($user_id) {
           $query = "SELECT username FROM users WHERE user_id = ?";
           $stmt = $this->db->prepare($query);
           $stmt->execute([$user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
          return $result ? htmlspecialchars($result['username']) : 'Unknown';
        }

    public function getArticleById($article_id) {
        $query = "SELECT * FROM articles WHERE article_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$article_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function updateArticle($article_id, $article_title, $article_content, $image, $theme_id) {
        $query = "UPDATE articles SET article_title = ?, article_content = ?, image = ?, theme_id = ? WHERE article_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$article_title, $article_content, $image, $theme_id, $article_id]);
    }

    public function updateStatus($article_id, $status) {
        $query = "UPDATE articles SET status = ? WHERE article_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, $article_id]);
    }
      public function getTotalArticlesCount() {
            $query = "SELECT COUNT(*) FROM articles";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
}
?>