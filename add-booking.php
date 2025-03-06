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
$presentdate = date('Y-m-d H:i:s');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $student_id = mysqli_real_escape_string($conn, $_POST['student']);
    $schedgroup_id = mysqli_real_escape_string($conn, $_POST['schedule']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher']);

    $result = $conn->query("SELECT `scheddate`, `schedstarttime` FROM `teachers_in_sched` WHERE `id` = $schedgroup_id");
    $row = $result->fetch_assoc();   //no loop since we are expecting one row only
    $scheddate = $row["scheddate"];
    $schedstarttime = $row["schedstarttime"];

    $result = $conn->query("SELECT `id` FROM `schedule` 
        WHERE `scheddate` = '$scheddate' AND `schedstarttime` = '$schedstarttime' AND `teacher_id` = $teacher_id;"); //enclose date and time in single quotes
    $row = $result->fetch_assoc();
    $schedule_id = $row["id"];

    // SQL query to insert data
    $sql = "INSERT INTO `booking` (`schedule_id`, `student_id`, `encoded_by`) 
            VALUES ('$schedule_id', '$student_id', 'CS Person - $presentdate');";

    // Execute query and check for success
    if (mysqli_query($conn, $sql)) {
        echo "Booking added successfully!<br>";
        echo "<a href='index.php'><button>Home</button></a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>