<?php
$pass = "admin123"; // Your new password
$hash = password_hash($pass, PASSWORD_DEFAULT);
echo "Password hash for 'admin123': <br><b>$hash</b>";
?>
