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
    <title>Sales</title>
    <link rel="stylesheet" href="styles.css">

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
$loggedInUser = $_SESSION["username"];
// Function to calculate and return the total sales for today
function calculateTotalSalesToday()
{
  $loggedInUser = $_SESSION["username"];
  global $conn; // Make the database connection variable accessible in this function

  // Get the current date
  $currentDate = date('Y-m-d');

  // Query to calculate the total sales for today
  $query = "SELECT SUM(pay_amount) AS total_sales FROM payments WHERE date_of_pay = '$currentDate' AND assigned = '$loggedInUser'";

  // Execute the query using the existing database connection
  $result = mysqli_query($conn, $query);

  // Check if the query was successful
  if (!$result) {
      die('Query error: ' . mysqli_error($conn));
  }

  // Fetch the result
  $row = mysqli_fetch_assoc($result);

  // Return the total sales for today
  return $row['total_sales'];
}

// Function to calculate and return the total sales for the last 7 days
function calculateTotalSalesLast7Days()
{
  $loggedInUser = $_SESSION["username"];
    // Connect to the database
     global $conn; 

    // Calculate the date 7 days ago
    $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

    // Query to calculate the total sales for the last 7 days
    $query = "SELECT SUM(pay_amount) AS total_sales FROM payments WHERE date_of_pay >= '$sevenDaysAgo' AND assigned = '$loggedInUser'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
      die('Query error: ' . mysqli_error($conn));
  }
    // Fetch the result
    $row = mysqli_fetch_assoc($result);

    // Return the total sales for the last 7 days
    return $row['total_sales'];
}

// Function to calculate and return the total sales for the current month
function calculateTotalSalesThisMonth()
{
  $loggedInUser = $_SESSION["username"];
     // Connect to the database
     global $conn; 

    // Get the current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Query to calculate the total sales for the current month
    $query = "SELECT SUM(pay_amount) AS total_sales FROM payments WHERE MONTH(date_of_pay) = $currentMonth AND YEAR(date_of_pay) = $currentYear AND assigned = '$loggedInUser'";

    // Execute the query
    $result = mysqli_query($conn, $query);


    if (!$result) {
      die('Query error: ' . mysqli_error($conn));
  }

    // Fetch the result
    $row = mysqli_fetch_assoc($result);

    // Return the total sales for the current month
    return $row['total_sales'];
}

// Function to calculate and return the total sales for the current year
function calculateTotalSalesThisYear()
{
  $loggedInUser = $_SESSION["username"];
     // Connect to the database
     global $conn; 

    // Get the current year
    $currentYear = date('Y');

    // Query to calculate the total sales for the current year
    $query = "SELECT SUM(pay_amount) AS total_sales FROM payments WHERE YEAR(date_of_pay) = $currentYear AND assigned = '$loggedInUser'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
      die('Query error: ' . mysqli_error($conn));
  }

    // Fetch the result
    $row = mysqli_fetch_assoc($result);


    // Return the total sales for the current year
    return $row['total_sales'];
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
          <p>Sales</p>
          <p class="time"><?php
$date = date('d-m-Y'); // Format: YYYY-MM-DD
echo $date;
?></p>

        </div>
        <div class="projects-section-line">
          <div class="projects-status">
            <div class="item-status">
              <span class="status-number">₹<?php
            // Calculate and display total sales today
            $totalSalesToday = calculateTotalSalesToday();
            echo $totalSalesToday;
            ?></span>
              <span class="status-type">Sales Today</span>
            </div>
            <div class="item-status">
              <span class="status-number">₹<?php
            // Calculate and display total sales last 7 days
            $totalSalesLast7Days = calculateTotalSalesLast7Days();
            echo $totalSalesLast7Days;
            ?></span>
              <span class="status-type">Sale this Week</span>
            </div>
            <div class="item-status">
              <span class="status-number">₹<?php
            // Calculate and display total sales this month
            $totalSalesThisMonth = calculateTotalSalesThisMonth();
            echo $totalSalesThisMonth;
            ?></span>
              <span class="status-type">Sales this Month</span>
            </div>
            <div class="item-status">
              <span class="status-number">₹<?php
            // Calculate and display total sales this year
            $totalSalesThisYear = calculateTotalSalesThisYear();
            echo $totalSalesThisYear;
            ?></span>
              <span class="status-type">Sales this Year</span>
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
            <a href="histroy.php"><button class="btn" style="margin:5px; padding:5px; height=5px;">Search History</button></a>
            <a href="report.php"><button class="btn" style="margin:5px; padding:5px; height=5px;">Check History</button></a>
          </div>
        </div>
        
<h3>Add Sales</h3>     
<div class="project-boxes jsGridView">
     
<br>
<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data

    $contactNum = $_POST['contact_num'];

    $payAmount = $_POST['pay_amount'];
    $payMethod = $_POST['pay_method'];
    $dateOfPay = date('Y-m-d');
    $assigned = $_SESSION['username'];

    // Search for the lead in the leads table
    $searchQuery = "SELECT * FROM leads WHERE contact_num = '$contactNum'";
    $result = $conn->query($searchQuery);

    if ($result->num_rows > 0) {
        // Lead found, insert sales into the payments table
        $leadRow = $result->fetch_assoc();
        $leadId = $leadRow['id'];

        $insertQuery = "INSERT INTO payments (id, pay_amount, date_of_pay, pay_method, assigned) VALUES ('$leadId', '$payAmount', '$dateOfPay', '$payMethod', '$assigned')";
        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Sales added successfully.');</script>";
        } else {
            echo "<script>alert('Error Adding Sales.');</script> " . $conn->error;
        }
    } else {
        echo "<script>alert('LEAD NOT FOUND, Check Number');</script>";
    }
}
?>
         <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form1">
<br>

    <label for="contact_num"  class="form-label">Contact Number:</label>
    <input class="form-control" type="text" name="contact_num" id="contact_num" required><br>

    <label for="location" class="form-label">Amount Paid:</label>
    <input class="form-control" type="text" name="pay_amount" required><br>

    <label for="dob" class="form-label">Payment Method</label>
    <input class="form-control" type="text" name="pay_method" required><br>

  <input class="button text" type="submit">

</form>

  </div>

  </div>
  </div>

  <script src="script.js"></script>
</body>
</html>