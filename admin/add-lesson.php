<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include('check.php');
include('../includes/db.php');

// Fetch all courses
$courses = mysqli_query($conn, "SELECT * FROM courses");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $order = $_POST['order'];
    $video = mysqli_real_escape_string($conn, $_POST['video']);

    $query = "INSERT INTO lessons (course_id, title, content, lesson_order, video_url)
              VALUES ('$course_id', '$title', '$content', '$order', '$video')";
    
    if (mysqli_query($conn, $query)) {
        echo "<p style='color:green;'>✅ Lesson added successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to add lesson: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lesson | Admin Panel</title>
    <style>
        body { font-family: sans-serif; background: #f9f9f9; padding: 40px; }
        form { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 6px; }
        button { padding: 12px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>

<h2 style="text-align:center;">➕ Add New Lesson</h2>
<form method="POST">
    <label>Choose Course</label>
    <select name="course_id" required>
        <option value="">-- Select Course --</option>
        <?php while($c = mysqli_fetch_assoc($courses)) { ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
        <?php } ?>
    </select>

    <label>Lesson Title</label>
    <input type="text" name="title" required>

    <label>Lesson Content</label>
    <textarea name="content" rows="5" required></textarea>

    <label>YouTube Embed URL (optional)</label>
    <input type="text" name="video" placeholder="https://www.youtube.com/embed/xyz">

    <label>Lesson Order</label>
    <input type="number" name="order" required min="1">

    <button type="submit">💾 Add Lesson</button>
</form>

</body>
</html>
