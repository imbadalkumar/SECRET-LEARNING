<?php
session_start();
include('../includes/db.php');

$user_id = $_SESSION['user']['id'];
$course_id = $_POST['course_id'];
$answers = $_POST['answers'] ?? [];

$score = 0;
$total = count($answers);

// Early exit if no questions attempted
if ($total === 0) {
    echo "<h2 style='color:red;'>🚫 No questions available for this test.</h2>";
    exit();
}

// Loop through answers
foreach ($answers as $qid => $ans) {
    $q = mysqli_query($conn, "SELECT correct_option FROM questions WHERE id = $qid");
    $correct = mysqli_fetch_assoc($q)['correct_option'];
    if ($correct == $ans) $score++;
}

// 60% passing criteria
$passing = ceil($total * 0.6);

// 🔐 Save score attempt
mysqli_query($conn, "INSERT INTO test_attempts (user_id, course_id, score) VALUES ($user_id, $course_id, $score)");

if ($score >= $passing) {
    // Mark course as completed
    $exists = mysqli_query($conn, "SELECT * FROM completions WHERE user_id = $user_id AND course_id = $course_id");
    if (mysqli_num_rows($exists) == 0) {
        mysqli_query($conn, "INSERT INTO completions (user_id, course_id) VALUES ($user_id, $course_id)");
    }
    echo "<h2 style='color:green;'>🎉 Test Passed! You can now download your certificate.</h2>";
    echo "<a href='certificate.php?id=$course_id'>🎓 Get Certificate</a>";
} else {
    echo "<h2 style='color:red;'>❌ Test Failed. You got $score/$total correct. Try again!</h2>";
    echo "<a href='test.php?id=$course_id'>🔁 Retake Test</a>";
}
?>

