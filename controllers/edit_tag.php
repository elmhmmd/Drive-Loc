<?php
require_once '../classes/database.php';
require_once '../classes/tag.php';

    $tagObj = new Tag();

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_id']) && isset($_POST['tag_name'])) {
          $tagId = $_POST['tag_id'];
        $tagName = $_POST['tag_name'];
        $tagObj->modifyTags($tagId,$tagName);

}
    header('Location: ../pages/admin_blog_dashboard.php');
    exit();
?>