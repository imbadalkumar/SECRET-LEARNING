<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>


<?php include('check.php'); ?>

<h2>Welcome Admin 👋</h2>

<ul>
    <li><a href="add-course.php">Add New Course</a></li>
    <li><a href="manage-courses.php">Manage Courses</a></li>
    <li><a href="add-lesson.php">Add New Lesson</a></li>
    <li><a href="manage-lessons.php">Manage Lessons</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
