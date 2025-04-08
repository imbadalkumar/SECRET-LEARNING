<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$lesson_id = $_POST['lesson_id'] ?? 0;

if ($lesson_id) {
    // Prevent duplicate
    $check = mysqli_query($conn, "SELECT * FROM lesson_progress WHERE user_id=$user_id AND lesson_id=$lesson_id");
    if (mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "INSERT INTO lesson_progress (user_id, lesson_id) VALUES ($user_id, $lesson_id)");
    }

    // Optionally: check if all lessons completed & mark course complete
    $course_res = mysqli_query($conn, "SELECT course_id FROM lessons WHERE id=$lesson_id");
    $course = mysqli_fetch_assoc($course_res);
    $course_id = $course['course_id'];

    $total = mysqli_query($conn, "SELECT COUNT(*) as total FROM lessons WHERE course_id=$course_id");
    $total_lessons = mysqli_fetch_assoc($total)['total'];

    $completed = mysqli_query($conn, "SELECT COUNT(*) as done FROM lesson_progress 
        WHERE user_id=$user_id AND lesson_id IN 
        (SELECT id FROM lessons WHERE course_id=$course_id)");
    $done_lessons = mysqli_fetch_assoc($completed)['done'];

    if ($total_lessons == $done_lessons) {
        $already = mysqli_query($conn, "SELECT * FROM completions WHERE user_id=$user_id AND course_id=$course_id");
        if (mysqli_num_rows($already) === 0) {
            mysqli_query($conn, "INSERT INTO completions (user_id, course_id) VALUES ($user_id, $course_id)");
        }
    }

    header("Location: lessons.php?id=$course_id");
    exit();
}
?>
