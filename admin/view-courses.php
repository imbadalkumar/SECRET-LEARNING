<?php
session_start();

if (isset($_GET['deleted'])) {
    echo "<p style='color:red;'>🗑️ Course deleted successfully!</p>";
}

include('../includes/db.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>

<h2>📚 All Courses</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php while ($course = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= $course['title'] ?></td>
            <td><?= $course['category'] ?></td>
            <td><?= $course['created_at'] ?></td>
            <td>
                <a href="edit-course.php?id=<?= $course['id'] ?>">✏️ Edit</a> |
                <a href="delete-course.php?id=<?= $course['id'] ?>" onclick="return confirm('Delete this course?')">🗑️ Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
