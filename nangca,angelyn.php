<?php
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "1234"){
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <button name="login">Login</button>
</form>