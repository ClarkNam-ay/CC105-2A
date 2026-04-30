<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    echo "Hello, " . htmlspecialchars($username);
}
?>

<form method="post">
    <input type="text" name="username">
    <input type="submit" value="Submit">
</form>