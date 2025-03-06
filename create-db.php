<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php

mysqli_report(MYSQLI_REPORT_OFF);

set_time_limit(0);

$presentdate = date('Y-m-d');

include 'dbname.php';
if (!$conn) {
	$conn = mysqli_connect($host, $uname, $pass);
	$sql = "CREATE DATABASE IF NOT EXISTS `$database`";
	if ($conn->query($sql) === TRUE) {
		include 'dbname.php';
	}
}

$filetoresult = ltrim($prefix) . '_' . $postfix . '.';


//Timeslots Table
$tablename = 'timeslots';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`starttime` time,
		`endtime` time 
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`starttime`, `endtime`) 
			VALUES
			('08:00:00', '09:00:00'),
			('09:00:00', '10:00:00'),
			('10:00:00', '11:00:00'),
			('11:00:00', '12:00:00'),
			('12:00:00', '13:00:00'),
			('13:00:00', '14:00:00'),
			('14:00:00', '15:00:00'),
			('15:00:00', '16:00:00'),
			('16:00:00', '17:00:00'),
			('17:00:00', '18:00:00'),
			('18:00:00', '19:00:00'),
			('19:00:00', '20:00:00'),
			('20:00:00', '21:00:00'),
			('21:00:00', '22:00:00')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Language Table
$tablename = 'language';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`code` VARCHAR(100) NOT NULL DEFAULT '',
      	`details` VARCHAR(100)
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`code`, `details`) 
			VALUES
			('ZH', 'Chinese'),
			('EN', 'English'),
			('FIL', 'Filipino')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Teacher Table
$tablename = 'teacher';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
	  	`email` VARCHAR(100) NOT NULL UNIQUE,
		`city` VARCHAR(100) NOT NULL,
		`phone` VARCHAR(50) NOT NULL,
		`birthday` DATE,
		`gender` VARCHAR(20) NOT NULL,
		`language_id` INT DEFAULT NULL,
		FOREIGN KEY (`language_id`) REFERENCES `language`(`id`) ON DELETE SET NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`fname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `language_id`) 
			VALUES
			('Kat', 'Gecolea', 'kat@wetalk.com', 'Cavite', '+639876543210', '1990-05-01', 'Female', 2)
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Student Table
$tablename = 'student';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
	  	`email` VARCHAR(100) NOT NULL UNIQUE,
		`city` VARCHAR(100) NOT NULL,
		`phone` VARCHAR(50) NOT NULL,
		`birthday` DATE,
		`gender` VARCHAR(20) NOT NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Schedule Table (made by teachers)
$tablename = 'schedule';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`scheddate` DATE,
		`schedstarttime` TIME,
		`schedendtime` TIME,
		`teacher_id` INT DEFAULT NULL,
		`language_id` INT DEFAULT NULL,
		`booking_id` INT DEFAULT NULL,
		FOREIGN KEY (`teacher_id`) REFERENCES `teacher`(`id`) ON DELETE SET NULL,
		FOREIGN KEY (`language_id`) REFERENCES `language`(`id`) ON DELETE SET NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Booking Table (made by students/CS)
$tablename = 'booking';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`schedule_id` INT DEFAULT NULL,
		`student_id` INT DEFAULT NULL,
		`encoded_by` VARCHAR(100) NOT NULL,
		FOREIGN KEY (`schedule_id`) REFERENCES `schedule`(`id`) ON DELETE SET NULL,
		FOREIGN KEY (`student_id`) REFERENCES `student`(`id`) ON DELETE SET NULL
	);"
;
//to do add foreign key
if ($conn->query($query) === TRUE) {
	mysqli_query($conn, "ALTER TABLE `schedule` ADD FOREIGN KEY (`booking_id`) REFERENCES `booking`(`id`) ON DELETE SET NULL");
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Grouped Teachers Per Sched Table
$tablename = 'teachers_in_sched';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,  
		`scheddate` DATE,
		`schedstarttime` TIME,
		`schedendtime` TIME,
		`teacher_ids` TEXT NOT NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

// Close connection
mysqli_close($conn);
?>