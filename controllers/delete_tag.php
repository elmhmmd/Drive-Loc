<?php
require_once '../classes/database.php';
require_once '../classes/tag.php';

$tagObj = new Tag();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_id'])) {
    $tagId = $_POST['tag_id'];
    $tagObj->deleteTags($tagId);
}

header('Location: ../pages/admin_blog_dashboard.php');
exit();
?>