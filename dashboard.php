<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>

.card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

.btn-light{
    margin:1px;
    background-color:#f2f2f2;
}
.btn{
    margin:1px;
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
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button type="button" class="btn btn-light"><a class="navbar-brand active" href="dashboard.php">Dashboard</a></button>
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
// Include the database connection file
require_once 'connection.php';
$loggedInUser = $_SESSION["username"];

// Function to execute SQL queries and fetch a single value
function fetchSingleValue($query)
{
    global $conn;
    $result = $conn->query($query);
    return ($result) ? $result->fetch_row()[0] : 0;
}

$totalLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE assigned = '$loggedInUser'");

// Total number of leads with status = 'uncalled'
$totalUncalledLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'uncalled' AND assigned = '$loggedInUser'");

// Total number of leads with status = 'dead'
$totalDeadLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'dead' AND assigned = '$loggedInUser'");

// Total number of leads with status = 'potential'
$totalPotentialLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'potential' AND assigned = '$loggedInUser'");

// Total number of leads with status = 'paid'
$totalPaidLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'paid' AND assigned = '$loggedInUser'");

// Total number of leads with status = 'onhold'
$totalOnHoldLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'onhold' AND assigned = '$loggedInUser'");

// Total number of leads with status = 'block'
$totalBlockLeads = fetchSingleValue("SELECT COUNT(*) FROM leads WHERE status = 'block' AND assigned = '$loggedInUser'");

// Total amount of sales in the current month from the payments table
$totalSales = fetchSingleValue("SELECT SUM(pay_amount) FROM payments WHERE assigned = '$loggedInUser' AND MONTH(date_of_pay) = MONTH(CURRENT_DATE())");

// Total number of meetings with today's date
$totalMeetings = fetchSingleValue("SELECT COUNT(*) FROM meetings WHERE assigned = '$loggedInUser' AND DATE(meeting_date) = CURDATE()");
?>
<div class="card-container">
<div class="card">
    <h3>Total Leads</h3>
    <p><?php echo $totalLeads; ?></p>
</div>

<div class="card">
    <h3>Uncalled Leads</h3>
    <p><?php echo $totalUncalledLeads; ?></p>
</div>

<div class="card">
    <h3>Dead Leads</h3>
    <p><?php echo $totalDeadLeads; ?></p>
</div>

<div class="card">
    <h3>Potential Leads</h3>
    <p><?php echo $totalPotentialLeads; ?></p>
</div>

<div class="card">
    <h3>Paid Leads</h3>
    <p><?php echo $totalPaidLeads; ?></p>
</div>

<div class="card">
    <h3>On Hold Leads</h3>
    <p><?php echo $totalOnHoldLeads; ?></p>
</div>

<div class="card">
    <h3>Block Leads</h3>
    <p><?php echo $totalBlockLeads; ?></p>
</div>

<div class="card">
    <h3>Total Sales Month</h3>
    <p>₹<?php echo $totalSales; ?></p>
</div>

<div class="card">
    <h3>Meetings Today</h3>
    <p><?php echo $totalMeetings; ?></p>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
