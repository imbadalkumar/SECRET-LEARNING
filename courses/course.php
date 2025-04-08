<?php
include('../includes/db.php');
include('../includes/header.php');

$course_id = $_GET['id'];
$course = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM courses WHERE id = $course_id"));
$lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE course_id = $course_id ORDER BY lesson_order");
?>

<h2><?php echo $course['title']; ?></h2>
<p><?php echo $course['description']; ?></p>

<h3>Lessons</h3>
<ol>
<?php while ($lesson = mysqli_fetch_assoc($lessons)) { ?>
    <li>
        <a href="lesson.php?id=<?php echo $lesson['id']; ?>"><?php echo $lesson['title']; ?></a>
    </li>
<?php } ?>
</ol>

<?php include('../includes/footer.php'); ?>

<?php
include('includes/db.php');

$where = "WHERE 1";
$params = [];

if (!empty($_GET['category'])) {
    $where .= " AND category = ?";
    $params[] = $_GET['category'];
}

if (!empty($_GET['search'])) {
    $where .= " AND title LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
}

$sql = "SELECT * FROM courses $where";
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters dynamically
if ($params) {
    $types = str_repeat("s", count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div style='margin-bottom:20px;'>";
    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
    echo "<a href='view-course.php?id=" . $row['id'] . "'>View Course</a>";
    echo "</div>";
}
