<?php
session_start();
require_once '../classes/database.php';
require_once '../classes/article.php';
require_once '../classes/theme.php';
require_once '../classes/tag.php';

if (!isset($_SESSION['user_id'])) {
       $_SESSION['error'] = "You must be logged in to publish an article.";
        header('Location: ../pages/login.php');
        exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_title = $_POST['title'];
    $article_content = $_POST['content'];
    $theme_id = isset($_POST['theme']) ? (int)$_POST['theme'] : null;
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $user_id = $_SESSION['user_id'];

    $image = 'default.png';
      if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
      $uploadDir = '../assets/images/';
      $filename_base = uniqid() . '_' . pathinfo($_FILES['images']['name'][0], PATHINFO_FILENAME);
      $imageFileType = strtolower(pathinfo($_FILES['images']['name'][0], PATHINFO_EXTENSION));
          $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
      if (in_array($imageFileType, $allowedTypes)) {
          $image = $filename_base . '.' . $imageFileType;
          $uploadFile = $uploadDir . $image;
        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $uploadFile)) {
              // Image uploaded successfully
          } else {
            $_SESSION['error'] = "Failed to move uploaded image.";
            header('Location: ../pages/add_article.php');
            exit();
          }
      } else {
           $_SESSION['error'] = "Invalid file type.";
            header('Location: ../pages/add_article.php');
            exit();
          }
    }
    
     if ($theme_id === null) {
            $_SESSION['error'] = "Please select a theme.";
            header('Location: ../pages/add_article.php');
            exit();
    }
     $themeObj = new Theme();
     $themes = $themeObj->viewThemes();
    $themeExists = false;
     foreach($themes as $theme) {
          if ($theme['theme_id'] === $theme_id) {
            $themeExists = true;
            break;
         }
      }
    if(!$themeExists){
         $_SESSION['error'] = "Please select a valid theme.";
         header('Location: ../pages/add_article.php');
          exit();
    }

    $article = new Article();
    $article_id = $article->publishArticle($article_title, $article_content, $image, $user_id, $theme_id);

    if ($article_id) {
        if (!empty($tags)) {
            $tagNames = array_map('trim', explode(',', $tags));
            $tagObj = new Tag();
             foreach ($tagNames as $tagName) {
                $tag_id = null;
                 $allTags = $tagObj->viewTags();
                foreach($allTags as $tag) {
                    if($tag['tag_name'] === $tagName) {
                       $tag_id = $tag['tag_id'];
                        break;
                     }
                 }
                if ($tag_id !== null) {
                    if(!$article->addArticleTags($article_id, $tag_id)){
                        $_SESSION['error'] = "Failed to add tags to the article";
                        header('Location: ../pages/add_article.php');
                        exit();
                     }
                } else {
                      $_SESSION['error'] = "One or more tags are invalid, please add the tags in the admin dashboard";
                      header('Location: ../pages/add_article.php');
                       exit();
                 }
            }
        }
         $_SESSION['success'] = "Article published successfully!";
    } else {
         $_SESSION['error'] = "Failed to publish article.";
    }

    header('Location: ../pages/add_article.php');
    exit();
}
?>