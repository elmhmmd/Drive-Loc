<?php
require_once '../classes/database.php';
require_once '../classes/tag.php';

$tagObj = new Tag();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_name'])) {
    $tagNames = $_POST['tag_name'];
      foreach ($tagNames as $tagName){
         $tagObj->addTags($tagName);
      }
}

header('Location: ../Pages/admin_blog_dashboard.php');
exit();
?>