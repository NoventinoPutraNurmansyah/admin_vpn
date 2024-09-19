<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conn = pg_connect("host=localhost dbname=admin_vpn user=postgres password=dbApiMikrotik2024!");

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM list_admin WHERE user_admin = $1 AND password_admin = $2";
    $result = pg_query_params($conn, $query, array($username, $password));

    if (!$result) {
        die("Query gagal: " . pg_last_error());
    }

    if (pg_num_rows($result) > 0) {
        $_SESSION['loggedin'] = true;
        header("Location: tabel.php");
        exit();
    } else {
        echo "<p style='color:red;'>Username atau password salah!</p>";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            border: 2px solid red;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid red;
            border-radius: 5px;
        }
        .login-btn {
            background-color: red;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
           <form method="POST">
            <input type="text" name="username" class="input-field" placeholder="Username" required>
            <input type="password" name="password" class="input-field" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
           </form>
    </div>
</body>
</html>
