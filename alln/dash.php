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
    </style>
</head>

<?php

// Function to execute SQL queries and fetch a single value
function fetchSingleValue($query)
{
    global $conn;
    $result = $conn->query($query);
    return ($result) ? $result->fetch_row()[0] : 0;
}

$totalLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser'");

// Total number of leads with status = 'paid'
$totalPaidLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'paid' AND assigned = '$loggedInUser'");


// Total amount of sales in the current month from the payments table
$totalSales = fetchSingleValue("SELECT SUM(pay_amount) FROM payments WHERE assigned = '$loggedInUser' AND MONTH(date_of_pay) = MONTH(CURRENT_DATE())");

// Total number of meetings with today's date
$totalMeetings = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND DATE(meet) = CURDATE()");

$salesToday = fetchSingleValue("SELECT SUM(pay_amount) FROM payments WHERE assigned = '$loggedInUser' AND DATE(date_of_pay) = CURDATE()");

$uncalled = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'uncalled'");

$onhold = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'onhold'");

$potential = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'potential'");

$blocked = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'blocked'");

$pendingmeet = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND DATE(meet)!= CURDATE() AND DATE(meet)<CURDATE()");

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
          <p>Home</p>
          <p class="time"><?php
$date = date('d-m-Y'); // Format: YYYY-MM-DD
echo $date;
?></p>

        </div>
        
        <div class="projects-section-line">
          <div class="projects-status">
            <div class="item-status">
              <span class="status-number"><?php echo $totalLeads; ?></span>
              <span class="status-type">Total Leads</span>
            </div>
            <div class="item-status">
              <span class="status-number"><?php echo $totalPaidLeads; ?></span>
              <span class="status-type">Paid Lead</span>
            </div>
            <div class="item-status">
              <span class="status-number"><?php echo $totalMeetings; ?></span>
              <span class="status-type">Todays Meetings</span>
            </div>
            <div class="item-status">
              <span class="status-number">₹<?php echo $totalSales; ?></span>
              <span class="status-type">Total Sales</span>
            </div>
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

        <h1>Welcome to MyCrm , <?php echo"$loggedInUser" ?></h1>
        <div class="project-boxes jsGridView">


      <div class="project-box-wrapper">
        <div class="project-box" style="background-color: #e9e7fd;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header"><?php echo"$uncalled" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #4f3ff0;">
              Uncalled Leads
            </div>
          </div>
        </div>
      </div>

      <div class="project-box-wrapper">
      <div class="project-box" style="background-color: #096c6;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header"><?php echo"$potential" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #096c6;">
              Potential Leads
            </div>
          </div>
        </div>
      </div>

      <div class="project-box-wrapper">
      <div class="project-box" style="background-color: #ffd3e2;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header"><?php echo"$blocked" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #ffd32;">
              Blocked Leads
            </div>
          </div>
        </div>
      </div>

      <div class="project-box-wrapper">
      <div class="project-box" style="background-color: #c8f7dc;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header"><?php echo"$pendingmeet" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #c8fdc;">
              Pending Meetings
            </div>
          </div>
        </div>
      </div>

      <div class="project-box-wrapper">
      <div class="project-box" style="background-color: #34c471;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header"><?php echo"$onhold" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #34c71;">
              On-Hold Leads
            </div>
          </div>
        </div>
      </div>
      

      <div class="project-box-wrapper">
      <div class="project-box" style="background-color: #4067f9;">
          <div class="project-box-header">
            <div class="more-wrapper">
            </div>
          </div>
          <div class="project-box-content-header">
            <p class="box-content-header" style="color:white;">₹<?php echo"$salesToday" ?></p>
          </div>
          <div class="project-box-footer">
            <div class="participants">
            </div>
            <div class="days-left" style="color: #407f9;">
              Sales Today
            </div>
          </div>
        </div>
      </div>
  
  </div>
  </div>

  <script src="script.js"></script>
</body>
</html>