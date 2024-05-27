<?php
include 'includes/db.php';
session_start();
$sql = "SELECT * FROM animes";
$stmt = $pdo->query($sql);
$animes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime List</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/header.jpg">
</head>
<body id="main">
    <div class="page-head">
        <h1>Anime Site</h1>
    </div>
    <nav>
        <a href="index.php"><button type="submit">Home</button></a>
    </nav>
    <div class="anime-container">
        <?php foreach ($animes as $anime): ?>
            <a href="anime_details.php?id=<?= $anime['id'] ?>" class="anime-item">
                <img src="<?= htmlspecialchars($anime['image']) ?>" alt="<?= htmlspecialchars($anime['title']) ?>">
                <h2><?= htmlspecialchars($anime['title']) ?></h2>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
