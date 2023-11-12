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
            echo "Sales added successfully.";
        } else {
            echo "Error adding sales: " . $conn->error;
        }
    } else {
        echo "Lead not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sales</title>
</head>
<body>
    <h2>Add Sales</h2>
    <form method="POST" action="">


        <label>Contact Number:</label>
        <input type="text" name="contact_num" required><br>

        <label>Payment Amount:</label>
        <input type="text" name="pay_amount" required><br>

        <label>Payment Method:</label>
        <input type="text" name="pay_method" required><br>

        <button type="submit">Add Sales</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>

