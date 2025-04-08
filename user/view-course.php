<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

// Get course info
$stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result);

// Get lessons
$lesson_res = mysqli_query($conn, "SELECT * FROM lessons WHERE course_id = $course_id");
$lessons = [];
while ($row = mysqli_fetch_assoc($lesson_res)) {
    $lessons[] = $row;
}
$total_lessons = count($lessons);

// Get completed lessons
$progress = [];
$res = mysqli_query($conn, "SELECT lesson_id FROM lesson_progress WHERE user_id = $user_id");
while ($row = mysqli_fetch_assoc($res)) {
    $progress[] = $row['lesson_id'];
}

// Completion Check
$completed_lessons = 0;
foreach ($lessons as $l) {
    if (in_array($l['id'], $progress)) $completed_lessons++;
}
$all_done = $total_lessons > 0 && $completed_lessons === $total_lessons;

// Check if course marked completed
$check = mysqli_query($conn, "SELECT * FROM completions WHERE user_id=$user_id AND course_id=$course_id");
$course_completed = mysqli_num_rows($check) > 0;

// Check if test is given
$test_check = mysqli_query($conn, "SELECT * FROM test_attempts WHERE user_id = $user_id AND course_id = $course_id");
$test_given = mysqli_num_rows($test_check) > 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($course['title']) ?> | EduApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
       /* ===== BASE RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f0f4f8;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

/* ===== CONTAINER ===== */
.container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 40px 30px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

/* ===== HEADINGS ===== */
h1 {
    font-size: 2.2rem;
    color: #007bff;
    margin-bottom: 10px;
}

h3 {
    margin-top: 40px;
    font-size: 1.4rem;
    color: #444;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ===== PARAGRAPHS ===== */
p {
    margin: 10px 0 20px;
    font-size: 1rem;
}

/* ===== BUTTONS ===== */
.btn, button {
    background: #0d6efd;
    color: white;
    padding: 10px 18px;
    font-size: 1rem;
    text-decoration: none;
    border-radius: 8px;
    border: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn:hover,
button:hover {
    background: #084298;
}

/* ===== LESSONS LIST ===== */
ul {
    list-style: none;
    margin-top: 20px;
}

li {
    background: #f7faff;
    padding: 15px 20px;
    margin-bottom: 12px;
    border-left: 6px solid #007bff;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

/* ===== LESSON STATUS ===== */
li form button {
    background: #28a745;
    padding: 6px 12px;
    font-size: 0.9rem;
}

li form button:hover {
    background: #1c7c34;
}

li.completed {
    background: #e8f9f1;
    border-left: 6px solid #28a745;
    color: #2c6e49;
}

/* ===== STATUS TEXT ===== */
.status {
    font-size: 0.95rem;
    color: #666;
    margin-top: 5px;
}

/* ===== CERTIFICATE / TEST / BACK LINKS ===== */
a.btn {
    margin-top: 25px;
    display: inline-block;
}

p[style*="color: green"],
p[style*="color: orange"] {
    font-weight: 500;
    padding: 10px;
    border-radius: 8px;
}

p[style*="color: green"] {
    background: #e6ffed;
    color: #198754;
}

p[style*="color: orange"] {
    background: #fff8e1;
    color: #e69500;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .container {
        padding: 25px 20px;
    }

    h1 {
        font-size: 1.8rem;
    }

    h3 {
        font-size: 1.2rem;
    }

    li {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    form {
        width: 100%;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h1><?= htmlspecialchars($course['title']) ?></h1>
    <p><strong>Category:</strong> <?= htmlspecialchars($course['category']) ?></p>
    <p><?= nl2br(htmlspecialchars($course['content'])) ?></p>

    <h3>📚 Lessons</h3>
    <a href="lessons.php?id=<?= $course['id'] ?>" class="btn">🚀 Start Lessons</a>

    <ul>
        <?php foreach ($lessons as $lesson): ?>
            <li>
                <?= htmlspecialchars($lesson['title']) ?>
                <?php if (in_array($lesson['id'], $progress)): ?>
                    ✅
                <?php else: ?>
                    <form method="POST" action="mark-lesson.php">
                        <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <button type="submit">Mark as Done</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- ✅ Final logic -->
    <?php if ($all_done && !$course_completed): ?>
        <form method="POST" action="mark-complete.php">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <button class="btn" type="submit">✅ Mark Course as Completed</button>
        </form>

    <?php elseif ($course_completed && !$test_given): ?>
        <p style="color: green;">🎉 Lessons Completed!</p>
        <a href="test.php?id=<?= $course_id ?>" class="btn">📝 Take Final Test</a>

    <?php elseif ($course_completed && $test_given): ?>
        <p style="color: green;">🎉 You have completed this course & test!</p>
        <a href="certificate.php?id=<?= $course['id'] ?>" class="btn">🎓 Get Certificate</a>

    <?php else: ?>
        <p style="color: orange;">📌 Complete all lessons to unlock the final test</p>
    <?php endif; ?>

    <a href="index.php" class="btn">⬅️ Back to Courses</a>
</div>

</body>
</html>
