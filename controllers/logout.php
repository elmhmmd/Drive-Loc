<?php
session_start();
session_destroy();
header('Location: ../pages/Homepage.php');
exit();
?>