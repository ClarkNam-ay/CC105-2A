<?php
// Basic PHP variables
$title = "My First PHP Page";
$welcomeMessage = "Hello, welcome to my website!";
$date = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<body>

    <h1><?php echo $welcomeMessage; ?></h1>

    <p>Today's date is: <?php echo $date; ?></p>

    <hr>

    <h2>User Input Example</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter your name">
        <button type="submit">Submit</button>
    </form>

    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"]);
        if (!empty($username)) {
            echo "<p>Hello, $username!</p>";
        } else {
            echo "<p>Please enter your name.</p>";
        }
    }
    ?>

</body>
</html>