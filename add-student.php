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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthday = $_POST['birthday']; // Date is already safe format

    // SQL query to insert data
    $sql = "INSERT INTO `student` (`fname`, `lname`, `email`, `city`, `phone`, `gender`, `birthday`) 
            VALUES ('$fname', '$lname', '$email', '$city', '$phone', '$gender', '$birthday');";

    // Execute query and check for success
    if (mysqli_query($conn, $sql)) {
        echo "Student registered successfully!<br>";
        echo "<a href='index.php'><button>Home</button></a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>