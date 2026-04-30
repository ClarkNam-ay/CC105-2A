<?php
// connect to database
$conn = new mysqli("localhost", "root", "", "library");

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// INSERT (Add Book)
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $author = $_POST['author'];

    $sql = "INSERT INTO books (title, author) VALUES ('$title', '$author')";
    $conn->query($sql);
}

// SELECT (Display Books)
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Library</title>
</head>
<body>

<h2>Add Book</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Book Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <button name="add">Add</button>
</form>

<h2>Book List</h2>
<table border="1">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['title'] ?></td>
    <td><?= $row['author'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>