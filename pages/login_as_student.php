<?php
session_start();
require '../includes/database_connection.php';
$studentNumber = '';
$error_message = '';

// If logged in
if (isset($_SESSION['student_number'])) {
  // Redirect to student homepage
  header("Location: student_homepage.php");
}
if (isset($_SESSION['id_number'])) {
  // Redirect to professor homepage
  header("Location: professor_homepage.php");
}

// Check if the form is submitted
if (isset($_POST['login'])) {
  // Retrieve the values from the form
  $studentNumber = $_POST['student-number'];
  $password = $_POST['password'];

  // Connect to database
  require '../includes/database_connection.php';

  // SQL query
  $sql = "SELECT * FROM students WHERE student_number = '$studentNumber'";
  $result = mysqli_query($connection, $sql);

  // Check if the query was successful
  if ($result) {
    $students = mysqli_fetch_assoc($result);

    // Check the password
    if ($students && password_verify($password, $students['password'])) {
      $_SESSION['student_number'] = $studentNumber;
      header("Location: student_homepage.php");
      exit();
    } else {
      $error_message = 'Student number or password is incorrect!';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PUP HDF Attendance System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/login_as.css" />
  </head>
  <body>
    <section class="main">
      <div class="left">
        <div class="login-div-center">
          <h1>STUDENT LOGIN</h1>
          <form method="POST" class="login-form">
            <div class="login-textbox-container">
              <img
                src="../assets/images/icons/person.svg"
                class="textbox-icon"
              />
              <input
                type="text"
                class="login-textbox"
                name="student-number"
                value="<?php echo htmlspecialchars($studentNumber); ?>"
                placeholder="Student Number"
              />
            </div>
            <div class="login-textbox-container">
              <img src="../assets/images/icons/lock.svg" class="textbox-icon" />
              <input
                type="password"
                class="login-textbox"
                name="password"
                placeholder="Password"
              />
            </div>
            <p class="error-message"><?php echo $error_message ?></p>
            <center>
              <button type="submit" name="login" class="login-button">LOGIN</button>
            </center>
          </form>
          <a href="forgot_password.php">
            <p class="forgot-password-text">Forgot your password?</p>
          </a>
        </div>
      </div>
      <div class="right">
        <img src="..\assets\images\graphics\students.png" />
      </div>
    </section>
  </body>
</html>
