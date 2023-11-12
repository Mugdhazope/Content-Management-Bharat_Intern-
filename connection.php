<?php
// Function to establish a database connection
function connectToDatabase() {
    $servername = "localhost"; // Replace with your host name
    $username = "root"; // Replace with your database username
    $password = ""; // Replace with your database password
    $dbname = "mycrm"; // Replace with your database name

    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Return the connection object
    return $conn;
}
?>
