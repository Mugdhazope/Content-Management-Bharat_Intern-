<?php
// Start the session
session_start();

require_once "connection.php";

// Retrieve the database connection
$conn = connectToDatabase();


// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}
$loggedInUser = $_SESSION["username"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Leads</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600" rel="stylesheet">

    <!-- custom css -->

    <style>
      .project-boxes.jsGridView {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    align-content: stretch;
    justify-content: center;
    }

.project-boxes.jsGridView .project-box-wrapper {
    width: 90%;
}
    </style>
</head>


<?php

    // Check if the lead form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve lead information from the form
        $name = $_POST["name"];
        $contactNum = $_POST["contact_num"];
        $refName = $_POST["ref_name"];
        $refContact = $_POST["ref_contact"];
        $lookFor = $_POST["look_for"];
        $location = $_POST["location"];
        $dob = $_POST["dob"];
        $caste = $_POST["caste"];
        $education = $_POST["education"];
        $income = $_POST["income"];
        $maritalStatus = $_POST["marital_status"];
        $requirements = $_POST["requirements"];
        $notes = $_POST["notes"];
        $deliverables = $_POST["deliverables"];
        $status = $_POST["status"];
        $gender = $_POST["gender"];
        $meet = $_POST["meet"];

      

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a SQL statement to insert the lead into the table
        $stmt = $conn->prepare("INSERT INTO leads (name, contact_num, ref_name, ref_contact, look_for, location, dob, caste, education, income, marital_status, requirements, notes, deliverables, assigned , status , gender , meet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssssss", $name, $contactNum, $refName, $refContact, $lookFor, $location, $dob, $caste, $education, $income, $maritalStatus, $requirements, $notes, $deliverables, $loggedInUser , $status , $gender ,$meet);
        $stmt->execute();

        // Close the database connection
        $stmt->close();
        $conn->close();

        echo "<script>alert('Lead $name Added Successfully ,');</script>";
    }
    ?>



<body>
<div class="app-container">
    <div class="app-header">
      <div class="app-header-left">
        <span class="app-icon"></span>
        <p class="app-name">Dashboard</p>
      </div>

      <div class="app-header-right">
        <button class="mode-switch" title="Switch Theme">
          <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
            <defs></defs>
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
          </svg>
        </button>
        <a href="addlead.php">
        <button class="add-btn" title="Add New Project">
          <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" /></svg>
        </button></a>
        <!-- <button class="notification-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13.73 21a2 2 0 0 1-3.46 0" /></svg>
        </button> -->
        <button class="profile-btn">
        <span><a href="logout.php">Logout, </a><?php echo $_SESSION['username'];?></span>
        </button>
      </div>
      <button class="messages-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" /></svg>
      </button>
    </div>
    <div class="app-content">
      <div class="app-sidebar">
        <a href="dash.php" class="app-sidebar-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" /></svg>
        </a>
        <a href="sale.php" class="app-sidebar-link">
          <svg class="link-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="feather feather-pie-chart" viewBox="0 0 24 24">
            <defs />
            <path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z" />
          </svg>
        </a>
        <a href="meetings.php" class="app-sidebar-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" /></svg>
        </a>
        <a href="leads.php" class="app-sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
          </svg>
        </a>
      </div>
      <div class="projects-section">
        <div class="projects-section-header">
          <p>Add Leads</p>
          <p class="time"><?php
$date = date('d-m-Y'); // Format: YYYY-MM-DD
echo $date;
?></p>

        </div>
       
        <div class="project-boxes jsGridView">
          <div class="project-box-wrapper">
        

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form1">
<br>

    <label for="name" class="form-label">Name:</label>
    <input class="form-control" type="text" name="name" id="name" required><br>

    <label for="contact_num"  class="form-label">Contact Number:</label>
    <input class="form-control" type="text" name="contact_num" id="contact_num" required><br>

    <label for="ref_name" class="form-label">Reference Name:</label>
    <input class="form-control" type="text" name="ref_name" id="ref_name"><br>

    <label for="ref_contact" class="form-label">Reference Contact:</label>
    <input class="form-control" type="text" name="ref_contact" id="ref_contact"><br>

    <label for="look_for" class="form-label">Looking For:</label>
    <input class="form-control" type="text" name="look_for" id="look_for"><br>

    <label for="location" class="form-label">Location:</label>
    <input class="form-control" type="text" name="location" id="location"><br>

    <label for="dob" class="form-label">Age</label>
    <input class="form-control" type="text" name="dob" id="dob"><br>
        
    <label for="caste" class="form-label">Caste:</label>
    <input class="form-control" type="text" name="caste" id="caste"><br>

    <label for="education" class="form-label">Education:</label>
    <input class="form-control" type="text" name="education" id="education"><br>

    <label for="income" class="form-label">Income:</label>
    <input class="form-control" type="text" name="income" id="income"><br>

    <label for="marital_status" class="form-label">Marital Status:</label>
    <select class="form-control" name="marital_status" id="marital_status">
        <option value="Single">Single</option>
        <option value="Married">Married</option>
        <option value="Divorced">Divorced</option>
        <option value="Parent">Has Kids (Single)</option>
        <option value="Widow">Widow</option>
        <option value="Awaited">Awaited Divorce</option>
    </select><br>

    <label for="gender" class="form-label">Gender:</label>
    <select class="form-control" name="gender" id="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br>

    <label for="requirements" class="form-label">Requirements:</label>
    <input class="form-control" type="text" name="requirements" id="requirements"><br>

    <label for="notes" class="form-label">Notes:</label>
    <textarea type="text" name="notes" id="notes" class="form-control"></textarea>

    <label for="deliverables" class="form-label">Extra Contact:</label>
    <input class="form-control" type="text" name="deliverables" id="deliverables"><br>

    <label for="status" class="form-label">Status:</label>
    <select class="form-control" name="status" id="status">
        <option value="potential">Potential</option>
        <option value="uncalled">Uncalled</option>
        <option value="onhold">On-Hold</option>
        <option value="dead">Dead</option>
        <option value="paid">Paid</option>
        <option value="blocked">Blocked</option>
    </select><br>

    <label for="meet" class="form-label">Next Meeting:</label>
    <input class="form-control" type="date" name="meet" id="meet"><br><br>


  <input class="button text" type="submit">

</form>
 </div></div></div>
  </div>
  </div>

  <script src="script.js"></>
</body>
</html>