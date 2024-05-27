<?php
include 'includes/db.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC); 

$name_error = $password_error = $delete_account_error = "";

if (isset($_POST['change_name'])) {
    $new_name = $_POST['new_name'];
    if (empty($new_name)) {
        $name_error = "Name cannot be empty.";
    } else {
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $user_id]);
        $user['name'] = $new_name; 
    }
}

if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    if (empty($new_password)) {
        $password_error = "Password cannot be empty.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_password, $user_id]);
    }
}

if (isset($_POST['delete_account'])) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    session_destroy();
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/header.jpg">
</head>
<body>
    <div class="container">
        <h1>Profile</h1>
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
        <nav>
            <a href="index.php">Home</a>
        </nav>
        <div class="profile-container">
            <h2>Change Name</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="new_name">New Name:</label>
                    <input type="text" name="new_name" id="new_name" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                    <?php if ($name_error): ?>
                        <div class="error"><?= $name_error ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="change_name">Change Name</button>
            </form>
        </div>
        
        <div class="password-container">
            <h2>Change Password</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="old_password">Old Password:</label>
                    <input type="password" name="old_password" id="old_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <?php if ($password_error): ?>
                        <div class="error"><?= $password_error ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>


        <div class="delete-container">
            <h2>Delete Account</h2>
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                <button type="submit" name="delete_account">Delete Account</button>
                <?php if ($delete_account_error): ?>
                    <div class="error"><?= $delete_account_error ?></div>
                <?php endif; ?>
            </form>
        </div>

        <div class="reviews-container">
            <h2>User Reviews</h2>
            <?php if ($user_id): ?>
                <?php
                $sql_user_reviews = "SELECT r.*, a.title AS anime_title FROM reviews r JOIN animes a ON r.anime_id = a.id WHERE r.user_id = ?";
                $stmt_user_reviews = $pdo->prepare($sql_user_reviews);
                $stmt_user_reviews->execute([$user_id]);
                $user_reviews = $stmt_user_reviews->fetchAll();
                ?>
                <?php if ($user_reviews): ?>
                    <ul>
                        <?php foreach ($user_reviews as $review): ?>
                            <li>
                                <strong><?= htmlspecialchars($review['anime_title']) ?>:</strong>
                                <em><?= htmlspecialchars($review['rating']) ?>/10</em>
                                <p><?= htmlspecialchars($review['comment']) ?></p>
                                <form action="edit_review.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <button type="submit">Edit</button>
                                </form>
                                <form action="delete_review.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No reviews found.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Please log in to see your reviews.</p>
            <?php endif; ?>
        </div>

    </div>



</body>
</html>
