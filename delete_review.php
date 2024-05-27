<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$review_id = $_POST['review_id'] ?? null;

if (!$review_id) {
    echo "Invalid review ID.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql_delete_review = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
$stmt_delete_review = $pdo->prepare($sql_delete_review);
$stmt_delete_review->execute([$review_id, $user_id]);

if ($stmt_delete_review->rowCount() > 0) {
    header("Location: profile.php");
    exit();
} else {
    echo "Failed to delete review.";
    exit;
}
?>
