<?php
include('check.php');
include('../includes/db.php');

$courses = mysqli_query($conn, "SELECT * FROM courses");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $question = $_POST['question'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $correct = $_POST['correct'];

    mysqli_query($conn, "INSERT INTO tests (course_id, question, option_a, option_b, option_c, option_d, correct_option)
                         VALUES ('$course_id', '$question', '$a', '$b', '$c', '$d', '$correct')");
    echo "<p style='color:green;'>✅ Test added!</p>";
}
?>

<h2>Add Test Question</h2>
<form method="POST">
    <label>Course:</label>
    <select name="course_id">
        <?php while ($c = mysqli_fetch_assoc($courses)) { ?>
            <option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
        <?php } ?>
    </select><br><br>

    <textarea name="question" placeholder="Question" required></textarea><br>
    <input type="text" name="a" placeholder="Option A" required><br>
    <input type="text" name="b" placeholder="Option B" required><br>
    <input type="text" name="c" placeholder="Option C" required><br>
    <input type="text" name="d" placeholder="Option D" required><br>
    <input type="text" name="correct" placeholder="Correct Option (A/B/C/D)" required><br>
    <button type="submit">Add Question</button>
</form>
