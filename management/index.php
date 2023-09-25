<?php
session_start();
header("X-Robots-Tag: noindex, nofollow", true);

// Check if the user is not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../admin");
    exit;
}

$logged_in_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Demo  Admin</h2>
    <?php
    echo "<p>Cześć " . $logged_in_username . ", miłego dnia!</p>";
    ?>
        <br>
        <p><a href="artist">Zarządzanie artystami</a></p>
        <p><a href="roster">Zarządzanie utworami</a></p>
        <p><a href="label">Zarządzanie labelami</a></p>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>
