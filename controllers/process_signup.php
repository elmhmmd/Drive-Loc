<?php
session_start();
require_once '../classes/database.php';
require_once '../classes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: pages/signup.php");
        exit();
    }

    // Password confirmation check
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: pages/signup.php");
        exit();
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: pages/signup.php");
        exit();
    }

    $user = new User();
    $signupData = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'role_id' => 2  // Client role from roles table
    ];

    if ($user->Signup($signupData)) {
        $_SESSION['success'] = "Account created successfully! Please login.";
        header("Location: pages/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Email already exists or registration failed";
        header("Location: pages/signup.php");
        exit();
    }
}
