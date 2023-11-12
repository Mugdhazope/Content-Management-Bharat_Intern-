<?php
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

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Assuming you have a database connection established
$today = date('Y-m-d');
$id = $_POST['id']; // Get the ID from the AJAX request

// Perform the update query
$query = "UPDATE leads SET done = '$today', meet = '$today' WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($result) {
  echo "Date updated successfully";
} else {
  echo "Error updating date: " . mysqli_error($conn);
}
?>
