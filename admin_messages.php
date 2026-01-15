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
    die("Database Connection Failed");
}


$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Messages</title>

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
    padding:15px;
    text-align:center;
    position:relative;
}

.back {
    position:absolute;
    left:20px;
    top:35px;
    background:#6b4f35;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.logout {
    position:absolute;
    right:20px;
    top:35px;
    background:#4e3205ab;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

h2 {
    text-align:center;
    margin:20px;
    color:#34495e;
}

table {
    width:90%;
    margin:10px auto 40px;
    border-collapse:collapse;
    background:white;
}

th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}

th {
    background:#cfa45d;
    color:white;
}

tr:hover {
    background:#f7f1e1;
}
</style>
</head>

<body>

<header>
<h1>Customer Messages</h1>
<a href="admin.php" class="back">⬅ Back</a>
<a href="login.php" class="logout">Logout</a>
</header>

<h2>All Messages</h2>

<table>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Date</th>
</tr>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="4" style="text-align:center;">No messages yet</td>
</tr>
<?php endif; ?>

</table>

</body>
</html>

<?php $conn->close(); ?>
