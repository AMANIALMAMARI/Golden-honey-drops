<?php
$host = "localhost";
$user = "u629988973_Honey";
$pass = "Oman2020oman*";
$db   = "u629988973_Honey";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Error");

$name    = $_POST['name'];
$email   = $_POST['email'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: index.php?sent=1");
exit();
?>
