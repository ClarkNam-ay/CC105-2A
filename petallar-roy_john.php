
<?php
session_start();
require "include/db.php";

$message = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "Please fill up all fields!";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Incorrect Password!";
            }

        } else {
            $message = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">
    <input type="text" name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit" name="login">Login</button>
</form>

<br>
<a href="register.php">Create Account</a>

</body>
</html>
=======
Done
>>>>>>> a5036e09a28cbc69b7a0e8b15bfe115265b34fdc
