<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$host = "localhost";
$user = "u629988973_Honey";
$pass = "Oman2020oman*";
$db   = "u629988973_Honey";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}


if (isset($_POST['update_stock'])) {
    $product_id = (int)$_POST['product_id'];
    $stock      = (int)$_POST['stock'];

    $stmt = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
    $stmt->bind_param("ii", $stock, $product_id);
    $stmt->execute();
    $stmt->close();
}


$result = $conn->query("SELECT * FROM products ORDER BY category, name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Inventory Dashboard</title>

<style>
body {
    font-family: Arial, sans-serif;
    background:#fff8e8;
    margin:0;
    padding:0;
}

header {
    background:#cfa45d;
    color:white;
    padding:20px;
    text-align:center;
    position:relative;
}

h2 {
    margin:20px;
    color:#34495e;
}

table {
    width:90%;
    margin:10px auto;
    border-collapse: collapse;
    background:white;
}

th, td {
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

th {
    background:#cfa45d;
    color:white;
}

tr:hover {
    background:#f1f1f1;
}

img {
    width:50px;
}

input[type=number] {
    width:70px;
    padding:5px;
}

button {
    padding:6px 12px;
    background:#cfa45d;
    border:none;
    color:white;
    border-radius:4px;
    cursor:pointer;
}

button:hover {
    background:#b8934f;
}

/* ====== أزرار الهيدر ====== */
.header-actions {
    position:absolute;
    top:45px;
    right:20px;
    display:flex;
    gap:10px;
}

.header-actions a {
    background:#6b4f35;
    color:white;
    padding:8px 14px;
    text-decoration:none;
    border-radius:5px;
    font-size:14px;
}

.header-actions a.logout {
    background:#840303;
}


</style>
</head>

<body>

<header>
    <h1>Inventory Dashboard</h1>

>
    <div class="header-actions">
        <a href="admin_messages.php">Messages</a>
        <a href="login.php" class="logout">Logout</a>
    </div>
</header>
<?php
$categoriesOrder = [
    "Raw & Flavored Honey",
    "Honey-Based Products",
    "Bee Supplements"
];


$productsByCategory = [];
while ($row = $result->fetch_assoc()) {
    $productsByCategory[$row['category']][] = $row;
}

foreach ($categoriesOrder as $category) {

    if (!empty($productsByCategory[$category])) {

        echo "<h2>$category</h2>";
        echo "<table>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Price (OMR)</th>
            <th>Stock</th>
            <th>Update</th>
        </tr>";

        foreach ($productsByCategory[$category] as $product) {
?>
<tr>
    <td><img src="<?php echo $product['image']; ?>"></td>
    <td><?php echo $product['name']; ?></td>
    <td><?php echo $product['price']; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" min="0">
    </td>
    <td>
            <button type="submit" name="update_stock">Save</button>
        </form>
    </td>
</tr>
<?php
        }
        echo "</table>";
    }
}
?>

</body>
</html>
