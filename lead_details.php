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
    $assigned = sanitize($_POST["assigned"]);
    $status = sanitize($_POST["status"]);
    $payInfo = sanitize($_POST["payInfo"]);
    $gender = sanitize($_POST["gender"]);

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
        gender = ?
        WHERE id = ? AND assigned = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssssssssssssssssss",
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
        $leadId,
        $loggedInUser
    );
    if ($stmt->execute()) {
        echo "Lead details updated successfully!";
        // Redirect back to the lead details page
        header("Location: lead_details.php?id=$leadId");
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
    header("Location: error.php");
    exit();
}

// Fetch lead details
$lead = $result->fetch_assoc();

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lead Details</title>
</head>
<body>
    <h2>Lead Details</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $leadId); ?>">
        <input type="hidden" name="leadId" value="<?php echo $leadId; ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $lead["name"]; ?>"><br><br>

        <label for="contactNum">Contact Number:</label>
        <input type="text" name="contactNum" id="contactNum" value="<?php echo $lead["contact_num"]; ?>"><br><br>

        <label for="refName">Reference Name:</label>
        <input type="text" name="refName" id="refName" value="<?php echo $lead["ref_name"]; ?>"><br><br>

        <label for="refContact">Reference Contact Number:</label>
        <input type="text" name="refContact" id="refContact" value="<?php echo $lead["ref_contact"]; ?>"><br><br>

        <label for="lookFor">Looking For:</label>
        <input type="text" name="lookFor" id="lookFor" value="<?php echo $lead["look_for"]; ?>"><br><br>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo $lead["location"]; ?>"><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="text" name="dob" id="dob" value="<?php echo $lead["dob"]; ?>"><br><br>

        <label for="caste">Caste:</label>
        <input type="text" name="caste" id="caste" value="<?php echo $lead["caste"]; ?>"><br><br>

        <label for="education">Education:</label>
        <input type="text" name="education" id="education" value="<?php echo $lead["education"]; ?>"><br><br>

        <label for="income">Income:</label>
        <input type="text" name="income" id="income" value="<?php echo $lead["income"]; ?>"><br><br>

        <label for="maritalStatus">Marital Status:</label>
    <select name="maritalStatus" id="maritalStatus">
        <option value="Single" <?php echo ($lead["marital_status"] === "Single") ? "selected" : ""; ?>>Single</option>
        <option value="Married" <?php echo ($lead["marital_status"] === "Married") ? "selected" : ""; ?>>Married</option>
        <option value="Divorced" <?php echo ($lead["marital_status"] === "Divorced") ? "selected" : ""; ?>>Divorced</option>
        <option value="Parent" <?php echo ($lead["marital_status"] === "Parent") ? "selected" : ""; ?>>Has Kids (Single)</option>
        
    </select><br><br>

        <label for="requirements">Requirements:</label>
        <input type="text" name="requirements" id="requirements" value="<?php echo $lead["requirements"]; ?>"><br><br>

        <label for="notes">Notes:</label>
        <input type="text" name="notes" id="notes" value="<?php echo $lead["notes"]; ?>"><br><br>

        <label for="deliverables">Deliverables:</label>
        <input type="text" name="deliverables" id="deliverables" value="<?php echo $lead["deliverables"]; ?>"><br><br>

        <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="potential" <?php echo ($lead["status"] === "potential") ? "selected" : ""; ?>>Potential</option>
        <option value="uncalled" <?php echo ($lead["status"] === "uncalled") ? "selected" : ""; ?>>Uncalled</option>
        <option value="blocked" <?php echo ($lead["status"] === "blocked") ? "selected" : ""; ?>>Blocked</option>
        <option value="onhold" <?php echo ($lead["status"] === "onhold") ? "selected" : ""; ?>>On-Hold</option>
        <option value="paid" <?php echo ($lead["status"] === "paid") ? "selected" : ""; ?>>Paid</option>
    </select><br><br>

        <label for="payInfo">Payment Information:</label>
        <input type="text" name="payInfo" id="payInfo" value="<?php echo $lead["pay_info"]; ?>"><br><br>

        <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender" value="<?php echo $lead["gender"]; ?>"><br><br>

        <input type="submit" name="updateLead" value="Update">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
