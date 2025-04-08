<?php
include('../includes/db.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Courses | EduApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f3f6fb;
    padding: 40px;
}

.container {
    max-width: 960px;
    margin: auto;
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 30px;
}

form {
    margin-bottom: 30px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

select, input[type="text"] {
    padding: 12px 16px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 1rem;
    flex-grow: 1;
    min-width: 200px;
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.course {
    background: #f9fafb;
    border: 1px solid #e3e7ec;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    transition: box-shadow 0.2s ease;
}

.course:hover {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
}

.course h3 {
    margin-bottom: 8px;
    font-size: 1.3rem;
    color: #333;
}

.course p {
    color: #555;
    margin-bottom: 12px;
}

.btn {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
}

.btn:hover {
    background: #218838;
}

/* Responsive */
@media (max-width: 600px) {
    form {
        flex-direction: column;
        align-items: stretch;
    }

    button[type="submit"] {
        width: 100%;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h2>📚 Search & Filter Courses</h2>

    <form method="GET">
        <select name="category">
            <option value="">-- All Categories --</option>
            <option value="Web Development">Web Development</option>
            <option value="Design">Design</option>
            <option value="Marketing">Marketing</option>
        </select>

        <input type="text" name="search" placeholder="🔍 Search courses..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">

        <button type="submit">Search</button>
    </form>

    <?php
    // Build query
    $query = "SELECT * FROM courses WHERE 1";
    $params = [];

    if (!empty($_GET['category'])) {
        $query .= " AND category = ?";
        $params[] = $_GET['category'];
    }

    if (!empty($_GET['search'])) {
        $query .= " AND title LIKE ?";
        $params[] = "%" . $_GET['search'] . "%";
    }

    $stmt = mysqli_prepare($conn, $query);

    if ($params) {
        $types = str_repeat("s", count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
        <div class="course">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
            <a class="btn" href="view-course.php?id=<?= $row['id'] ?>">📘 View Course</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No courses found.</p>";
    endif;
    ?>
</div>

</body>
</html>
