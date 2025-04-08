<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$course = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $content = $_POST['content'];

    $update = mysqli_prepare($conn, "UPDATE courses SET title = ?, category = ?, description = ?, content = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "ssssi", $title, $category, $description, $content, $id);
    mysqli_stmt_execute($update);

    echo "<p style='color:green;'>✅ Course updated successfully!</p>";
    // Refresh data
    $course['title'] = $title;
    $course['category'] = $category;
    $course['description'] = $description;
    $course['content'] = $content;
}
?>

<h2>✏️ Edit Course</h2>
<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" required><br><br>
    <input type="text" name="category" value="<?= htmlspecialchars($course['category']) ?>"><br><br>
    <textarea name="description" rows="3" cols="50"><?= htmlspecialchars($course['description']) ?></textarea><br><br>
    <textarea name="content" rows="10" cols="80"><?= htmlspecialchars($course['content']) ?></textarea><br><br>
    <button type="submit">Update Course</button>
</form>
<br>
<a href="view-courses.php">🔙 Back to Course List</a>
