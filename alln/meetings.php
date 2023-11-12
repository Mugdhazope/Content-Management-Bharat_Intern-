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
    <title>Meetings</title>
    <link rel="stylesheet" href="styles.css">
</head>

<?php

// Function to execute SQL queries and fetch a single value
function fetchSingleValue($query)
{
    global $conn;
    $result = $conn->query($query);
    return ($result) ? $result->fetch_row()[0] : 0;
}


// Total number of meetings with today's date
$totalMeetings = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND DATE(meet) = CURDATE() AND done!=CURDATE()");

$pendingMeetings = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND DATE(meet)!= CURDATE() AND done!=meet AND DATE(done)<DATE(meet) AND DATE(meet)<CURDATE()");
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
        <a href="meetings.php" class="app-sidebar-link active">
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
          <p>Meetings</p>
          <p class="time"><?php
$date = date('d-m-Y'); // Format: YYYY-MM-DD
echo $date;
?></p>

        </div>
        <div class="projects-section-line">
          <div class="projects-status">
            <div class="item-status">
              <span class="status-number"><?php echo $totalMeetings; ?></span>
              <span class="status-type">Todays Meetings</span>
            </div>
            <div class="item-status">
              <span class="status-number"><?php echo $pendingMeetings; ?></span>
              <span class="status-type">Total Pending Meets</span>
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


        <div class="project-boxes jsGridView">

        <?php

// Get today's date
$today = date('Y-m-d');

// Retrieve today's meetings for the logged-in user
$userId = $_SESSION['username'];
$query = "SELECT * FROM leads WHERE assigned = '$userId' AND meet = '$today'";
$result = $conn->query($query);


// Check if any meetings found
if ($result->num_rows > 0) {
    // Meetings found, display them
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $meet = $row['meet'];
        $name = $row['name'];
        $contact = $row['contact_num'];
       
            echo "
            <div class='project-box-wrapper'>
                <div class='project-box' style='background-color: #e9e7fd;'>
                    <div class='project-box-header'>
                        <span>$id</span>
                        <div class='more-wrapper'>
                            $meet
                        </div>
                    </div>
                    <div class='project-box-content-header'>
                        <p class='box-content-header'>" . $name . "</p>
                        <p class='box-content-subheader'>" . $contact . "</p>
                    </div>
                    <div class='project-box-footer'>
                        <div class='participants'>
                            <button onclick=\"showLeadDetails('$id')\" class='add-participant' style='color: #e9e7fd;'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-pen-fill' viewBox='0 0 16 16'>
                                    <path d='m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z'/>
                                </svg>
                            </button>
                        </div>
                        <div class='days-left' style='color: #4f3ff0;'>
                            <a onclick=\"updateDate('$id')\" add-participant>
                                <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' class='bi bi-check-lg' viewBox='0 0 16 16'>
                                    <path d='M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z'/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ";
    }
} else {
    echo "No meetings scheduled for today.";
}

$query = "SELECT * FROM leads WHERE assigned = '$userId' AND DATE(meet) != '$today' AND DATE(meet) < '$today'";
$result = $conn->query($query);


// Check if any meetings found
if ($result->num_rows > 0) {
    // Meetings found, display them
    while ($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $meet = $row['meet'];
      $name = $row['name'];
      $contact = $row['contact_num'];

            echo "
            <div class='project-box-wrapper'>
                <div class='project-box' style='background-color: #ffd3e2;'>
                    <div class='project-box-header'>
                        <span>$id</span>
                        <div class='more-wrapper'>
                        $meet
                        </div>
                    </div>
                    <div class='project-box-content-header'>
                        <p class='box-content-header'>" . $name . "</p>
                        <p class='box-content-subheader'>" . $contact . "</p>
                    </div>
                    <div class='project-box-footer'>
                        <div class='participants'>
                            <button onclick=\"showLeadDetails('$id')\" class='add-participant' style='color: #ffd3e2;'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-pen-fill' viewBox='0 0 16 16'>
                                    <path d='m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z'/>
                                </svg>
                            </button>
                        </div>
                        <div class='days-left' style='color: #4f3ff0;'>
                            <a onclick=\"updateDate('$id')\" add-participant>
                                <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' class='bi bi-check-lg' viewBox='0 0 16 16'>
                                    <path d='M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z'/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ";
    }
} else {
    echo "No Pending Meetings.";
}

$conn->close();
?>


     
  </div>
  </div>
  <script>

function updateDate(id) {
  // Send an AJAX request to the server
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_date.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // Handle the response from the server
      var response = xhr.responseText;
      alert(response); // Display a message or perform additional actions
    }
  };
  xhr.send("id=" + id);
}

function showLeadDetails(leadId) {
    // Redirect to a page to display lead details
    window.location.href = "lead_details.php?id=" + leadId;
}
</script>
  <script src="script.js"></script>
</body>
</html>