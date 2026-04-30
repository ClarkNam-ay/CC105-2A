<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact System</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 60%; margin: auto; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        form { text-align: center; margin-bottom: 20px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Contact Management System</h2>

<!-- ADD CONTACT -->
<form action="add.php" method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <button type="submit">Add</button>
</form>

<!-- DISPLAY DATA -->
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM contacts");

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>
                <a href='edit.php?id={$row['id']}'>Edit</a> |
                <a href='delete.php?id={$row['id']}'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>

</body>
</html>