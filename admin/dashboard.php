<?php
session_start();
include('../includes/db.php');
include('includes/auth.php');

// Fetch Stats
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$totalCourses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM courses"))['count'];
$totalCompletions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM completions"))['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | EduApp</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 40px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .card {
            flex: 1;
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card span {
            display: block;
            font-size: 30px;
            font-weight: bold;
            margin-top: 5px;
        }
        .links a {
            display: inline-block;
            margin: 10px 15px 0 0;
            padding: 10px 16px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, Admin 👑</h1>

    <div class="stats">
        <div class="card">👤 Users <span><?= $totalUsers ?></span></div>
        <div class="card">📚 Courses <span><?= $totalCourses ?></span></div>
        <div class="card">🎯 Completions <span><?= $totalCompletions ?></span></div>
    </div>

    <div class="links">
        <a href="add-course.php">➕ Add Course</a>
        <a href="add-lesson.php" style="display:inline-block; margin:10px; padding:12px 20px; background:#007bff; color:white; border-radius:8px; text-decoration:none;">📚 Add Lesson</a>


        <a href="view-courses.php">📋 View All Courses</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

</body>
</html>

