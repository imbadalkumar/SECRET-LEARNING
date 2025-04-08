<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$course_id = $_POST['course_id'];

$stmt = mysqli_prepare($conn, "INSERT INTO completions (user_id, course_id) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
mysqli_stmt_execute($stmt);

header("Location: view-course.php?id=" . $course_id);
exit();
