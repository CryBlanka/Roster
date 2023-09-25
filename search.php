<!DOCTYPE html>
<html>
<head>
    <title>Wyniki wyszukiwania - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Wyniki wyszukiwania</h1>
        <?php
        // Check if the category and search query are provided in the query string
        if (isset($_GET['category']) && isset($_GET['search'])) {
            $category = $_GET['category'];
            $searchQuery = $_GET['search'];

            // Perform search based on the selected category
            if ($category === 'artist') {
                // Search for artists
                $artistsData = file_get_contents('artlist.json');
                $artists = json_decode($artistsData, true);

                // Filter the artists based on the search query
                $matchedArtists = array_filter($artists, function ($artist) use ($searchQuery) {
                    return stripos($artist['name'], $searchQuery) !== false;
                });

                echo '<p>Artyści pasujący do Twojego zapytania "' . $searchQuery . '":</p>';

                if (count($matchedArtists) > 0) {
                    echo '<ul>';
                    foreach ($matchedArtists as $matchedArtist) {
                        echo '<li>';
                        echo '<a href="artist?name=' . urlencode($matchedArtist['name']) . '">' . $matchedArtist['name'] . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Nie znaleziono artystów pasujących do Twojego zapytania.</p>';
                }
            } elseif ($category === 'track') {
                // Search for tracks
                $rosterData = file_get_contents('roster.json');
                $tracks = json_decode($rosterData, true);

                // Filter the tracks based on the search query
                $matchedTracks = array_filter($tracks, function ($track) use ($searchQuery) {
                    return stripos($track['Title'], $searchQuery) !== false;
                });

                echo '<p>Utwory pasujące do wyszukiwanego hasła "' . $searchQuery . '":</p>';

                if (count($matchedTracks) > 0) {
                    echo '<ul>';
                    foreach ($matchedTracks as $matchedTrack) {
                        echo '<li>';
                        echo '<a href="track?id=' . $matchedTrack['ID'] . '">' . $matchedTrack['Title'] . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Nie znaleziono utworów pasujących do zapytania.</p>';
                }
            } elseif ($category === 'upc') {
                // Search for tracks
                $rosterData = file_get_contents('roster.json');
                $tracks = json_decode($rosterData, true);

                // Filter the tracks based on the search query
                $matchedTracks = array_filter($tracks, function ($track) use ($searchQuery) {
                    return stripos($track['UPC'], $searchQuery) !== false;
                });

                echo '<p>Utwory pasujące do wyszukiwanego hasła"' . $searchQuery . '":</p>';

                if (count($matchedTracks) > 0) {
                    echo '<ul>';
                    foreach ($matchedTracks as $matchedTrack) {
                        echo '<li>';
                        echo '<a href="track?id=' . $matchedTrack['ID'] . '">' . $matchedTrack['Title'] . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Nie znaleziono utworów pasujących do zapytania.</p>';
                }
            } elseif ($category === 'label') {
                // Search for labels
                $labelsData = file_get_contents('labels.json');
                $labels = json_decode($labelsData, true)['labels'];

                // Filter the labels based on the search query
                $matchedLabels = array_filter($labels, function ($label) use ($searchQuery) {
                    return stripos($label['name'], $searchQuery) !== false;
                });

                echo '<p>Labele pasujące do wyszukiwanego hasła "' . $searchQuery . '":</p>';

                if (count($matchedLabels) > 0) {
                    echo '<ul>';
                    foreach ($matchedLabels as $matchedLabel) {
                        echo '<li>';
                        echo '<a href="label?label=' . urlencode($matchedLabel['name']) . '">' . $matchedLabel['name'] . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Nie znaleziono labeli pasujących do Twojego zapytania.</p>';
                }
            } else {
                echo '<p>Nieprawidłowa kategoria</p>';
            }
        } else {
            echo '<p>Nieprawidłowe parametry wyszukiwania</p>';
        }
        ?>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>
