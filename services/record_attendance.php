<?php
date_default_timezone_set('Asia/Manila');

if (isset($_POST['UIDresult'])) {
  require '../includes/database_connection.php';

  $uid = $_POST['UIDresult'];
  $room = $_POST['room'];
  $time = date("H:i");
  $date = date('Y-m-d');
  $dayOfWeek = date('l', strtotime($date));

  // SQL query to fetch student data
  $studentSQL = "SELECT last_name, student_number, section, nfc_uid FROM students WHERE nfc_uid = '$uid'";
  $studentStmt = mysqli_prepare($connection, $studentSQL);
  mysqli_stmt_execute($studentStmt);
  $result = mysqli_stmt_get_result($studentStmt);

  // If no student found
  if ($result == NULL || mysqli_num_rows($result) == 0) {
    echo json_encode(['studentData' => ['last_name' => 'none', 'student_number' => 'none', 'nfc_uid' => $uid]]);
    exit;
  }

  // Get student data
  $studentData = mysqli_fetch_assoc($result);
  $studentNumber = $studentData['student_number'];
  $section = $studentData['section'];
  mysqli_free_result($result);

  // Response: Student Data
  echo json_encode(['studentData' => $studentData]);

  // Get HDF data
  $hdfSQL = "SELECT * 
             FROM hdf 
             WHERE student_number = '$studentNumber'
             AND DATE(timestamp) = '$date'";
  $hdfStmt = mysqli_prepare($connection, $hdfSQL);
  mysqli_stmt_execute($hdfStmt);
  $result = mysqli_stmt_get_result($hdfStmt);

  if ($result && mysqli_num_rows($result) > 0) {
    $hdfData = mysqli_fetch_assoc($result);
    $hdfVerified = $hdfData["verified"];

    if ($hdfVerified == "false") {
      // Response: Not Verified
      echo json_encode(['hdf' => $hdfVerified]); 
    } else {
      echo json_encode(['hdf' => $hdfVerified]); 
    }
  } else {
    // Response: No HDF
    echo json_encode(['hdf' => 'none']); 
    exit;
  }

  // SQL query to retrieve schedule data
  $scheduleSQL = "SELECT * 
          FROM schedule 
          WHERE section = '$section' 
          AND day = '$dayOfWeek' 
          AND '$time' BETWEEN DATE_SUB(start_time, INTERVAL 1 HOUR) AND end_time";
  $scheduleStmt = mysqli_prepare($connection, $scheduleSQL);
  mysqli_stmt_execute($scheduleStmt);
  $result = mysqli_stmt_get_result($scheduleStmt);
  $scheduleData = mysqli_fetch_assoc($result);

  echo json_encode(['DATA' => [$section, $dayOfWeek, $time]]);

  // Response: Schedule Data
  echo json_encode(['scheduleData' => $scheduleData]);

  if ($result && mysqli_num_rows($result) > 0 && $hdfVerified == "true") {
    $scheduleId = $scheduleData['id'];
    $startTime = $scheduleData['start_time'];

    // Check if attendance already recorded
    $checkAttendanceSQL = "SELECT * FROM attendance WHERE student_number = '$studentNumber' AND date = '$date' AND schedule_id = '$scheduleId'";
    $checkAttendanceStmt = mysqli_prepare($connection, $checkAttendanceSQL);
    mysqli_stmt_execute($checkAttendanceStmt);
    $existingAttendance = mysqli_stmt_get_result($checkAttendanceStmt);

    if (mysqli_num_rows($existingAttendance) > 0) {
      echo json_encode(['status' => 'Already recorded']);
      exit;
    }
    mysqli_free_result($existingAttendance);

    // Determine the status
    $lateThreshold = strtotime("$startTime +15 minutes");
    $status = (strtotime($time) > $lateThreshold) ? "Late" : "Present";

    // Insert attendance data
    $insertAttendanceSQL = "INSERT INTO attendance (student_number, room, time, date, status, schedule_id)
                VALUES ('$studentNumber', '$room', '$time', '$date', '$status', '$scheduleId')";
    $insertAttendanceStmt = mysqli_prepare($connection, $insertAttendanceSQL);
    mysqli_stmt_execute($insertAttendanceStmt);

    // Response: Success
    echo json_encode(['status' => $status]);
    
    exit;
  } else {
    // No schedule found
    echo json_encode(['status' => 'No schedule']);
    exit;
  }
}
?>
