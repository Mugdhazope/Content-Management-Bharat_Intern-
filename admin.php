<?php
// Database connection details
require_once "connection.php";

// Retrieve the database connection
$conn = connectToDatabase();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the new user details from the form
$username = $_POST["username"];
$password = $_POST["password"];
$code = $_POST["code"];

if($code=="tanujkumbhar"){

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare a SQL statement to insert a new user
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);

// Execute the statement
if ($stmt->execute()) {
    echo "New user created successfully.";
} else {
    echo "Error creating user: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
}
else {
    echo"Wrong Code : Contact 7700007543";
}
?>
<form method="POST" action="admin.php">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>
    <label for="password">Add Special Code</label>
    <input type="password" name="code" id="code" required><br>
    <input type="submit" value="Create User">
</form>
