<?php
session_start();
include('../includes/db.php');

// Auth check
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$course_id = $_GET['id'] ?? 0;

// Get course
$course_stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
mysqli_stmt_bind_param($course_stmt, "i", $course_id);
mysqli_stmt_execute($course_stmt);
$course_result = mysqli_stmt_get_result($course_stmt);
$course = mysqli_fetch_assoc($course_result);

if (!$course) {
    echo "❌ Course not found!";
    exit();
}

// Get lessons
$lessons = [];
$lesson_stmt = mysqli_prepare($conn, "SELECT * FROM lessons WHERE course_id = ?");
mysqli_stmt_bind_param($lesson_stmt, "i", $course_id);
mysqli_stmt_execute($lesson_stmt);
$lesson_result = mysqli_stmt_get_result($lesson_stmt);
while ($row = mysqli_fetch_assoc($lesson_result)) {
    $lessons[] = $row;
}

// Get user's completed lessons
$progress_result = mysqli_query($conn, "SELECT lesson_id FROM lesson_progress WHERE user_id = $user_id");
$completed_lessons = [];
while ($r = mysqli_fetch_assoc($progress_result)) {
    $completed_lessons[] = $r['lesson_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($course['title']) ?> | Lessons</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
       /* Reset and base setup */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #eef2f7;
    color: #333;
    padding: 20px;
    line-height: 1.6;
}

.container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 40px 30px;
    border-radius: 14px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

/* Header */
h1 {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 30px;
}

/* Lesson Block */
.lesson {
    background: #f9fbff;
    border-left: 6px solid #007bff;
    padding: 25px;
    margin-bottom: 25px;
    border-radius: 10px;
    transition: 0.3s ease;
}

.lesson:hover {
    background: #f1f6ff;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.lesson h3 {
    font-size: 1.3rem;
    margin-bottom: 12px;
    color: #333;
}

.lesson p {
    margin-top: 10px;
    font-size: 0.98rem;
}

/* Video */
iframe {
    margin-top: 10px;
    border-radius: 12px;
}

/* Buttons */
.btn, button {
    background: #28a745;
    color: white;
    padding: 10px 16px;
    font-size: 0.95rem;
    text-decoration: none;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
    margin-top: 12px;
    display: inline-block;
}

.btn:hover, button:hover {
    background: #1e7e34;
}

.done {
    color: #28a745;
    font-weight: bold;
    margin-top: 15px;
    font-size: 1rem;
}

/* Back Button */
a.btn.back {
    background: #17a2b8;
    margin-top: 30px;
}

a.btn.back:hover {
    background: #117a8b;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding: 25px 20px;
    }

    h1 {
        font-size: 1.6rem;
    }

    .lesson h3 {
        font-size: 1.1rem;
    }

    iframe {
        height: 220px;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h1>📚 <?= htmlspecialchars($course['title']) ?> - Lessons</h1>

    <?php if (count($lessons) === 0): ?>
        <p>No lessons found for this course.</p>
    <?php endif; ?>

    <?php foreach ($lessons as $lesson): ?>
        <div class="lesson">
            <h3><?= htmlspecialchars($lesson['title']) ?></h3>
            <?php if ($lesson['video_url']): ?>
                <iframe width="100%" height="315" src="<?= htmlspecialchars($lesson['video_url']) ?>" frameborder="0" allowfullscreen></iframe>
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($lesson['content'])) ?></p>

            <?php if (in_array($lesson['id'], $completed_lessons)): ?>
                <p class="done">✅ Completed</p>
            <?php else: ?>
                <form method="POST" action="mark-lesson.php">
                    <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                    <button class="btn" type="submit">✅ Mark as Done</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <a href="view-course.php?id=<?= $course_id ?>" class="btn" style="background:#17a2b8;margin-top:20px;">⬅️ Back to Course</a>
</div>

</body>
</html>
