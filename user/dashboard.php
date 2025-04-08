<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Get completed courses
$sql = "SELECT c.title, c.id FROM completions comp
        JOIN courses c ON comp.course_id = c.id
        WHERE comp.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard | EduApp</title>
    <link rel="stylesheet" href="/edu-app/assets/css/style.css">

    <style>
        body { font-family: sans-serif; background: #f0f0f0; padding: 30px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        h1 { color: #007bff; }
        ul { padding-left: 20px; }
        li { margin-bottom: 10px; }
        .btn { background: #28a745; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; }
    </style>
</head>
<body>
<div class="dashboard-container">

    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?> 👋</h1>
    <h3>🎓 Completed Courses</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <li>
                    <?= htmlspecialchars($row['title']) ?>
                    - <a class="btn" href="certificate.php?id=<?= $row['id'] ?>">Download Certificate</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No courses completed yet. Keep learning! 🚀</p>
    <?php endif; ?>

    <br>
    <a class="back-btn" href="index.php">⬅️ Back to Courses</a>

</div>
</body>
</html>
