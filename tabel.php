<?php
session_start();
require_once 'routeros_api.class.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

$API = new RouterosAPI();

function getActiveVPNUsers($API) {
    $active_users = array();

    if ($API->connect('ip-mikrotik', 'user-mikrotik', 'password-mikrotik')) {
        $active_connections = $API->comm("/ppp/active/print");

        foreach ($active_connections as $connection) {
            $active_users[] = array(
                'username' => $connection['name'],
                'ip' => $connection['address'],
                'uptime' => $connection['uptime']
            );
        }

        $API->disconnect();
    }

    return $active_users;
}

$active_users = getActiveVPNUsers($API);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active VPN Users</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid red;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: red;
            color: white;
        }
        td {
            background-color: white;
        }
    </style>
</head>
<body>
    <h2>Active VPN Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>IP Address</th>
            <th>Time Connected</th>
        </tr>
        <?php if (count($active_users) > 0): ?>
            <?php foreach ($active_users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['ip']) ?></td>
                <td><?= htmlspecialchars($user['uptime']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center;">No active VPN users</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
