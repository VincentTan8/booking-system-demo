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
$presentdate = date('Y-m-d H:i:s');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $booking_id = mysqli_real_escape_string($conn, $_POST['booking']);

    // SQL query to delete data
    $sql = "DELETE FROM `booking` WHERE `id` = $booking_id;";

    // Execute query and check for success
    if (mysqli_query($conn, $sql)) {
        echo "Booking deleted successfully!<br>";
        echo "<a href='index.php'><button>Home</button></a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>