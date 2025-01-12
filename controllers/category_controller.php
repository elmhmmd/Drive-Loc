<?php
session_start();
require_once '../classes/category.php';
require_once '../classes/database.php';

$category = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'add_multiple':
            $success = true;
            foreach ($_POST['categories'] as $categoryData) {
                $result = $category->addCategory($categoryData['category_name']);
                if (!$result) {
                    $success = false;
                }
            }
            if ($success) {
                $_SESSION['success'] = "Categories added successfully";
            } else {
                $_SESSION['error'] = "Failed to add some categories";
            }
            break;

        case 'delete':
            $result = $category->deleteCategory($_POST['category_id']);
            if ($result) {
                $_SESSION['success'] = "Category deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete category";
            }
            break;
    }
    header('Location: ../pages/Admin_Dashboard.php');
    exit();
}