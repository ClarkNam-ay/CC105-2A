<!DOCTYPE html>
<html>
<head>
    <title>Simple Form</title>
</head>
<body>

<form method="POST">
    Name: <input type="text" name="username">
    <input type="submit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    echo "Hello, " . htmlspecialchars($name);
}
?>

</body>
</html>