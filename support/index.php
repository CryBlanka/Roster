<!DOCTYPE html>
<html>
<head>
    <title>Skontaktuj się z nami - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Skontaktuj się z nami</h1>
        <form action="send-email" method="post">
            <label for="name">Imie:</label>
            <input type="text" name="name" required><br><br>

            <label for="email">E-mail:</label>
            <input type="email" name="email" required><br><br>

            <label for="category">Kategoria:</label>
            <select name="category" required>
            <option value="General Support" <?php if ($_GET['Category'] === 'General Support') echo 'selected'; ?>>Ogólne wsparcie</option>
            <option value="Copyright/DMCA" <?php if ($_GET['Category'] === 'Copyright/DMCA') echo 'selected'; ?>>Prawa autorskie/DMCA</option>
            <option value="Suggestions" <?php if ($_GET['Category'] === 'Suggestions') echo 'selected'; ?>>Propozycje</option>
            <option value="Partnership" <?php if ($_GET['Category'] === 'Partnership') echo 'selected'; ?>>Współpraca</option>
            <option value="Technical Support" <?php if ($_GET['Category'] === 'Technical Support') echo 'selected'; ?>>Pomoc techniczna</option>
            </select><br><br>

            <label for="content">Wiadomość:</label><br>
            <textarea name="content" rows="4" cols="50" required></textarea><br><br>

            <!-- Add reCAPTCHA widget -->
            <div class="g-recaptcha" data-sitekey="6Lfoy5YnAAAAADRhRg8eDcMrLFPiw3alkE_WHEyP"></div>

            <input type="submit" value="Submit">
        </form>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
