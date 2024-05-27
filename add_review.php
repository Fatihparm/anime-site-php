<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $anime_id = $_POST['anime_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO reviews (user_id, anime_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $anime_id, $rating, $comment]);

    header("Location: anime_details.php?id=$anime_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link rel="icon" href="img/header.jpg">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
    </nav>

    <h1>Add Review</h1>
    <form action="add_review.php" method="post">
        <label for="anime_id">Anime ID:</label>
        <input type="number" name="anime_id" required>
        <label for="rating">Rating:</label>
        <input type="number" name="rating" min="1" max="10" required>
        <label for="comment">Comment:</label>
        <textarea name="comment" required></textarea>
        <button type="submit">Submit Review</button>
    </form>
</body>
</html>
