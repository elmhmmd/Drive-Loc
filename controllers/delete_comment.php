<?php
require_once '../classes/database.php';
require_once '../classes/comment.php';

$commentObj = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $commentId = $_POST['comment_id'];
     $commentObj->DeleteComment($commentId);
    }

 header('Location: admin_blog_dashboard.php');
exit();
?>