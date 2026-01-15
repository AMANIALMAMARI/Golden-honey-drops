<?php
// ---------- Database Connection ----------
$host = "localhost";
$user = "u629988973_Honey";
$pass = "Oman2020oman*";
$db   = "u629988973_Honey";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Database Connection Failed: " . $conn->connect_error);
}

// ---------- Form Processing ----------
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email    = $_POST["email"];
    $phone    = $_POST["phone"];
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match!";
    } else {

        // Check if email already exists
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $message = "❌ Email is already registered!";
        } else {

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (fullname, email, phone, password) 
                    VALUES ('$fullname', '$email', '$phone', '$hashedPassword')";

            if ($conn->query($sql) === TRUE) {
                $message = "✅ Account Created Successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account</title>
  <style>
    body {
      font-family: "Tajawal", sans-serif;
      background: #fff7e6;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: #ffffff;
      width: 340px;
      padding: 25px;
      border-radius: 14px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .login-box h2 {
      margin: 0 0 15px;
      font-size: 22px;
      color: #3d2b1f;
    }

    .login-box input {
      width: 90%;
      padding: 12px;
      margin: 8px 0;
      border-radius: 8px;
      border: 1px solid #d8c3a5;
      font-size: 15px;
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      background: #cfa45d;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-box button:hover {
      background: #b88940;
    }

    .small {
      font-size: 13px;
      color: #6b4f35;
      margin-top: 10px;
    }

    .msg {
      background: #ffe9c7;
      padding: 8px;
      border-radius: 6px;
      margin-bottom: 10px;
      color: #5a3d1e;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Create Account</h2>

    <?php if ($message != "") { echo "<div class='msg'>$message</div>"; } ?>

    <form action="" method="POST">
      <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit">Create Account</button>
    </form>

    <p class="small">Already have an account? <a href="login.php">Log In</a></p>
  </div>

</body>
</html>   بدون تعليق