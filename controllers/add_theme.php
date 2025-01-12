<?php
require_once '../classes/database.php';
require_once '../classes/theme.php';

$themeObj = new Theme();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme_name'])) {
    $themeNames = $_POST['theme_name'];
      foreach ($themeNames as $themeName){
         $themeObj->addThemes($themeName);
      }
}

header('Location: ../pages/admin_blog_dashboard.php');
exit();
?>