<?php include('../includes/db.php'); include('../includes/header.php'); ?>

<h2>Available Courses</h2>

<?php
$courses = mysqli_query($conn, "SELECT * FROM courses");
while($course = mysqli_fetch_assoc($courses)) {
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

/* Base setup */
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: #f5f7fa;
    color: #333;
}

/* Heading */
h2 {
    text-align: center;
    color: #007bff;
    margin-top: 30px;
    font-size: 2rem;
}

/* Filter form */
form {
    max-width: 800px;
    margin: 20px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

form select,
form input[type="text"] {
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    width: 200px;
}

form button {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}

form button:hover {
    background: #0056b3;
}

/* Course container */
.course-card {
    background: white;
    max-width: 800px;
    margin: 20px auto;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    display: flex;
    gap: 20px;
    align-items: center;
    transition: transform 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
}

/* Thumbnail */
.course-card img {
    width: 120px;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Course Info */
.course-card h3 {
    margin: 0 0 10px 0;
    font-size: 1.3rem;
    color: #333;
}

.course-card h3 a {
    text-decoration: none;
    color: #007bff;
}

.course-card h3 a:hover {
    text-decoration: underline;
}

.course-card p {
    margin: 0;
    color: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .course-card {
        flex-direction: column;
        text-align: center;
    }

    .course-card img {
        width: 100%;
        max-width: 300px;
    }

    form {
        flex-direction: column;
        align-items: center;
    }

    form select,
    form input[type="text"],
    form button {
        width: 100%;
        max-width: 300px;
    }
}


    </style>
</head>
<body>
    

<form method="GET">
    <select name="category">
        <option value="">-- All Categories --</option>
        <option value="Web Development">Web Development</option>
        <option value="Design">Design</option>
        <option value="Marketing">Marketing</option>
        <!-- Add more categories as per your need -->
    </select>

    <input type="text" name="search" placeholder="🔍 Search courses...">
    <button type="submit">Filter</button>
</form>

    
<div class="course-card">
    <img src="/uploads/<?php echo $course['thumbnail']; ?>" alt="Course Thumbnail">
    <div>
        <h3><a href="course.php?id=<?php echo $course['id']; ?>"><?php echo $course['title']; ?></a></h3>
        <p><?php echo $course['description']; ?></p>
    </div>
</div>

<?php } ?>

<?php include('../includes/footer.php'); ?>


</body>
</html>