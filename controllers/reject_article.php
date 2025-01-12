<?php
require_once '../classes/database.php';
require_once '../classes/article.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];
    $articleObj = new Article();
    if ($articleObj->updateStatus($article_id, 'Rejected')) {
        header('Location: admin_blog_dashboard.php');
        exit(); // Added exit()
    } else {
        // Handle error if update fails (optional)
        header('Location: admin_blog_dashboard.php?error=update_failed');
        exit(); // Added exit() for error case
    }
} else {
    // Handle cases where the request is not valid
    header('Location: admin_blog_dashboard.php?error=invalid_request');
    exit(); // Added exit() for invalid request
}
?>