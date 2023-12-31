<?php
$idNumber = '';
$error_message = '';
// Check if the form is submitted
if (isset($_POST['login'])) {
    // Retrieve the values from the form
    $idNumber = $_POST['id-number'];
    $password = $_POST['password'];

    // Perform authentication or any other necessary actions here
    // You can add your PHP login logic here
    // For example, you might want to validate the credentials against a database

    // After successful login, you can redirect the user to the student homepage
    if ($idNumber == 'admin' && $password == 'admin') {
      header("Location: professor_homepage.php");
      exit();
    } else {
      $error_message = 'ID number or password is incorrect!';
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
    <link rel="stylesheet" href="../css/login_as_student.css" />
  </head>
  <body>
    <div class="login-div">
      <section class="login-div-L">
        <div class="login-div-center">
          <div class="login-title">
            <a href="professor_homepage.html"
              >
              <a1>PROFESSOR LOGIN</a1>
            </a>
          </div>
          <form action="" method="POST" class="login-form">
            <div class="login-textbox-container">
              <img
                src="../assets/images/icons/person.png"
                class="textbox-icon"
              />
              <input
                type="textbox"
                class="login-textbox"
                name="id-number"
                value="<?php echo htmlspecialchars($idNumber); ?>"
                placeholder="ID Number"
              />
            </div>
            <div class="login-textbox-container">
              <img src="../assets/images/icons/lock.png" class="textbox-icon" />
              <input
                type="password"
                class="login-textbox"
                name="password"
                placeholder="Password"
              />
            </div>
            <p class="error-message"><?php echo $error_message ?></p>
            <div>
              <button type="submit" name="login" class="login-button">LOGIN</button>
            </div>
          </form>
          <div>
            <a href="forgot_password.php"
              ><p class="forgot-password-text">Forgot your password?</p></a
            >
          </div>
        </div>
      </section>
      <section class="login-div-R">
        <div>
          <img
            src="..\assets\images\graphics\professor.png"
            class="login-graphics"
          />
        </div>
      </section>
    </div>
    <!-- <script src="../scripts.js"></script> -->
  </body>
</html>
