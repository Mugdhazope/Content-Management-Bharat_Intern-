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

// Check if the meeting_id parameter is set
if (isset($_POST['meeting_id'])) {
    $meetingId = $_POST['meeting_id'];

    // Update the status of the meeting to 'Done'
    $query = "UPDATE meetings SET status = 'Done' WHERE id = '$meetingId'";
    $result = $conn->query($query);

    if ($result) {
        // Status updated successfully
        echo "<script>alert('Status updated successfully.');</script>";
    } else {
        // Failed to update status
        echo "<script>alert('Failed to update status.');</script>";
    }
} else {
    // meeting_id parameter not provided
    echo "Invalid request.";
}
header("Refresh: 0.01");

$conn->close();
?>
