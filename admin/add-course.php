<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $content = $_POST['content'];

    $query = "INSERT INTO courses (title, category, description, content) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $category, $description, $content);
    mysqli_stmt_execute($stmt);

    echo "<p style='color:green;'>✅ Course added successfully!</p>";
}
?>

<h2>Add New Course</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Course Title" required><br><br>
    <input type="text" name="category" placeholder="Category"><br><br>
    <textarea name="description" placeholder="Short Description" rows="3" cols="50"></textarea><br><br>
    <textarea name="content" placeholder="Full Course Content" rows="10" cols="80"></textarea><br><br>
    <button type="submit">Add Course</button>
</form>

