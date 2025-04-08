<?php
session_start();
include('../includes/db.php');

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $photo = $_FILES['photo']['name'];

        if (!empty($photo)) {
            $target = "../user_photos/" . basename($photo);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);

            mysqli_query($conn, "UPDATE users SET name='$name', photo='$photo' WHERE id=$user_id");
            $_SESSION['user']['photo'] = $photo;
        } else {
            mysqli_query($conn, "UPDATE users SET name='$name' WHERE id=$user_id");
        }

        $_SESSION['user']['name'] = $name;
        header("Location: profile.php");
        exit();
    }

    if (isset($_POST['change_password'])) {
        $newpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$newpass' WHERE id=$user_id");
        echo "<p style='color:green;'>✅ Password updated!</p>";
    }
}

// Get completed courses
$stmt = mysqli_prepare($conn, "
    SELECT c.id, c.title, c.category 
    FROM completions cp 
    JOIN courses c ON cp.course_id = c.id 
    WHERE cp.user_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$completed_courses = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile | EduApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* Reset & Base Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f2f5fa;
    padding: 30px;
    color: #333;
}

.container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
}

/* Headings */
h2 {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #333;
}

h3 {
    margin-top: 30px;
    margin-bottom: 10px;
    color: #444;
    font-weight: 600;
}

/* Buttons */
button,
.btn {
    background: #007bff;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    font-size: 0.95rem;
    cursor: pointer;
    text-decoration: none;
    transition: 0.2s;
}

button:hover,
.btn:hover {
    background: #0056b3;
}

/* Inputs */
input[type="text"],
input[type="password"],
input[type="file"] {
    width: 100%;
    padding: 12px 14px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 0.95rem;
    background: #fafafa;
    transition: 0.2s;
}

input:focus {
    outline: none;
    border-color: #007bff;
    background: #fff;
}

/* Profile Image */
img {
    margin: 10px 0;
    border-radius: 12px;
    border: 2px solid #eee;
    width: 120px;
    height: auto;
    object-fit: cover;
}

/* Completed Courses */
ul {
    list-style: none;
    margin-top: 10px;
}

ul li {
    padding: 12px 16px;
    margin-bottom: 10px;
    background: #f7f9fc;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 4px solid #007bff;
}

ul li a {
    font-size: 0.9rem;
    color: #007bff;
    text-decoration: none;
}

ul li a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 25px;
    }

    h2 {
        font-size: 1.6rem;
    }

    h3 {
        font-size: 1.2rem;
    }

    button,
    .btn {
        width: 100%;
        margin-top: 10px;
    }

    ul li {
        flex-direction: column;
        align-items: flex-start;
    }
}

    </style>
</head>
<body>
<div class="container">
    <h2>👋 Welcome, <?= isset($user['username']) ? htmlspecialchars($user['username']) : 'Guest' ?></h2>
    <p>Email: <?= isset($user['email']) ? htmlspecialchars($user['email']) : 'Not available' ?></p>

    <h3>📷 Profile Photo</h3>
    <?php if (!empty($user['photo'])): ?>
        <img src="../user_photos/<?= htmlspecialchars($user['photo']) ?>" width="120"><br>
    <?php else: ?>
        <p>No profile photo uploaded.</p>
    <?php endif; ?>

    <h3>✏️ Edit Profile</h3>
    <form method="POST" enctype="multipart/form-data">
        Name: <input type="text" name="name" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>"><br>
        Change Photo: <input type="file" name="photo"><br>
        <button type="submit" name="update_profile">💾 Update Profile</button>
    </form>

    <h3>🔐 Change Password</h3>
    <form method="POST">
        New Password: <input type="password" name="new_password" required><br>
        <button type="submit" name="change_password">Change Password</button>
    </form>

    <h3>🎓 Completed Courses</h3>
    <?php if (mysqli_num_rows($completed_courses) > 0): ?>
        <ul>
            <?php while ($course = mysqli_fetch_assoc($completed_courses)): ?>
                <li>
                    <?= htmlspecialchars($course['title']) ?> 
                    (<?= htmlspecialchars($course['category']) ?>)
                    | <a href="certificate.php?id=<?= $course['id'] ?>">📜 View Certificate</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No courses completed yet.</p>
    <?php endif; ?>

    <br><br>
    <a href="logout.php" class="btn">🚪 Logout</a>
</div>
</body>
</html>
