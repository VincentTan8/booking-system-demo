<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include 'conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['sched_id'])) {
    $sched_id = $_POST['sched_id'];

    $result = $conn->query("SELECT `teacher_ids`
            FROM `teachers_in_sched`
            WHERE `id` = $sched_id");
    $row = $result->fetch_assoc();
    $availableteachers = $row['teacher_ids'];     //sample value: 1,8,9

    // Fetch teachers assigned to the selected schedule
    $result = $conn->query("SELECT `id`, `fname`, `lname`
            FROM `teacher`
            WHERE `id` IN ($availableteachers)
            ORDER BY `lname` ASC;");

    echo '<option value="">Select Teacher</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['lname'] . ', ' . $row['fname'] . '</option>';
    }
}
?>