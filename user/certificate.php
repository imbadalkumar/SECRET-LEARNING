<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$course_id = $_GET['id'] ?? 0;

// Check if user completed this course
$stmt = mysqli_prepare($conn, "SELECT * FROM completions WHERE user_id = ? AND course_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "<h3 style='color:red;'>🚫 You must complete the course to access the certificate.</h3>";
    exit();
}

// ✅ Get course title
$stmt = mysqli_prepare($conn, "SELECT title FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result_title = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result_title);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Get Your Certificate | EduApp</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; padding: 50px; }
        .box { max-width: 600px; margin: auto; background: white; padding: 40px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input[type="text"] { padding: 12px; width: 80%; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ccc; }
        button { padding: 12px 20px; background: #007bff; color: white; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>

<div class="box">
    <h2>🎉 Congratulations on completing:</h2>
    <h1><?= htmlspecialchars($course['title']) ?></h1>
    <p>Enter your name to get your certificate</p>
    <form method="POST" action="generate-certificate.php">
        <input type="hidden" name="course" value="<?= htmlspecialchars($course['title']) ?>">
        <input type="text" name="name" placeholder="Your Full Name" required>
        <br>
        <button type="submit">🎓 Download Certificate</button>
    </form>
</div>

</body>
</html>

