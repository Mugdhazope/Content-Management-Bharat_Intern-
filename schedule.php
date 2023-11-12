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

// Function to sanitize user input
function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $leadId = sanitize($_POST["leadId"]);
    $meetingDate = sanitize($_POST["meetingDate"]);
    $loggedInUser = $_SESSION["username"];

    // Prepare SQL statement to insert meeting details
    $insertQuery = "INSERT INTO meetings (lead_id, meeting_date, status, outcome, assigned) VALUES ('$leadId', '$meetingDate', '', NULL, '$loggedInUser')";

    // Execute the insert query
    if ($conn->query($insertQuery) === TRUE) {
        // Meeting inserted successfully
        echo "Meeting scheduled successfully.";
    } else {
        echo "Error scheduling meeting: " . $conn->error;
    }
}

// Retrieve the list of leads for search functionality
$searchTerm = "";

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    // Sanitize the search term
    $searchTerm = sanitize($_POST["searchTerm"]);
}

// Prepare SQL statement for lead search
$leadQuery = "SELECT * FROM leads WHERE (contact_num LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%' OR ref_name LIKE '%$searchTerm%' OR ref_contact LIKE '%$searchTerm%')";

// Execute the lead query
$leadResult = $conn->query($leadQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Meeting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Schedule Meeting</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="searchTerm" class="form-label">Search Lead:</label>
                <input type="text" class="form-control" id="searchTerm" name="searchTerm" value="<?php echo $searchTerm; ?>" placeholder="Enter contact number, name, ref number, or ref name">
            </div>
            <button type="submit" class="btn btn-primary" name="search">Search</button>
        </form>

        <?php if ($leadResult && $leadResult->num_rows > 0) : ?>
            <h3>Leads</h3>
            <ul class="list-group">
                <?php while ($row = $leadResult->fetch_assoc()) : ?>
                    <li class="list-group-item">
                        <span><?php echo $row["name"]; ?></span>
                        <span><?php echo $row["contact_num"]; ?></span>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#meetingModal<?php echo $row["id"]; ?>">Schedule Meeting</button>
                    </li>

                    <!-- Meeting Modal -->
                    <div class="modal fade" id="meetingModal<?php echo $row["id"]; ?>" tabindex="-1" aria-labelledby="meetingModalLabel<?php echo $row["id"]; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="meetingModalLabel<?php echo $row["id"]; ?>">Schedule Meeting</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="leadId" value="<?php echo $row["id"]; ?>">
                                        <div class="mb-3">
                                            <label for="meetingDate" class="form-label">Meeting Date:</label>
                                            <input type="date" class="form-control" id="meetingDate" name="meetingDate" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Schedule</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p>No leads found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>

