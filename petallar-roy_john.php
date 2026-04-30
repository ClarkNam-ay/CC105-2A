<?php
// 🔌 Connect to database
$conn = new mysqli("localhost", "root", "", "your_database");

// ❌ Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 📥 Fetch data (example table: books)
$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #333;
            color: #fff;
        }
    </style>
</head>
<body>

<h2>📚 Book List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['title']; ?></td>
                <td><?= $row['author']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No data found</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>

<?php
$conn->close();
?>