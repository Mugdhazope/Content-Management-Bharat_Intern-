<!DOCTYPE html>
<html>
<head>
    <title>Add Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        label{
            margin:5px;
        }
  .center{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

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
          <button type="button" class="btn btn-light active"><a class="nav-link" href="addleads.php">Add Leads</a></button>
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
    // Retrieve the logged-in username
    $loggedInUser = $_SESSION["username"];

    // Check if the lead form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve lead information from the form
        $name = $_POST["name"];
        $contactNum = $_POST["contact_num"];
        $refName = $_POST["ref_name"];
        $refContact = $_POST["ref_contact"];
        $lookFor = $_POST["look_for"];
        $location = $_POST["location"];
        $dob = $_POST["dob"];
        $caste = $_POST["caste"];
        $education = $_POST["education"];
        $income = $_POST["income"];
        $maritalStatus = $_POST["marital_status"];
        $requirements = $_POST["requirements"];
        $notes = $_POST["notes"];
        $deliverables = $_POST["deliverables"];
        $status = $_POST["status"];
        $gender = $_POST["gender"];

      

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a SQL statement to insert the lead into the table
        $stmt = $conn->prepare("INSERT INTO leads (name, contact_num, ref_name, ref_contact, look_for, location, dob, caste, education, income, marital_status, requirements, notes, deliverables, assigned , status , gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssss", $name, $contactNum, $refName, $refContact, $lookFor, $location, $dob, $caste, $education, $income, $maritalStatus, $requirements, $notes, $deliverables, $loggedInUser , $status , $gender);
        $stmt->execute();

        // Close the database connection
        $stmt->close();
        $conn->close();

        echo "Lead added successfully!";
    }
    ?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="center">
<br>

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required><br>

    <label for="contact_num">Contact Number:</label>
    <input type="text" name="contact_num" id="contact_num" required><br>

    <label for="ref_name">Reference Name:</label>
    <input type="text" name="ref_name" id="ref_name"><br>

    <label for="ref_contact">Reference Contact:</label>
    <input type="text" name="ref_contact" id="ref_contact"><br>

    <label for="look_for">Looking For:</label>
    <input type="text" name="look_for" id="look_for"><br>

    <label for="location">Location:</label>
    <input type="text" name="location" id="location"><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" id="dob"><br>

    <label for="caste">Caste:</label>
    <input type="text" name="caste" id="caste"><br>

    <label for="education">Education:</label>
    <input type="text" name="education" id="education"><br>

    <label for="income">Income:</label>
    <input type="text" name="income" id="income"><br>

    <label for="marital_status">Marital Status:</label>
    <select name="marital_status" id="marital_status">
        <option value="Single">Single</option>
        <option value="Married">Married</option>
        <option value="Divorced">Divorced</option>
        <option value="Parent">Has Kids (Single)</option>
    </select>

    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <label for="requirements">Requirements:</label>
    <input type="text" name="requirements" id="requirements"><br>

    <label for="notes">Notes:</label>
    <input type="text" name="notes" id="notes"><br>

    <label for="deliverables">Deliverables:</label>
    <input type="text" name="deliverables" id="deliverables"><br>

    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="uncalled">Uncalled</option>
        <option value="potential">Potential</option>
        <option value="onhold">On-Hold</option>
        <option value="dead">Dead</option>
        <option value="paid">Paid</option>
        <option value="blocked">Blocked</option>
    </select><br><br>

    <input type="submit" value="Add Lead">
</form>
       
</body>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
