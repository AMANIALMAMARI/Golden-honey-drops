<?php
session_start();

$host = "localhost";
$user = "u629988973_Honey";
$pass = "Oman2020oman*";
$db   = "u629988973_Honey";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$message = "";
$msg_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password, user_type, fullname FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashedPassword, $user_type, $fullname);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['user_type'] = $user_type;

            if ($user_type == "admin") {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $message = "❌ Invalid password!";
            $msg_class = "error";
        }
    } else {
        $message = "❌ Email not found!";
        $msg_class = "error";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
body{font-family:"Tajawal",sans-serif;background:#fff7e6;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}
.login-box{background:#fff;width:340px;padding:25px;border-radius:14px;box-shadow:0 4px 15px rgba(0,0,0,.1);text-align:center}
.login-box h2{margin:0 0 15px;font-size:22px;color:#3d2b1f}
.login-box input{width:90%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid #d8c3a5;font-size:15px}
.login-box button{width:100%;padding:12px;background:#cfa45d;border:none;color:#fff;font-size:16px;font-weight:bold;border-radius:8px;cursor:pointer;margin-top:10px}
.login-box button:hover{background:#b88940}
.msg{padding:8px;border-radius:6px;margin-bottom:10px;font-size:14px}
.msg.error{background:#ffe9c7;color:#5a3d1e}
.msg.success{background:#d4edda;color:#155724}
</style>
</head>
<body>

<div class="login-box">
<h2>Login</h2>
<?php if($message!=""){echo "<div class='msg $msg_class'>$message</div>";} ?>
<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
<p class="small">Don't have an account? <a href="createaccount.php">Register</a></p>
</div>

</body>
</html>
