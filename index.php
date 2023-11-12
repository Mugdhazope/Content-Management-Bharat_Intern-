<!DOCTYPE html>
<html>
<head>
    <title>Index</title>
</head>
<body>
    <?php
    // Start the session
    session_start();

    // Check if the user is already logged in
    if (isset($_SESSION['username'])) {
        // User is logged in, redirect to dashboard.php
        header("Location: dashboard.php");
        exit();
    } else {
        // User is not logged in, redirect to login.php
        header("Location: login.php");
        exit();
    }
    ?>
</body>
</html>
