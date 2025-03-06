<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include 'dbname.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['sched_id'])) {
    $sched_id = $_POST['sched_id'];

    $result = $_SESSION['sched_list'];

    $result = $conn->query("SELECT `teacher_ids`
            FROM `teachers_in_sched`
            WHERE `id` = $sched_id");
    $availableteachers = $result->fetch_assoc();
    echo "available teacher ids are: $availableteachers";

    // Fetch teachers assigned to the selected schedule
    $result = $conn->query("SELECT `id`, `fname`, `lname`
            FROM teacher
            WHERE `id` IN $availableteachers;");

    echo '<option value="">Select Teacher</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['lname'] . ', ' . $row['fname'] . '</option>';
    }
}
?>