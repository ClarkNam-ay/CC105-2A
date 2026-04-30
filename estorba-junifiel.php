<?php
// Enable error reporting (useful for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sample function
function greet($name) {
    return "Hello, " . htmlspecialchars($name) . "!";
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? "Guest";
    $message = greet($name);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Starter</title>
</head>
<body>

<h1>PHP Test Page</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Enter your name">
    <button type="submit">Submit</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>