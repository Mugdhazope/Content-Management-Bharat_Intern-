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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
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
      
    </style>
</head>
<body>

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

$a = 1;

if ($a >= 1) {
    $sql .= " ORDER BY id DESC";
}

// Execute the SQL statement
$result = $conn->query($sql);


?>


<div class="app-container">
    <div class="app-header">
      <div class="app-header-left">
        <span class="app-icon"></span>
        <p class="app-name">Dashboard</p>
        <div class="">
        <form method="POST" class="search-wrapper" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input class="search-input" name="searchTerm" type="text" placeholder="Search" value="<?php echo $searchTerm; ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="feather feather-search" viewBox="0 0 24 24">
            <defs></defs>
            <circle cx="11" cy="11" r="8"></circle>
            <path d="M21 21l-4.35-4.35"></path>
          </svg>
        </div>
      </div>

      <div class="app-header-right">
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
        <a href="sale.php" class="app-sidebar-link">
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
        <a href="leads.php" class="app-sidebar-link active">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
          </svg>
        </a>
      </div>
      <div class="projects-section">
        <div class="projects-section-header">
          <p>Projects</p>
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

        <div class="project-boxes jsGridView">

    

        <!-- Table to display search results -->
        <div class="tables center">
        <table  class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
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
                        echo "<td scope='row'>$leadName</td>";
                        echo "<td>$leadContactNum</td>";
                        echo "<td>$status</td>";
                        echo "<td><button class='btn btn-secondary' onclick=\"showLeadDetails($leadId)\">Show More</button></td>";
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


  </div>
  </div>
  <script>
  $(document).ready(function() {
    $('#exampleModal').modal('hide');
  });
</script>
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
 <script src="script.js"></script>

</body>
</html>