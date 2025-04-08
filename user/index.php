<?php
include('../includes/db.php');
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secret Learning | Learn New Skills</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/edu-app/assets/css/home.css">

</head>
<body>

<div class="header">
    <h1>🌐 Welcome to Secret Learning</h1>
    <p>Learn skills & earn free certificates 💡</p>
  
  
  <!-- Add more categories as per your need -->
   <!-- <a href="dashboard.php" class="btn">📊 My Dashboard</a> -->
<!-- Add more categories as per your need -->


    <div class="top-links">
        <a href="search-courses.php">🔍 Search Courses</a>
        <a href="profile.php">👤 My Profile</a>
    </div>
</div>

<div class="container">
    <h2>📚 Available Courses</h2>

    <?php while ($row = mysqli_fetch_assoc($courses)) { ?>
        <div class="course">
            <h2><?= htmlspecialchars($row['title']) ?></h2>
            <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <a href="view-course.php?id=<?= $row['id'] ?>" class="btn">📖 Read Course</a>
        </div>
    <?php } ?>
</div>


</body>
</html>
