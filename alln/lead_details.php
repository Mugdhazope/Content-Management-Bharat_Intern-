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
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>

      .box-content-header{
       font-size:50px;
       text-decoration:none;
      }
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
input {
    background-color: transparent;
    border: 0px solid;
    height: 20px;
    width: 160px;
    color: black;
    font-size:20px;
    font-weight:bold;

}
    </style>
</head>

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
        <a href="dash.php" class="app-sidebar-link active">
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
          <p>Lead Details</p>
          <p class="time"><?php
$date = date('d-m-Y'); // Format: YYYY-MM-DD
echo $date;
?></p>

        </div>
        
        <div class="projects-section-line">
          <div class="projects-status">
       
          </div>
   
          <div class="view-actions">
            <button class="view-btn list-view" title="List View">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                <line x1="8" y1="6" x2="21" y2="6" />
                <line x1="8" y1="12" x2="21" y2="12" />
                <line x1="8" y1="18" x2="21" y2="18" />
                <line x1="3" y1="6" x2="3.01" y2="6" />
                <line x1="3" y1="12" x2="3.01" y2="12" />
                <line x1="3" y1="18" x2="3.01" y2="18" /></svg>
            </button>
            <button class="view-btn grid-view active" title="Grid View">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" /></svg>
            </button>
          </div>
        </div>

        
        <div class="project-boxes jsGridView">

        <?php

// Check if the lead ID is provided in the URL
if (!isset($_GET["id"])) {
    header("Location: error.php");
    exit();
}

// Retrieve the lead ID from the URL
$leadId = $_GET["id"];

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$loggedInUser = $_SESSION["username"];

// Check if the form is submitted for updating lead details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateLead"])) {
    // Sanitize the lead ID
    $leadId = sanitize($_POST["leadId"]);

    // Retrieve the submitted form data
    $name = sanitize($_POST["name"]);
    $contactNum = sanitize($_POST["contactNum"]);
    $refName = sanitize($_POST["refName"]);
    $refContact = sanitize($_POST["refContact"]);
    $lookFor = sanitize($_POST["lookFor"]);
    $location = sanitize($_POST["location"]);
    $dob = sanitize($_POST["dob"]);
    $caste = sanitize($_POST["caste"]);
    $education = sanitize($_POST["education"]);
    $income = sanitize($_POST["income"]);
    $maritalStatus = sanitize($_POST["maritalStatus"]);
    $requirements = sanitize($_POST["requirements"]);
    $notes = sanitize($_POST["notes"]);
    $deliverables = sanitize($_POST["deliverables"]);
    $assigned = $loggedInUser;
    $status = sanitize($_POST["status"]);
    $payInfo = sanitize($_POST["payInfo"]);
    $gender = sanitize($_POST["gender"]);
    $meet = sanitize($_POST["meet"]);

    // Update the lead details in the database
    $updateSql = "UPDATE leads SET
        name = ?,
        contact_num = ?,
        ref_name = ?,
        ref_contact = ?,
        look_for = ?,
        location = ?,
        dob = ?,
        caste = ?,
        education = ?,
        income = ?,
        marital_status = ?,
        requirements = ?,
        notes = ?,
        deliverables = ?,
        assigned = ?,
        status = ?,
        pay_info = ?,
        gender = ?,
        meet = ?
        WHERE id = ? AND assigned = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssssssssssssssssssss",
        $name,
        $contactNum,
        $refName,
        $refContact,
        $lookFor,
        $location,
        $dob,
        $caste,
        $education,
        $income,
        $maritalStatus,
        $requirements,
        $notes,
        $deliverables,
        $assigned,
        $status,
        $payInfo,
        $gender,
        $meet,
        $leadId,
        $loggedInUser
    );
    if ($stmt->execute()) {
        echo "Lead details updated successfully!";
        // Redirect back to the lead details page
        exit();
    } else {
        echo "Error updating lead details: " . $stmt->error;
    }
}

