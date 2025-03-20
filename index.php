<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}

// Database connection
include 'conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Registration</title>
</head>

<body>
    <h2>Student Registration Form</h2>
    <form action="add-student.php" method="post">
        <label>First Name:</label>
        <input type="text" name="fname" required><br><br>

        <label>Last Name:</label>
        <input type="text" name="lname" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>City:</label>
        <input type="text" name="city" required><br><br>

        <label>Phone:</label>
        <input type="text" name="phone" required><br><br>

        <label>Birthday:</label>
        <input type="date" name="birthday" required><br><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <br><br>

        <input type="submit" value="Register Student">
    </form>

    <h2>Teacher Registration Form</h2>
    <form action="add-teacher.php" method="post">
        <label>First Name:</label>
        <input type="text" name="fname" required><br><br>

        <label>Last Name:</label>
        <input type="text" name="lname" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>City:</label>
        <input type="text" name="city" required><br><br>

        <label>Phone:</label>
        <input type="text" name="phone" required><br><br>

        <label>Birthday:</label>
        <input type="date" name="birthday" required><br><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <br><br>

        <label>Language:</label>
        <select name="language" required>
            <option value="">Select Language</option>
            <?php
            $langlist = $conn->query("SELECT * FROM  `language` ORDER BY `details` ASC;");

            for ($i = 0; $i < $langlist->num_rows; $i++) {
                $row = $langlist->fetch_assoc();
                $details = $row["details"];
                $id = $row["id"];
                echo "<option value='$id'>$details</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <input type="submit" value="Register Teacher">
    </form>

    <h2>Add Schedule (Teacher)</h2>
    <form action="add-schedule.php" method="post">
        <label>Date:</label>
        <input type="date" name="scheddate" required><br><br>

        <label>Timeslot:</label>
        <select name="schedtime" required>
            <option value="">Select Timeslot</option>
            <?php
            $timelist = $conn->query("SELECT * FROM  `timeslots` ORDER BY `id` ASC;");

            for ($i = 0; $i < $timelist->num_rows; $i++) {
                $row = $timelist->fetch_assoc();
                $starttime = $row["starttime"];
                $endtime = $row["endtime"];
                $id = $row["id"];
                echo "<option value='$id'>$starttime - $endtime</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <label>Platform:</label>
        <select name="platform" required>
            <option value="">Select Platform</option>
            <option value="0">Offline</option>
            <option value="1">Online</option>
        </select>
        <br><br>

        <label>Teacher:</label>
        <select name="teacher" required>
            <option value="">Select Teacher</option>
            <?php
            $teacherlist = $conn->query("SELECT * FROM  `teacher` ORDER BY `lname` ASC;");

            for ($i = 0; $i < $teacherlist->num_rows; $i++) {
                $row = $teacherlist->fetch_assoc();
                $fname = $row["fname"];
                $lname = $row["lname"];
                $id = $row["id"];
                echo "<option value='$id'>$lname, $fname</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <input type="submit" value="Add Schedule">
    </form>

    <h2>Add Booking (CS)</h2> <!-- Student will not have a student field -->
    <form action="add-booking.php" method="post">
        <label>Schedules Available:</label>
        <select id="scheduleSelect" name="schedule" required>
            <option value="">Select Schedule</option>
            <?php
            //to avoid duplicate entries
            $conn->query("TRUNCATE TABLE `teachers_in_sched`");

            $conn->query("INSERT INTO `teachers_in_sched` (`scheddate`, `schedstarttime`, `schedendtime`, `platform`, `teacher_ids`)
                    SELECT `scheddate`, `schedstarttime`, `schedendtime`, `platform`,
                    GROUP_CONCAT(DISTINCT `teacher_id` ORDER BY `teacher_id` SEPARATOR ',') AS `teacher_ids`
                    FROM `schedule` WHERE `booking_id` IS NULL
                    GROUP BY `scheddate`, `schedstarttime`, `schedendtime`, `platform`;");

            $schedlist = $conn->query("SELECT * FROM `teachers_in_sched`");

            for ($i = 0; $i < $schedlist->num_rows; $i++) {
                $row = $schedlist->fetch_assoc();
                $starttime = $row["schedstarttime"];
                $endtime = $row["schedendtime"];
                $date = $row["scheddate"];
                $platform = $row["platform"] ? "Online" : "Offline";
                $id = $row["id"];
                echo "<option value='$id'>$date $starttime - $endtime | $platform</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <label>Teacher:</label>
        <select id="teacherSelect" name="teacher" required>
            <option value="">Select Teacher</option>
        </select>
        <br><br>

        <label>Student:</label>
        <select name="student" required>
            <option value="">Select Student</option>
            <?php
            $studentlist = $conn->query("SELECT * FROM  `student` ORDER BY `lname` ASC;");

            for ($i = 0; $i < $studentlist->num_rows; $i++) {
                $row = $studentlist->fetch_assoc();
                $fname = $row["fname"];
                $lname = $row["lname"];
                $id = $row["id"];
                echo "<option value='$id'>$lname, $fname</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <input type="submit" value="Add Booking">
    </form>

    <h2>Delete Booking</h2>
    <form action="delete-booking.php" method="post">
        <label>Bookings:</label>
        <select name="booking" required>
            <option value="">Select Booking</option>
            <?php
            $bookinglist = $conn->query("SELECT 
                    b.id AS `booking_id`,
                    s.scheddate,
                    s.schedstarttime,
                    s.schedendtime,
                    s.platform,
                    CONCAT(st.lname, ', ', st.fname) AS `student_name`,
                    CONCAT(t.lname, ', ', t.fname) AS `teacher_name`,
                    l.details AS `language_name`
                    FROM `booking` b
                    LEFT JOIN `schedule` s ON b.schedule_id = s.id
                    LEFT JOIN `student` st ON b.student_id = st.id
                    LEFT JOIN `teacher` t ON s.teacher_id = t.id
                    LEFT JOIN `language` l ON s.language_id = l.id;");

            for ($i = 0; $i < $bookinglist->num_rows; $i++) {
                $row = $bookinglist->fetch_assoc();
                $scheddate = $row["scheddate"];
                $schedstarttime = $row["schedstarttime"];
                $schedendtime = $row["schedendtime"];
                $platform = $row["platform"] ? "Online" : "Offline";
                $student_name = $row["student_name"];
                $teacher_name = $row["teacher_name"];
                $language_name = $row["language_name"];
                $id = $row["booking_id"];
                echo "<option value='$id'>$scheddate $schedstarttime-$schedendtime | $student_name | $teacher_name | $platform</option><br/>";
            }
            ;
            ?>
        </select>
        <br><br>

        <input type="submit" value="Delete Booking">
    </form>
    <br><br><br><br>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const scheduleSelect = document.getElementById("scheduleSelect");
            const teacherSelect = document.getElementById("teacherSelect");

            scheduleSelect.addEventListener("change", function () {
                let sched_id = scheduleSelect.value;

                // Clear the teachers dropdown if no timeslot is selected
                if (sched_id === "") {
                    teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
                    return;
                }

                // Use fetch() to get teachers based on the selected timeslot
                fetch("fetch-teacher.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "sched_id=" + encodeURIComponent(sched_id)
                })
                    .then(response => response.text())
                    .then(data => {
                        teacherSelect.innerHTML = data; // Update dropdown
                    })
                    .catch(error => console.error("Error fetching teachers:", error));
            });
        });
    </script>

</body>

</html>