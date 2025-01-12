<?php
require_once '../classes/database.php';
require_once '../classes/theme.php';

$themeObj = new Theme();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme_id'])) {
    $themeId = $_POST['theme_id'];
    $themeObj->deleteThemes($themeId);
}

header('Location: ../pages/admin_blog_dashboard.php');
exit();
?>