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

if (isset($_POST['platform'])) {
    $platform_id = $_POST['platform'];

    $tistable = $prefix . "_resources.`teachers_in_sched`";
    $scheduletable = $prefix . "_resources.`schedule`";
    //to avoid duplicate entries
    $conn->query("TRUNCATE TABLE $tistable");

    $conn->query("INSERT INTO $tistable (`scheddate`, `schedstarttime`, `schedendtime`, `platform`, `teacher_ids`)
                    SELECT `scheddate`, `schedstarttime`, `schedendtime`, `platform`,
                    GROUP_CONCAT(DISTINCT `teacher_id` ORDER BY `teacher_id` SEPARATOR ',') AS `teacher_ids`
                    FROM $scheduletable WHERE `booking_id` IS NULL AND `platform` = $platform_id
                    GROUP BY `scheddate`, `schedstarttime`, `schedendtime`, `platform`;");

    $schedlist = $conn->query("SELECT * FROM $tistable");

    echo '<option value="">Select Schedule</option>';
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
}
?>