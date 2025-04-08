<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/header.php');
?>

<h2>Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h2>
<p>This is your dashboard. Courses will appear here.</p>

<?php include('includes/footer.php'); ?>
