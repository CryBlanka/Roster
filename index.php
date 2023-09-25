<!DOCTYPE html>
<html>
<head>
    <title>Wyszukiwanie - Demo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">

        <form action="search" method="GET">
            <label for="category">Wybierz kategorie:</label>
            <select name="category" id="category">
                <option value="artist">Artyści</option>
                <option value="track">Utwory</option>
                <option value="label">Labele</option>
                <option value="upc">UPC</option>
            </select>
            <br><br>
            <label for="search">Pole wyszukiwania:</label>
            <input type="text" name="search" id="search">
            <br><br>
            <input type="submit" value="Search">
        </form>
        <br>
        <p><a href="artists">Pokaż wszystkich artstów</a></p>
        <p><a href="tracks">Pokaż wszystkie utwory</a></p>
        <p><a href="labels">Pokaż wszystkie labele</a></p>
        
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>