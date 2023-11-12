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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
      .box-content-header{
       font-size:50px;
       text-decoration:none;
      }
      .btn-light{
    margin:1px;
    background-color:#f2f2f2;
}
.btn{
    margin:1px;
}
.center{
    padding: 70px 0;
    text-align: center;
    position: relative;
    top:-30px;
}
.show{
  padding-top: 65px;
    padding-left: 17px;
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
$totalMeetings = fetchSingleValue("SELECT COUNT(*) FROM meetings WHERE assigned = '$loggedInUser' AND DATE(meeting_date) = CURDATE()");

$salesToday = fetchSingleValue("SELECT SUM(pay_amount) FROM payments WHERE assigned = '$loggedInUser' AND DATE(date_of_pay) = CURDATE()");

$uncalled = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'uncalled'");

$onhold = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'onhold'");

$potential = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'potential'");

$blocked = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser' AND status = 'blocked'");

$pendingmeet = fetchSingleValue("SELECT COUNT(*) FROM meetings WHERE assigned = '$loggedInUser' AND DATE(meeting_date) != CURDATE() AND status=''");

?>


<body>
<div class="app-container">
    <div class="app-header">
      <div class="app-header-left">
        <span class="app-icon"></span>
        <p class="app-name">Dashboard</p>
      </div>

      <div class="app-header-right">


      <form class="notification-btn">
        <div class="projects-section-line">
          <div class="projects-status">
          </div>
        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                Filter
            </button>
            <!-- Filter options -->
            <div class="collapse" id="filterCollapse">
                <div style="margin-top: 10px;">
                    <label for="dobFilter">From :</label>
                    <input type="date" name="dobFilter" id="dobFilter" value="<?php echo $dobFilter; ?>">
                </div>

                <div style="margin-top: 10px;">
                    <label for="dobFilter">To :</label>
                    <input type="date" name="dobFilter" id="dobFilter" value="<?php echo $dobFilter; ?>">
                </div>

                <button type="submit" name="search" style="margin-top: 10px;">Apply Filters</button>
            </div>
        </div>
      </form>


      
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
        <a href="sale.php" class="app-sidebar-link active">
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
          <p>Report / Search Histroy</p>
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

        

       <?php
       
// Filter variables
$filterId = isset($_GET['filterId']) ? $_GET['filterId'] : "";
$filterName = isset($_GET['filterName']) ? $_GET['filterName'] : "";
$filterContact = isset($_GET['filterContact']) ? $_GET['filterContact'] : "";

// Query to retrieve data from payments and leads tables
$query = "SELECT p.id, p.pay_amount, l.name, l.contact_num, l.status 
          FROM payments p 
          INNER JOIN leads l ON p.id = l.id";

// Apply filters if provided
if ($filterId != "") {
    $query .= " WHERE p.id = '$filterId'";
}
if ($filterName != "") {
    $query .= " AND l.name LIKE '%$filterName%'";
}
if ($filterContact != "") {
    $query .= " AND l.contact_num = '$filterContact'";
}

// Execute the query
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Sum of Amount</th><th>Name</th><th>Contact Number</th><th>Status</th><th>Delete</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $sumAmount = $row['pay_amount'];
        $name = $row['name'];
        $contactNum = $row['contact_num'];
        $status = $row['status'];

        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$sumAmount</td>";
        echo "<td>$name</td>";
        echo "<td>$contactNum</td>";
        echo "<td>$status</td>";
        echo "<td><button>Delete</button></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();
?>

  </div>
  </div>

  <script src="script.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>