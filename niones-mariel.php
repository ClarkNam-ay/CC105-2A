<?php
$result = "";

if (isset($_POST['add'])) {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];

    $result = $num1 + $num2;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Two Numbers</title>
</head>
<body>

<h2>Add Two Numbers</h2>

<form method="POST">
    <input type="number" name="num1" placeholder="Enter first number" required><br><br>
    <input type="number" name="num2" placeholder="Enter second number" required><br><br>
    <button type="submit" name="add">Add</button>
</form>

<?php
if ($result !== "") {
    echo "<h3>Result: $result</h3>";
}
?>

</body>
</html>