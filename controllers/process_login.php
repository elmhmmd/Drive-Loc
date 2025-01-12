<?php
session_start();
require_once '../classes/database.php';
require_once '../classes/user.php';
require_once '../classes/role.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: ../pages/login.php");
        exit();
    }

    $user = new User();
    $loggedInUser = $user->Login($email, $password);
    if ($loggedInUser) {
          $_SESSION['user_id'] = $loggedInUser['user_id'];
          $_SESSION['username'] = $loggedInUser['username'];
           $role = new Role();
          $userRole = $role->getRoleById($loggedInUser['role_id']);
          if($userRole && $userRole['role_name'] === 'admin'){
            header("Location: ../pages/admin_dashboard.php");
          } else {
             header("Location: ../pages/user_page.php");
          }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: ../pages/login.php");
        exit();
    }
}
?>