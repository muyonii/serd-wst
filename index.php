<?php
// Login page: form + $_POST + isset() + session_start() + $_SESSION
session_start();
include "mydb.php";

// already logged in -> go to dashboard
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST["login"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // SELECT query
    $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row["password"])) {
        $_SESSION["username"] = $username;   // save user to session
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SERD - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-box">
        <h1>SERD.</h1>
        <p class="subtitle">Smart Emergency Response Dispatch</p>

        <?php if ($error != ""): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <input type="text" name="username" id="username" placeholder="Username" required>

            <input type="password" name="password" id="password" placeholder="Password" required style="margin-top:14px;">

            <button type="submit" name="login">LOG IN</button>
        </form>
    </div>
</body>
</html>