// Retrieve lead details from the database
$selectSql = "SELECT * FROM leads WHERE id = ? AND assigned = '$loggedInUser'";
$stmt = $conn->prepare($selectSql);
$stmt->bind_param("s", $leadId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a lead with the provided ID exists
if ($result->num_rows === 0) {
    header("Location:error.php");
    exit();
}

// Fetch lead details
$lead = $result->fetch_assoc();

// Close the database connection
$stmt->close();
$conn->close();
?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $leadId); ?>">
        <input type="hidden" name="leadId" value="<?php echo $leadId; ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $lead["name"]; ?>"><br>

        <label for="contactNum">Contact Number:</label>
        <input type="text" name="contactNum" id="contactNum" value="<?php echo $lead["contact_num"]; ?>"><br>

        <label for="meet">Next Meeting:</label>
        <input type="date" name="meet" id="meet" value="<?php echo $lead["meet"]; ?>"><br>

        <label for="refName">Reference Name:</label>
        <input type="text" name="refName" id="refName" value="<?php echo $lead["ref_name"]; ?>"><br>

        <label for="refContact">Reference Contact Number:</label>
        <input type="text" name="refContact" id="refContact" value="<?php echo $lead["ref_contact"]; ?>"><br>

        <label for="lookFor">Looking For:</label>
        <input type="text" name="lookFor" id="lookFor" value="<?php echo $lead["look_for"]; ?>"><br>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo $lead["location"]; ?>"><br>

        <label for="dob">Age</label>
        <input type="text" name="dob" id="dob" value="<?php echo $lead["dob"]; ?>"><br>

        <label for="caste">Caste:</label>
        <input type="text" name="caste" id="caste" value="<?php echo $lead["caste"]; ?>"><br>

        <label for="education">Education:</label>
        <input type="text" name="education" id="education" value="<?php echo $lead["education"]; ?>"><br>

        <label for="income">Income:</label>
        <input type="text" name="income" id="income" value="<?php echo $lead["income"]; ?>"><br>

        <label for="maritalStatus">Marital Status:</label>
    <select name="maritalStatus" id="maritalStatus">
        <option value="Single" <?php echo ($lead["marital_status"] === "Single") ? "selected" : ""; ?>>Single</option>
        <option value="Married" <?php echo ($lead["marital_status"] === "Married") ? "selected" : ""; ?>>Married</option>
        <option value="Divorced" <?php echo ($lead["marital_status"] === "Divorced") ? "selected" : ""; ?>>Divorced</option>
        <option value="Parent" <?php echo ($lead["marital_status"] === "Parent") ? "selected" : ""; ?>>Has Kids (Single)</option>
        <option value="Widow" <?php echo ($lead["marital_status"] === "Widow") ? "selected" : ""; ?>>Widow</option>
        <option value="Awaited" <?php echo ($lead["marital_status"] === "Awaited") ? "selected" : ""; ?>>Awaited Divorce</option>     
    </select><br>

        <label for="requirements">Requirements:</label>
        <input type="text" name="requirements" id="requirements" value="<?php echo $lead["requirements"]; ?>"><br>

        <label for="notes">Notes:</label>
        <textarea name="notes" id="notes" class="form-control"><?php echo $lead["notes"]; ?></textarea>

        <label for="deliverables">Extra Numbers:</label>
        <input type="text" name="deliverables" id="deliverables" value="<?php echo $lead["deliverables"]; ?>"><br>

        <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="potential" <?php echo ($lead["status"] === "potential") ? "selected" : ""; ?>>Potential</option>
        <option value="uncalled" <?php echo ($lead["status"] === "uncalled") ? "selected" : ""; ?>>Uncalled</option>
        <option value="blocked" <?php echo ($lead["status"] === "blocked") ? "selected" : ""; ?>>Blocked</option>
        <option value="onhold" <?php echo ($lead["status"] === "onhold") ? "selected" : ""; ?>>On-Hold</option>
        <option value="paid" <?php echo ($lead["status"] === "paid") ? "selected" : ""; ?>>Paid</option>
    </select><br>

        <label for="payInfo">Payment Information (If Any):</label>
        <input type="text" name="payInfo" id="payInfo" value="<?php echo $lead["pay_info"]; ?>"><br>

        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
        <option value="male" <?php echo ($lead["gender"] === "male") ? "selected" : ""; ?>>Male</option>
        <option value="female" <?php echo ($lead["gender"] === "female") ? "selected" : ""; ?>>Female</option>
        <option value="other" <?php echo ($lead["gender"] === "other") ? "selected" : ""; ?>>Other</option>
    </select><br>

        <button><input type="submit" name="updateLead"></button>
    </form>

  
  </div>
  </div>

  <script src="script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
