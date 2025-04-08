<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
$hide_nav = basename($_SERVER['PHP_SELF']) === 'login.php' || basename($_SERVER['PHP_SELF']) === 'register.php';
?>

<?php if (isset($_SESSION['user_id']) && !$hide_nav): ?>
<nav> ... </nav>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/edu-app/assets/css/style.css">
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<nav>
    <a href="/edu-app/dashboard.php">Dashboard</a>
    <a href="/edu-app/logout.php">Logout</a>
</nav>
<?php endif; ?>




