<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$course_id = intval($_GET['id']); // sanitize
$user_id = $_SESSION['user']['id'];

// 1. Check if user completed all lessons
$lessonCount = mysqli_query($conn, "SELECT COUNT(*) as total FROM lessons WHERE course_id = $course_id");
$lessonDone = mysqli_query($conn, "
    SELECT COUNT(*) as done 
    FROM lesson_progress 
    JOIN lessons ON lesson_progress.lesson_id = lessons.id 
    WHERE lessons.course_id = $course_id AND lesson_progress.user_id = $user_id
");

$total = mysqli_fetch_assoc($lessonCount)['total'];
$done = mysqli_fetch_assoc($lessonDone)['done'];

if ($total == 0 || $done < $total) {
    echo "<h2 style='color:red;'>🚫 Please complete all lessons before attempting the test.</h2>";
    exit();
}

// 2. Check if user already attempted
$attempted = mysqli_query($conn, "SELECT * FROM test_attempts WHERE user_id = $user_id AND course_id = $course_id");
if (mysqli_num_rows($attempted) > 0) {
    echo "<h2 style='color:green;'>✅ You have already attempted the test.</h2>";
    echo "<a href='certificate.php?id=$course_id'>🎓 Get Certificate</a>";
    exit();
}

// 3. Get Questions
$questions = mysqli_query($conn, "SELECT * FROM questions WHERE course_id = $course_id");

if (mysqli_num_rows($questions) === 0) {
    echo "<h2 style='color:red;'>⚠️ No questions available for this course.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Final Test | EduApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">


    <style>

body {
    font-family: 'Segoe UI', sans-serif;
    background: #eef3f8;
    padding: 40px;
}

.container {
    max-width: 860px;
    margin: auto;
    background: white;
    padding: 40px;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 25px;
}

.q {
    margin-bottom: 30px;
    padding: 20px;
    background: #f9f9fb;
    border: 1px solid #ddd;
    border-radius: 10px;
    transition: all 0.2s;
}

.q:hover {
    background: #f0f6ff;
    border-color: #a0c4ff;
}

.q p {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #333;
}

label {
    display: block;
    margin: 8px 0;
    padding-left: 10px;
    font-size: 1rem;
    cursor: pointer;
}

input[type="radio"] {
    margin-right: 10px;
}

button[type="submit"] {
    margin-top: 30px;
    background: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

button[type="submit"]:hover {
    background: #0056b3;
}

      </style>
</head>
<body>
<div class="container">
    <h2>📝 Final Test</h2>
    <form method="POST" action="submit-test.php">
        <input type="hidden" name="course_id" value="<?= $course_id ?>">

        <?php $i = 1; while ($q = mysqli_fetch_assoc($questions)): ?>
            <div class="q">
                <p><b><?= $i++ ?>. <?= htmlspecialchars($q['question']) ?></b></p>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> <?= htmlspecialchars($q['option_a']) ?></label>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> <?= htmlspecialchars($q['option_b']) ?></label>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> <?= htmlspecialchars($q['option_c']) ?></label>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> <?= htmlspecialchars($q['option_d']) ?></label>
            </div>
        <?php endwhile; ?>

        <button type="submit">🚀 Submit Test</button>
    </form>
</div>
</body>
</html>
