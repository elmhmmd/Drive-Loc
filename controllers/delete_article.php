<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];
    $article->deleteArticle($article_id);
    header('Location: admin_blog_dashboard.php'); 
    exit();
} else {
    header('Location: admin_blog_dashboard.php'); 
    exit();
}

?>