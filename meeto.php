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

// Get today's date
$today = date('Y-m-d');

// Retrieve today's meetings for the logged-in user
$userId = $_SESSION['username'];
$query = "SELECT * FROM meetings WHERE assigned = '$userId' AND meeting_date = '$today' AND status=''";
$result = $conn->query($query);

echo "<h1>Todays Meetings</h1>";

// Check if any meetings found
if ($result->num_rows > 0) {
    // Meetings found, display them
    while ($row = $result->fetch_assoc()) {
        $meetingId = $row['id'];
        $leadId = $row['lead_id'];

        echo "Meeting ID: " . $meetingId . "<br>";
        echo "User ID: " . $leadId . "<br>";
        echo "Date of Meeting: " . $row['meeting_date'] . "<br>";

        // Retrieve lead information from the leads table
        $leadQuery = "SELECT name, contact_num FROM leads WHERE id = '$leadId'";
        $leadResult = $conn->query($leadQuery);

        // Check if lead information found
        if ($leadResult->num_rows > 0) {
            // Lead information found, display it
            $leadRow = $leadResult->fetch_assoc();
            echo "Lead Name: " . $leadRow['name'] . "<br>";
            echo "Lead Contact: " . $leadRow['contact_num'] . "<br>";
        } else {
            echo "Lead information not found.<br>";
        }

        echo "<button onclick=\"updateStatus('$meetingId')\">Done</button><br>";
        echo "<button onclick=\"showLeadDetails('$leadId')\" target='_blank'>Show More</button><br>";
        echo "--------------------------<br>";
    }
} else {
    echo "No meetings scheduled for today.";
}

$query = "SELECT * FROM meetings WHERE assigned = '$userId' AND status = '' AND meeting_date != '$today'";
$result = $conn->query($query);

echo "<h1>Pending Meetings</h1>";

// Check if any meetings found
if ($result->num_rows > 0) {
    // Meetings found, display them
    while ($row = $result->fetch_assoc()) {
        $meetingId = $row['id'];
        $leadId = $row['lead_id'];

        echo "Meeting ID: " . $meetingId . "<br>";
        echo "User ID: " . $leadId . "<br>";
        echo "Date of Meeting: " . $row['meeting_date'] . "<br>";

        // Retrieve lead information from the leads table
        $leadQuery = "SELECT name, contact_num FROM leads WHERE id = '$leadId'";
        $leadResult = $conn->query($leadQuery);

        // Check if lead information found
        if ($leadResult->num_rows > 0) {
            // Lead information found, display it
            $leadRow = $leadResult->fetch_assoc();
            echo "Lead Name: " . $leadRow['name'] . "<br>";
            echo "Lead Contact: " . $leadRow['contact_num'] . "<br>";
        } else {
            echo "Lead information not found.<br>";
        }

        echo "<button onclick=\"updateStatus('$meetingId')\">Done</button><br>";
        echo "<button onclick=\"showLeadDetails('$leadId')\" target='_blank'>Show More</button><br>";
        echo "--------------------------<br>";
    }
} else {
    echo "No meetings scheduled for today.";
}

$conn->close();
?>


<script>
function updateStatus(meetingId) {
    // Perform an AJAX request to update the status
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Success, do something if needed
                console.log("Status updated successfully");
            } else {
                // Error, handle accordingly
                console.error("Failed to update status");
            }
        }
    };
    xhr.send("meeting_id=" + meetingId);
}

function showLeadDetails(leadId) {
    // Redirect to a page to display lead details
    window.location.href = "lead_details.php?id=" + leadId;
}
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>

