<?php 
require "config.php";
if(isset($_POST["submit"])){
    $tel = $_POST["tel"];
    $rusult=tel_existe($tel);
    if(empty($rusult)){
        header("Location:register.php");
    }else{
        $_SESSION["client"]=$rusult;
        header("Location:SQL.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <img src="img/restaurant.jpg" alt="restaurant" class="background-image">
    <form method="POST" class="login-form">
        <label for="tel">entre tel:</label>
        <input type="tel" id="tel" name="tel" required>
        <button name="submit">log in</button>
    </form>
</body>
</html>