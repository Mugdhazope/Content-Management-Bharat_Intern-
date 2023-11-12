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
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>

.btn-light{
    margin:1px;
    background-color:#f2f2f2;
}
.card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

.card {
            flex-wrap: wrap;
            justify-content: center;
            width: 300px;
            height: 200px;
            padding: 10px;
            margin: 10px;
            font-size:30px;
            display: inline-block;
            background: rgba( 255, 255, 255, 0.25 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 2.5px );
-webkit-backdrop-filter: blur( 2.5px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );
        }
.btn{
    margin:1px;
}
        .navbar{
            background: rgba( 248, 231, 28, 0 );
box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
backdrop-filter: blur( 0px );
-webkit-backdrop-filter: blur( 0px );
border-radius: 10px;
border: 1px solid rgba( 255, 255, 255, 0.18 );
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button type="button" class="btn btn-light"><a class="navbar-brand" href="dashboard.php">Dashboard</a></button>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="addleads.php">Add Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="search.php">Search Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="meeto.php">Meetings Today</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="schedule.php">Schedule Meetings</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="uncalled.php">Uncalled Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="potential.php">Potential Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="paid.php">Paid Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="onhold.php">Onhold Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="blocked.php">Blocked Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="sales.php">Sales Report</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-danger"><a class="nav-link" href="logout.php">Logout , <?php echo $_SESSION['username'];?></a></button>
        </li>

      </ul>
    </div>
  </div>
</nav>

<br>
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
    <div class="cards-container">
        <div class="card">
            <h3>Total Sales Today</h3>
            ₹<?php
            // Calculate and display total sales today
            $totalSalesToday = calculateTotalSalesToday();
            echo $totalSalesToday;
            ?>
        </div>
        <div class="card">
            <h3>Total Sales Last 7 Days</h3>
            ₹<?php
            // Calculate and display total sales last 7 days
            $totalSalesLast7Days = calculateTotalSalesLast7Days();
            echo $totalSalesLast7Days;
            ?>
        </div>
        <div class="card">
            <h3>Total Sales This Month</h3>
            ₹<?php
            // Calculate and display total sales this month
            $totalSalesThisMonth = calculateTotalSalesThisMonth();
            echo $totalSalesThisMonth;
            ?>
        </div>
        <div class="card">
            <h3>Total Sales This Year</h3>
            ₹<?php
            // Calculate and display total sales this year
            $totalSalesThisYear = calculateTotalSalesThisYear();
            echo $totalSalesThisYear;
            ?>
        </div>
    </div>

    <a style="border:2px solid black;" href="sales_report.php" class="btn">View Sales Report</a>
    <a style="border:2px solid black;" href="add_sales.php" class="btn">Add a sale</a>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>

