<?php
include('../includes/db.php');
include('../includes/header.php');
session_start();

$lesson_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Get lesson data
$lesson = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lessons WHERE id = $lesson_id"));
$course = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM courses WHERE id = {$lesson['course_id']}"));

// Save progress
if ($user_id) {
    mysqli_query($conn, "INSERT IGNORE INTO progress (user_id, lesson_id) VALUES ($user_id, $lesson_id)");
}
?>

<h2><?php echo $lesson['title']; ?></h2>
<div>
    <?php echo nl2br($lesson['content']); ?>
</div>

<p><a href="course.php?id=<?php echo $lesson['course_id']; ?>">← Back to course</a></p>

<?php include('../includes/footer.php'); ?>
