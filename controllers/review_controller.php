<?php
session_start();
require_once '../classes/review.php';
require_once '../classes/database.php';

$review = new Review();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'delete':
            $result = $review->DeleteReview($_POST['review_id']);
            if ($result) {
                $_SESSION['success'] = "Review deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete review";
            }
            break;
        // Add other cases as needed
    }
    header('Location: ../pages/Admin_Dashboard.php');
    exit();
}
?>