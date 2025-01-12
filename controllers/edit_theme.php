<?php
require_once '../classes/database.php';
require_once '../classes/theme.php';

$themeObj = new Theme();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme_id']) && isset($_POST['theme_name'])) {
    $themeId = $_POST['theme_id'];
    $themeName = $_POST['theme_name'];
    $themeObj->modifyThemes($themeId, $themeName);
}

header('Location: ../pages/admin_blog_dashboard.php');
exit();
?>