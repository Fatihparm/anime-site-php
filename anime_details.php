<?php
include 'includes/db.php';
session_start();
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid anime ID.";
    exit;
}

$sql = "SELECT * FROM animes WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$anime = $stmt->fetch();

if (!$anime) {
    echo "Anime not found.";
    exit;
}

$sql_reviews = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.anime_id = ?";
$stmt_reviews = $pdo->prepare($sql_reviews);
$stmt_reviews->execute([$id]);
$reviews = $stmt_reviews->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($anime['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/header.jpg">
</head>
<body>
    <div class="container-anime">
        <h1><?= htmlspecialchars($anime['title']) ?></h1>
        <nav>
            <a href="index.php"><button type="submit">HOME</button></a>
        </nav>
        <br><br>
        <img src="<?= htmlspecialchars($anime['image']) ?>" alt="<?= htmlspecialchars($anime['title']) ?>">
        <p><?= htmlspecialchars($anime['description']) ?></p>
        <p>Score: <?= htmlspecialchars($anime['score']) ?></p>
        <p>Rank: <?= htmlspecialchars($anime['rank']) ?></p>
        <p>Popularity: <?= htmlspecialchars($anime['popularity']) ?></p>
        <p>Season: <?= htmlspecialchars($anime['season']) ?></p>

        <h2>Reviews</h2>
        <ul>
            <?php foreach ($reviews as $review): ?>
                <li>
                    <strong><?= htmlspecialchars($review['username']) ?>:</strong>
                    <em><?= htmlspecialchars($review['rating']) ?>/10</em>
                    <p><?= htmlspecialchars($review['comment']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="add_review.php" method="post">
                <input type="hidden" name="anime_id" value="<?= $anime['id'] ?>">
                <label for="rating">Rating:</label>
                <input type="number" name="rating" min="1" max="10" required>
                <label for="comment">Comment:</label>
                <textarea name="comment" required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to add a review.</p>
        <?php endif; ?>
    </div>
</body>
</html>
