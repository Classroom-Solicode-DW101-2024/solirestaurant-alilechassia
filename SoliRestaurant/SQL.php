<?php  
$servername = "localhost";
$username = "root";
$password = "";
$database = "solirestaurant";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
$categories = $conn->query("SELECT DISTINCT categoriePlat FROM plat")->fetchAll(PDO::FETCH_COLUMN);
$typeCuisines = $conn->query("SELECT DISTINCT TypeCuisine FROM plat")->fetchAll(PDO::FETCH_COLUMN);

$categorieFilter = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$typeCuisineFilter = isset($_GET['typeCuisine']) ? $_GET['typeCuisine'] : '';

$sql = "SELECT categoriePlat, image, nomPlat, prix, TypeCuisine FROM plat WHERE 1=1";
$params = [];

if (!empty($categorieFilter)) {
    $sql .= " AND categoriePlat = :categorie";
    $params[':categorie'] = $categorieFilter;
}

if (!empty($typeCuisineFilter)) {
    $sql .= " AND TypeCuisine = :typeCuisine";
    $params[':typeCuisine'] = $typeCuisineFilter;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="SQL.css">
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
    <div class="content">
        <!-- Formulaire de filtre -->
        <form class="filter-form" method="GET">

        <select name="categorie">
                <option value="">Filtrer par catégorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $category === $categorieFilter ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="typeCuisine">
                <option value="">Filtrer par type de cuisine</option>
                <?php foreach ($typeCuisines as $type): ?>
                    <option value="<?= htmlspecialchars($type) ?>" <?= $type === $typeCuisineFilter ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type) ?>
                    </option>
                    
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>
        <div class="card-container">
            <?php foreach ($result as $row): ?>
                <div class='card'>
                    <img src='<?= htmlspecialchars($row['image'] ?: 'image/default.jpg') ?>' alt='<?= htmlspecialchars($row['categoriePlat']) ?>'>
                    <h3><?= htmlspecialchars($row['nomPlat']) ?></h3>
                    <h4>Catégorie: <?= htmlspecialchars($row['categoriePlat']) ?></h4>
                    <h4>Prix: <?= htmlspecialchars($row['prix']) ?> DH</h4>
                    <h4>Type Cuisine: <?= htmlspecialchars($row['TypeCuisine']) ?></h4>
                    <form action='cart.php' method='POST'>
                        <input type='hidden' name='item' value='<?= htmlspecialchars($row['nomPlat']) ?>'>
                        <button type='submit' class='add-to-cart'>🛒 Ajouter au panier</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Restaurant Menu. All rights reserved by ALILECH ASSIA.</p>
    </footer>
</body>
</html>