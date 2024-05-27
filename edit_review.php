<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$review_id = $_POST['review_id'] ?? $_GET['review_id'] ?? null;

if (!$review_id) {
    echo "Invalid review ID.";
    exit;
}

$user_id = $_SESSION['user_id'];


$sql_review = "SELECT * FROM reviews WHERE id = ? AND user_id = ?";
$stmt_review = $pdo->prepare($sql_review);
$stmt_review->execute([$review_id, $user_id]);
$review = $stmt_review->fetch();

if (!$review) {
    echo "Review not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_rating']) && isset($_POST['new_comment'])) {
    $new_rating = $_POST['new_rating'];
    $new_comment = $_POST['new_comment'];
    $sql_update_review = "UPDATE reviews SET rating = ?, comment = ? WHERE id = ? AND user_id = ?";
    $stmt_update_review = $pdo->prepare($sql_update_review);
    $stmt_update_review->execute([$new_rating, $new_comment, $review_id, $user_id]);

    if ($stmt_update_review->rowCount() > 0) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Failed to update review.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/header.jpg">
</head>
<body>
    <div class="container">
        <h1>Edit Review</h1>
        <nav>
            <a href="index.php">Home</a>
        </nav>
        <form method="POST" action="edit_review.php?review_id=<?= htmlspecialchars($review_id) ?>">
            <div class="form-group">
                <label for="new_rating">New Rating:</label>
                <input type="number" name="new_rating" id="new_rating" min="1" max="10" value="<?= htmlspecialchars($review['rating']) ?>" required>
            </div>
            <div class="form-group">
                <label for="new_comment">New Comment:</label>
                <textarea name="new_comment" id="new_comment" required><?= htmlspecialchars($review['comment']) ?></textarea>
            </div>
            <button type="submit">Update Review</button>
        </form>
    </div>
</body>
</html>
