<?php
// Start session (if needed)
session_start();

// Database connection (edit credentials)
$conn = new mysqli("localhost", "root", "", "your_database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example variable
$message = "";

// Example form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    if (!empty($name)) {
        $message = "Hello, " . htmlspecialchars($name) . "!";
    } else {
        $message = "Please enter your name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Sample</title>
</head>
<body>

<h2>Simple PHP Form</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Enter your name">
    <button type="submit">Submit</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>