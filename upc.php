<!DOCTYPE html>
<html>
<head>
    <?php
    // Check if the UPC code is provided in the query string
    if (isset($_GET['upc'])) {
        $upcCode = $_GET['upc'];

        // Read the JSON file and decode the data
        $tracksData = file_get_contents('roster.json');
        $tracks = json_decode($tracksData, true);

        // Initialize an array to store matching tracks
        $matchingTracks = [];

        // Find tracks that match the provided UPC code
        foreach ($tracks as $track) {
            if ($track['UPC'] === $upcCode) {
                $matchingTracks[] = $track;
            }
        }
        ?>
        <title><?php echo $upcCode; ?> - Demo</title>
        <link rel="icon" type="image/x-icon" href="https://cdn.clippsly.com/favicon.ico">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <style>
        .notfound {
            display: inline-block;
            width: 150px;
            height: 150px;
            margin-left: 5px;
            vertical-align: middle;
        }
    </style>
    <body>
        <div class="container">
            <?php
            // Display the UPC code as an h1 heading
            echo '<h1>Kod UPC: ' . htmlspecialchars($upcCode) . '</h1>';

            // Display the list of matching tracks
            if (!empty($matchingTracks)) {
                echo '<ul>';
                foreach ($matchingTracks as $matchingTrack) {
                    echo '<li>';
                    echo '<a href="track?id=' . $matchingTrack['ID'] . '">' . htmlspecialchars($matchingTrack['Title']) . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo "<h1>Ups! Szukaliśmy wysoko i nisko, ale wygląda na to, że ten kod UPC prowadzi nas w pościg za dzikimi gęsiami, bez żadnych śladów w zasięgu wzroku. 🦆🎵 #TracklessUPC 😄🔍</h1>";
                echo '<p>Nie znaleziono utworów dla podanego kodu UPC.</p>';
                echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly404NotFound.png"/>';
            }
        } else {
            echo "<h1>Przepraszamy, ale wygląda na to, że temu utworowi brakuje „Universal Puzzling Code”. 🤷‍♂️🎵 #UPCNotFound 😄🔍</h1>";
            echo '<p>Podaj kod UPC w ciągu zapytania (np. upc?upc=twój_kod_upc)..</p>';
            echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly400BadRequest.png"/>';
        }
        ?>
        </div>
        <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
    </body>
</html>
