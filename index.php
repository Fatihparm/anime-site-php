<?php
include 'includes/db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/header.jpg">
    <title>Anime Site</title>

</head>
<body id="main">
    <header>
        <div class="page-head">
            <h1>Anime Site</h1>
        </div>
        <nav>
            <a href="index.php"><button type="submit">Home</button></a>
            <a href="anime_list.php"><button type="submit">Anime List</button></a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><button type="submit">Profile</button></a>
                <a href="logout.php"><button type="submit">Logout</button></a>
            <?php else: ?>
                <a href="login.php"><button type="submit">Login</button></a>
                <a href="register.php"><button type="submit">Register</button></a>
            <?php endif; ?>
        </nav>
        <div class="github-container">
            <a href="https://github.com/Fatihparm/anime-site-php"><img src="img/github.png" alt="GitHub"></a>
        </div>
    </header>

</body>
</html>
