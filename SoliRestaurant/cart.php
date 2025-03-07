<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "solirestaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "SELECT DISTINCT categoriePlat, image, nomPlat, prix, TypeCuisine FROM plat";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item'])) {
    $item = $_POST['item'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$item])) {
        $_SESSION['cart'][$item]++;
    } else {
        $_SESSION['cart'][$item] = 1;
    }
}

if (isset($_GET['increase'])) {
    $itemToIncrease = $_GET['increase'];
    if (isset($_SESSION['cart'][$itemToIncrease])) {
        $_SESSION['cart'][$itemToIncrease]++;
    }
   
}

if (isset($_GET['decrease'])) {
    $itemToDecrease = $_GET['decrease'];
    if (isset($_SESSION['cart'][$itemToDecrease])) {

        if ($_SESSION['cart'][$itemToDecrease] > 1) {
            $_SESSION['cart'][$itemToDecrease]--;

        } else {
            unset($_SESSION['cart'][$itemToDecrease]);
        }
    }
}

if (isset($_GET['remove'])) {
    $itemToRemove = $_GET['remove'];
    unset($_SESSION['cart'][$itemToRemove]);
    header("Location: cart.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
<img src="img/restaurant.jpg" alt="restaurant" class="background-image">
<header>
        <div class="header-container">
            <h3>Restaurant Menu</h3>
            <nav class="header-nav">
                <a href="SQL.php">Home</a>
                <a href="cart.php">Cart</a>
                <a href="admin_orders.php">Admin</a>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>🛒 Your Cart</h2>
        <ul>
            <?php
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $cartItem => $quantity) {
                    echo "<li>" . htmlspecialchars($cartItem) . " (x$quantity) 
                    <a href='cart.php?decrease=" . ($cartItem) . "' class='btn-small btn-decrease'>-</a>
                    <a href='cart.php?increase=" . ($cartItem) . "' class='btn-small btn-increase'>+</a>
                    <a href='cart.php?remove=" . ($cartItem) . "' class='btn-small btn-remove'>❌</a>
                    </li>";
                }
            } else {
                echo "<p class='empty-cart'>Your cart is empty.</p>";
            }
            ?>
        </ul>
        <a href="SQL.php" class="btn">🔙 Continue Shopping</a>
        
    </div>
</body>
</html>