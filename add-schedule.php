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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $scheddate = $_POST['scheddate'];
    $schedtime_id = $_POST['schedtime'];
    $schedtimeslot = $conn->query("SELECT `starttime`, `endtime` FROM `timeslots` WHERE `id` = $schedtime_id;");
    $row = $schedtimeslot->fetch_assoc();
    $schedstarttime = $row["starttime"];
    $schedendtime = $row["endtime"];

    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher']);
    $teacher = $conn->query("SELECT `language_id` FROM `teacher` WHERE `id` = $teacher_id");
    $row = $teacher->fetch_assoc();
    $language_id = $row["language_id"];

    // SQL query to insert data
    $sql = "INSERT INTO `schedule` (`scheddate`, `schedstarttime`, `schedendtime`, `teacher_id`, `language_id`) 
            VALUES ('$scheddate', '$schedstarttime', '$schedendtime', '$teacher_id', '$language_id');";

    // Execute query and check for success
    if (mysqli_query($conn, $sql)) {
        echo "Schedule added successfully!<br>";
        echo "<a href='index.php'><button>Home</button></a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>