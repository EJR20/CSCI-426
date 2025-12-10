<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitQuest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- If you have a CSS file, link it here, e.g.: -->
    <!-- <link rel="stylesheet" href="css/stylefit.css"> -->
</head>
<body>
<header>
    <h1>@FitQuest</h1>
    <nav>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="index.php">Dashboard</a> |
            <a href="workouts.php">Workouts</a> |
            <a href="progress_new.php">Log Progress</a> |
            <a href="progress_list.php">Progress History</a> |
            <a href="analytics.php">Analytics</a> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="register.php">Sign Up</a>
        <?php endif; ?>
    </nav>
    <hr>
</header>
<main>
