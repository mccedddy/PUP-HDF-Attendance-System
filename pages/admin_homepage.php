<?php
session_start();
require '../includes/database_connection.php';

// Clear selection
unset($_SESSION['selected_section']);

// If logged in
if (isset($_SESSION['student_number'])) {
  // Redirect to student homepage
  header("Location: student_homepage.php");
}
if (isset($_SESSION['id_number'])) {
  $idNumber = $_SESSION['id_number'];

  // Redirect to professor homepage
  if ($idNumber != 'admin') {
    header("Location: professor_homepage.php");
  }

  // SQL query
  $sql = "SELECT * FROM professors WHERE id_number = '$idNumber'";
  $result = mysqli_query($connection, $sql);

  // Check if the query was successful
  if ($result) {
    $professor = mysqli_fetch_assoc($result);

    // Get professor info
    if ($professor) {
      $name = strtoupper($professor['last_name']) . ', ' . strtoupper($professor['first_name']);
      $idNumber = $professor['id_number'];
    }
        
    // Free result from memory
    mysqli_free_result($result);
  } else {
    echo 'Error: ' . mysqli_error($connection);
  }
    
  // Close database connection
  mysqli_close($connection);
} else {
  // Redirect to login
  header("Location: ../index.php");
}

// Logout
if (isset($_POST['logout'])) {
  require '../includes/logout.php';
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
    <link rel="stylesheet" href="../css/admin_section_page.css" />
  </head>
  <body>
    <nav class="navbar">
      <div class="navbar-top">
        <img src="..\assets\images\icons\arrow_left.svg" id="closeNavbar" class="nav-button" onclick="toggleMobileNavbar()"/>
        <a onclick="toAdminHomepage()"><img src="..\assets\images\logos\pup_logo.png" class="logo"/></a>
        <a onclick="toSection()"><img src="..\assets\images\icons\group.svg" class="nav-button"/></a>
        <a onclick="toSchedule()"><img src="..\assets\images\icons\table.svg" class="nav-button"/></a>
        <a onclick="toSubjects()"><img src="..\assets\images\icons\book.svg" class="nav-button"/></a>
        <a onclick="toAnalytics()"><img src="..\assets\images\icons\graph.svg" class="nav-button"/></a>
      </div>
      <form method="POST" class="logout-form">
        <button type="submit" name="logout" class="logout-button">
          <img src="..\assets\images\icons\logout.svg" class="nav-button"/>
        </button>
      </form>
    </nav>
    <section class="main">
      <div class="header">
        <div class="left">
          <div class="mobile-navbar-toggle" onclick="toggleMobileNavbar()">
            <img src="..\assets\images\icons\hamburger.svg" class="hamburger">
          </div>
          <a onclick="toAdminHomepage()"><h1>PUP HDF Attendance System</h1></a>
        </div>
        <div class="right">
          <h5>ADMIN</h5>
        </div>
      </div>
      <h1 class="title">Computer Engineering Department</h1>
      <div class="section-button-container">
        <button class="section-button" onclick="toSection()" onmouseover="changeSectionImage(true)" onmouseout="changeSectionImage(false)">
          <img src="../assets/images/icons/group_large_dark.svg" id="sectionButtonImg" />
          SECTION
        </button>
        <button class="section-button" onclick="toSchedule()" onmouseover="changeScheduleImage(true)" onmouseout="changeScheduleImage(false)">
          <img src="../assets/images/icons/table_large_dark.svg" id="scheduleButtonImg" />
          SCHEDULE
        </button>
        <button class="section-button" onclick="toSubjects()" onmouseover="changeSubjectsImage(true)" onmouseout="changeSubjectsImage(false)">
          <img src="../assets/images/icons/book_large_dark.svg" id="subjectsButtonImg" />
          SUBJECTS
        </button>
        <button class="section-button" onclick="toAnalytics()" onmouseover="changeAnalyticsImage(true)" onmouseout="changeAnalyticsImage(false)">
          <img src="../assets/images/icons/graph_large_dark.svg" id="analyticsButtonImg" />
          ANALYTICS
        </button>
      </div>
    </section>
    <script src="../js/navbar_controller.js"></script>
    <script>
      function toLogin() {
        window.location.href = "../index.php";
        return false;
      }
      function toAdminHomepage() {
        window.location.href = "admin_homepage.php";
        return false;
      }
      function toSection() {
        window.location.href = "admin_section_page.php";
        return false;
      }
      function toSubjects() {
        window.location.href = "admin_subjects_page.php";
        return false;
      }
      function toAnalytics() {
        window.location.href = "admin_analytics_page.php";
        return false;
      }
      function toSchedule() {
        window.location.href = "admin_schedule_page.php";
        return false;
      }
      function toSettings() {
        window.location.href = "admin_settings_page.php";
        return false;
      }
      function changeSectionImage(isHovered) {
    var imgElement = document.getElementById("sectionButtonImg");

    if (isHovered) {
      imgElement.src = "../assets/images/icons/group_large.svg";
    } else {
      imgElement.src = "../assets/images/icons/group_large_dark.svg"; 
    }
  }

  function changeScheduleImage(isHovered) {
    var imgElement = document.getElementById("scheduleButtonImg");

    if (isHovered) {
      imgElement.src = "../assets/images/icons/table_large.svg"; 
    } else {
      imgElement.src = "../assets/images/icons/table_large_dark.svg";
    }
  }

  function changeSubjectsImage(isHovered) {
    var imgElement = document.getElementById("subjectsButtonImg");

    if (isHovered) {
      imgElement.src = "../assets/images/icons/book_large.svg"; 
    } else {
      imgElement.src = "../assets/images/icons/book_large_dark.svg"; 
    }
  }

  function changeAnalyticsImage(isHovered) {
    var imgElement = document.getElementById("analyticsButtonImg");

    if (isHovered) {
      imgElement.src = "../assets/images/icons/graph_large.svg";
    } else {
      imgElement.src = "../assets/images/icons/graph_large_dark.svg"; 
    }
  }
    </script>
  </body>
</html>
