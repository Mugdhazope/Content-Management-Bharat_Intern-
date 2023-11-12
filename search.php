<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>

        .tables{
            margin-left:400px;
        }

.center-div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
          <button type="button" class="btn btn-light"><a class="nav-link" href="uncalled.php">uncalled Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="potential.php">potential Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="paid.php">paid Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="onhold.php">Onhold Leads</a></button>
        </li>
        <li class="nav-item">
          <button type="button" class="btn btn-light"><a class="nav-link" href="blocked.php">blocked Leads</a></button>
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

<?php
// Function to sanitize user input
function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

$loggedInUser = $_SESSION["username"];

// Search and filter variables
$searchTerm = "";
$genderFilter = "";
$statusFilter = "";
$maritalStatusFilter = "";
$dobFilter = "";

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    // Sanitize the search term
    $searchTerm = sanitize($_POST["searchTerm"]);
    $genderFilter = sanitize($_POST["genderFilter"]);
    $statusFilter = sanitize($_POST["statusFilter"]);
    $maritalStatusFilter = sanitize($_POST["maritalStatusFilter"]);
    $dobFilter = sanitize($_POST["dobFilter"]);
}

// Prepare SQL statement for search and filter
$sql = "SELECT * FROM leads WHERE (contact_num LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%' OR ref_name LIKE '%$searchTerm%' OR ref_contact LIKE '%$searchTerm%') AND assigned = '$loggedInUser'";

if (!empty($genderFilter)) {
    $sql .= " AND gender = '$genderFilter'";
}

if (!empty($statusFilter)) {
    $sql .= " AND status = '$statusFilter'";
}

if (!empty($maritalStatusFilter)) {
    $sql .= " AND marital_status = '$maritalStatusFilter'";
}

if (!empty($dobFilter)) {
    $sql .= " AND dob = '$dobFilter'";
}

// Execute the SQL statement
$result = $conn->query($sql);

?>

<div class="center-div">
        <!-- Search form -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="searchTerm" placeholder="Search by contact number, name, ref number, or ref name" value="<?php echo $searchTerm; ?>">
            <!-- Add more input fields for filter options -->
            <button type="submit" name="search">Search</button>

            <div style="margin-top: 10px;">
            <!-- Filter button -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                Filter
            </button>
            <!-- Filter options -->
            <div class="collapse" id="filterCollapse">
                <div style="margin-top: 10px;">
                    <label for="genderFilter">Gender:</label>
                    <select name="genderFilter" id="genderFilter">
                        <option value="">All</option>
                        <option value="Male" <?php if ($genderFilter == "Male") echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if ($genderFilter == "Female") echo "selected"; ?>>Female</option>
                    </select>
                </div>
                <div style="margin-top: 10px;">
                    <label for="statusFilter">Status:</label>
                    <select name="statusFilter" id="statusFilter">
                        <option value="">All</option>
                        <option value="uncalled" <?php if ($statusFilter == "uncalled") echo "selected"; ?>>Uncalled</option>
                        <option value="potential" <?php if ($statusFilter == "potential") echo "selected"; ?>>Potential</option>
                        <option value="onhold" <?php if ($statusFilter == "onhold") echo "selected"; ?>>On-Hold</option>
                        <option value="dead" <?php if ($statusFilter == "dead") echo "selected"; ?>>Dead</option>
                        <option value="paid" <?php if ($statusFilter == "paid") echo "selected"; ?>>Paid</option>
                        <option value="blocked" <?php if ($statusFilter == "blocked") echo "selected"; ?>>Blocked</option>
                    </select>
                </div>
                <div style="margin-top: 10px;">
                    <label for="maritalStatusFilter">Marital Status:</label>
                    <select name="maritalStatusFilter" id="maritalStatusFilter">
                        <option value="">All</option>
                        <option value="single" <?php if ($maritalStatusFilter == "single") echo "selected"; ?>>Single</option>
                        <option value="married" <?php if ($maritalStatusFilter == "married") echo "selected"; ?>>Married</option>
                    </select>
                </div>
                <div style="margin-top: 10px;">
                    <label for="dobFilter">Date of Birth:</label>
                    <input type="date" name="dobFilter" id="dobFilter" value="<?php echo $dobFilter; ?>">
                </div>
                <button type="submit" name="search" style="margin-top: 10px;">Apply Filters</button>
            </div>
        </div>

        </form>

        <!-- Table to display search results -->
        <div class="tables">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display search results
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $leadId = $row["id"];
                        $leadName = $row["name"];
                        $leadContactNum = $row["contact_num"];
                        $status = $row["status"];
                        // Add more columns as needed

                        // Display a row for each search result
                        echo "<tr>";
                        echo "<td>$leadName</td>";
                        echo "<td>$leadContactNum</td>";
                        echo "<td>$status</td>";
                        echo "<td><button onclick=\"showLeadDetails($leadId)\">Show More</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No results found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- JavaScript for the popup card and edit functionality -->
    <script>
        function showLeadDetails(leadId) {
            // Retrieve lead details using leadId and display in a popup card
            // You can use JavaScript, CSS, and HTML to create the popup card

            // Example code for opening the popup card
            window.open("lead_details.php?id=" + leadId, "_blank");
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
