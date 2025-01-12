<?php
require_once '../classes/database.php';
require_once '../classes/article.php';

$articleObj = new Article();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id']) && isset($_POST['status'])) {
    $articleId = $_POST['article_id'];
    $status = $_POST['status'];

    $articleObj->updateArticleStatus($articleId, $status);
}

header('Location: admin_blog_dashboard.php');
exit();
?>