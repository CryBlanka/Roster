<?php
session_start();
header("X-Robots-Tag: noindex, nofollow", true);

// Create a PDO connection to the database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=nazwa_databasea', 'użytkownik', 'hasło');
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Function to check if a user exists and verify the password
function verifyUser($pdo, $username, $password)
{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        return $user;
    }

    return null;
}

// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: /management");
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the username and password
    $user = verifyUser($pdo, $username, $password);
    if ($user) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['artist_id'] = $user['artist_id']; // Assuming the artist_id is stored in the 'artist_id' column of the 'users' table
        $_SESSION['can_delete'] = $user['can_delete']; // Assuming 'can_delete' is a column in the 'users' table
        $_SESSION['can_edit'] = $user['can_edit'];
        $_SESSION['can_create'] = $user['can_create'];
        $_SESSION['can_terminate'] = $user['can_terminate'];

        header("Location: /management");
        exit;
    } else {
        $error_message = "Nieprawidłowa nazwa użytkownika lub hasło";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://cdn.clippsly.com/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Demo Roster Login</h1>
        <form method="POST" action="">
            <?php if (isset($error_message)) { ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php } ?>
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Login">
            <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
        </form>
    </div>
</body>
</html>
