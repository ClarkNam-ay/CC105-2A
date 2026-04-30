<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TaskWave - Welcome</title>
<link rel="stylesheet" href="assets\style.css" />

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: Arial, sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;

    animation: gradientMove 10s ease infinite;
}

@keyframes gradientMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.container{
    background:white;
    padding:45px;
    border-radius:15px;
    text-align:center;
    width:360px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    animation: fadeSlide 1.2s ease;
}

@keyframes fadeSlide{
    from{ opacity:0; transform:translateY(40px);}
    to{ opacity:1; transform:translateY(0);}
}

h1{
    margin-bottom:10px;
    color:#333;
    font-size:32px;
}

p{
    color:#777;
    margin-bottom:20px;
}

.btn{
    display:block;
    text-decoration:none;
    padding:13px;
    margin-top:15px;
    border-radius:8px;
    color:white;
    font-weight:bold;
    letter-spacing:0.5px;
    transition:all 0.3s ease;
}

.login{ background:#4CAF50; }
.register{ background:#2196F3; }

.btn:hover{
    transform:translateY(-4px) scale(1.03);
    box-shadow:0 8px 18px rgba(0,0,0,0.2);
}

.btn:active{
    transform:scale(0.96);
}

.container:hover{
    transform:translateY(-5px);
    transition:0.3s;
}
</style> 
</head>

<body>

<div class="container">
    <h1>WELCOME</h1>
     <p>    </p>
    <h2>TASKWAVE SYSTEM</h2>
    <p>Smart Task Management System</p>

    <a href="login.php" class="btn login">Login</a>
    <a href="register.php" class="btn register">Register</a>
</div>

<!-- Include external JS -->
<script src="assets/style.js"></script>

</body>
</html>