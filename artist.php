<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="https://cdn.clippsly.com/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    function handleImageHover(imageElement, hoverUrl) {
        const originalUrl = imageElement.getAttribute('src');
        
        imageElement.addEventListener('mouseenter', () => {
            imageElement.setAttribute('src', hoverUrl);
        });

        imageElement.addEventListener('mouseleave', () => {
            imageElement.setAttribute('src', originalUrl);
        });
    }
</script>
    <style>
        .label-button {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #e9e9e9;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .label-button:hover {
            background-color: #ccc;
        }

        .label-logo {
            display: inline-block;
            border-radius: 23%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            width: 20px;
            height: 20px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .art-logo {
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .streaming {
            display: inline-block;
            margin-right: 5px;
            width: 30px;
            height: 39px;
            padding: 5px 10px;
            border-radius: 8px;
            background-color: #e9e9e9;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .streaming-img {
            display: inline-block;
            width: 50px;
            height: 50px;
            margin: -25px;
            border-radius: 15%;
        }

        .streaming-container {
            display: flex; /* Use flexbox */
            justify-content: space-between; /* Add space between items */
        }

        .badge-container {
            display: flex;
            justify-content: center; /* Center badges horizontally on PC */
            overflow-x: auto; /* Add horizontal scrolling for mobile devices */
            margin-top: 15px;
        }

        .badge-list-container {
            display: flex;
            flex-wrap: nowrap; /* Set flex-wrap to nowrap to keep badges horizontal */
            align-items: center; /* Center badges vertically on PC */
        }

        .badge-list-container li {
            text-align: center; /* Center badge image and title */
            flex: 0 0 150px; /* Fix the width of the badge item */
            margin: 5px; /* Add some margin between badges */
        }

        .badge-list-container img {
            width: 50px; /* Adjust the badge size */
            height: 50px; /* Adjust the badge size */
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .badge-list-container span {
            display: block; /* Display the title below the image */
            margin-top: 5px; /* Add spacing between the badge image and title */
            white-space: nowrap; /* Prevent the title from wrapping */
            overflow: hidden; /* Hide any overflow to avoid stretching badges */
            text-overflow: ellipsis; /* Add ellipsis (...) for longer titles */
        }

        .notfound {
            display: inline-block;
            width: 150px;
            height: 150px;
            margin-left: 5px;
            vertical-align: middle;
        }
    </style>
<?php
    // Check if the artist name is provided in the query string
    if (isset($_GET['name'])) {
        $artistName = urldecode($_GET['name']);

        // Read the JSON file and decode the data
        $artistsData = file_get_contents('artlist.json');
        $artists = json_decode($artistsData, true);

        // Find the artist in the list by name
        $artist = null;
        foreach ($artists as $a) {
            if ($a['name'] === $artistName) {
                $artist = $a;
                break;
            }
        }

        // Generate Discord embed meta tags
        if ($artist) {
            echo '<title>' . $artist['name'] . ' - Demo</title>';
            echo '<meta property="og:title" content="Demo Roster">';
            echo '<meta property="og:description" content="Check info about ' . $artist['name'] . '">';
            echo '<meta property="og:image" content="' . $artist['logo'] . '">';
            echo '<meta property="og:image:width" content="600">';
            echo '<meta property="og:image:height" content="600">';
            echo '<meta name="theme-color" content="#9F2B68">';
            echo '<meta property="og:url" content="' . 'https://prezentacja.clippsly.com/artist?name=' . urlencode($artist['name']) . '">';
            
        }
    }
    ?>
</head>
<body>
    <div class="container">
        <?php
        
        // Check if the artist name is provided in the query string
        if (isset($_GET['name'])) {
            $artistName = urldecode($_GET['name']);

            // Read the JSON file and decode the data
            $artistsData = file_get_contents('artlist.json');
            $artists = json_decode($artistsData, true);

            // Find the artist in the list by name
            $artist = null;
            foreach ($artists as $a) {
                if ($a['name'] === $artistName) {
                    $artist = $a;
                    break;
                }
            }

                // Check if the artist is banned
    if ($artist && isset($artist['Banned']) && $artist['Banned'] === true) {
        // Display a message for banned artists
        echo '<h1>Wygląda na to, że ten artysta zagubił się w cyfrowym Trójkącie Bermudzkim! 😅🌐</h1>';
        echo '<p>Artysta powiązany z tą stroną został usunięty.</p>';
        echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly400BadRequest.png"/>';
    } elseif ($artist) {
                echo '<h1>' . $artist['name'] . '</h1>';
                echo '<img class="art-logo" src="' . $artist['logo'] . '" alt="' . $artist['name'] . ' Logo" width="200">';

                

                // Check if the artist has a label
                $labelName = $artist['label'];

                if (!empty($labelName)) {
                    $labelsData = file_get_contents('labels.json');
                    $labels = json_decode($labelsData, true)['labels'];
                    $labelLogo = '';

                    foreach ($labels as $label) {
                        if ($label['name'] === $labelName) {
                            $labelLogo = $label['logo'];
                            break;
                        }
                    }

                    // Display the label name and logo if available
                    if (!empty($labelLogo)) {
                        echo '<p><strong>Label:</strong> ';
                        echo '<a class="label-button" href="label?label=' . urlencode($labelName) . '">';
                        echo $labelName;
                        echo '<img class="label-logo" src="' . $labelLogo . '" alt="' . $labelName . ' Logo">';
                        echo '</a></p>';
                    }
                }

                echo '<div class="streaming-container">';
    
                if (isset($artist['Spotify']) && !empty($artist['Spotify'])) {
                    $spotifySocialId = $artist['Spotify'];
                    echo '<p><a class="streaming spotify" href="https://open.spotify.com/artist/' . $spotifySocialId . '" target="_blank">';
                    echo '<img id="spotifyImg" class="streaming-img" alt="Spotify" title="Spotify" src="https://cdn.clippsly.com/streamings/spotify.png"/>';
                    echo '</a></p>';
                }
    
                if (isset($artist['X']) && !empty($artist['X'])) {
                    $xSocialId = $artist['X'];
                    echo '<p><a class="streaming" href="https://twitter.com/' . $xSocialId . '" target="_blank">';
                    echo '<img id="xImg" class="streaming-img" alt="X (Twitter))" title="X (Twitter)" src="https://cdn.clippsly.com/streamings/x-twitter.png"/>';
                    echo '</a></p>';
                }

                if (isset($artist['Instagram']) && !empty($artist['Instagram'])) {
                    $instagramSocialId = $artist['Instagram'];
                    echo '<p><a class="streaming" href="https://instagram.com/' . $instagramSocialId . '" target="_blank">';
                    echo '<img id="instagramImg" class="streaming-img" alt="Instagram" title="Instagram" src="https://cdn.clippsly.com/streamings/instagram.png"/>';
                    echo '</a></p>';
                }

                if (isset($artist['YouTube']) && !empty($artist['YouTube'])) {
                    $youtubeSocialId = $artist['YouTube'];
                    echo '<p><a class="streaming" href="https://youtube.com/@' . $youtubeSocialId . '" target="_blank">';
                    echo '<img id="youtubeImg" class="streaming-img" alt="YouTube" title="YouTube" src="https://cdn.clippsly.com/streamings/yt.png"/>';
                    echo '</a></p>';
                }

                if (isset($artist['SoundCloud']) && !empty($artist['SoundCloud'])) {
                    $soundcloudSocialId = $artist['SoundCloud'];
                    echo '<p><a class="streaming" href="https://soundcloud.com/' . $soundcloudSocialId . '" target="_blank">';
                    echo '<img id="soundcloudImg" class="streaming-img" alt="SoundCloud" title="SoundCloud" src="https://cdn.clippsly.com/streamings/soundcloud.png"/>';
                    echo '</a></p>';
                }

                if (isset($artist['Discord']) && !empty($artist['Discord'])) {
                    $discordSocialId = $artist['Discord'];
                    echo '<p><a class="streaming" href="https://discord.com/users/' . $discordSocialId . '" target="_blank">';
                    echo '<img id="discordImg" class="streaming-img" alt="Discord" title="Discord" src="https://cdn.clippsly.com/streamings/discord.png"/>';
                    echo '</a></p>';
                }

                if (isset($artist['ROBLOX']) && !empty($artist['ROBLOX'])) {
                    $robloxSocialId = $artist['ROBLOX'];
                    echo '<p><a class="streaming" href="https://roblox.com/users/' . $robloxSocialId . '" target="_blank">';
                    echo '<img id="robloxImg" class="streaming-img" alt="ROBLOX" title="ROBLOX" src="https://cdn.clippsly.com/streamings/roblox.png"/>';
                    echo '</a></p>';
                }
                ?>

<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        // Check if the element exists before attaching the hover effect
        const spotifyImg = document.getElementById('spotifyImg');
        if (spotifyImg) {
            handleImageHover(spotifyImg, 'https://cdn.clippsly.com/streamings/spotify_hover.png');
        }

        const xImg = document.getElementById('xImg');
        if (xImg) {
            handleImageHover(xImg, 'https://cdn.clippsly.com/streamings/x-twitter_hover.png');
        }

        const instagramImg = document.getElementById('instagramImg');
        if (instagramImg) {
            handleImageHover(instagramImg, 'https://cdn.clippsly.com/streamings/instagram_hover.png');
        }

        const youtubeImg = document.getElementById('youtubeImg');
        if (youtubeImg) {
            handleImageHover(youtubeImg, 'https://cdn.clippsly.com/streamings/yt_hover.png');
        }

        const soundcloudImg = document.getElementById('soundcloudImg');
        if (soundcloudImg) {
            handleImageHover(soundcloudImg, 'https://cdn.clippsly.com/streamings/soundcloud_hover.png');
        }

        const discordImg = document.getElementById('discordImg');
        if (discordImg) {
            handleImageHover(discordImg, 'https://cdn.clippsly.com/streamings/discord_hover.png');
        }

        const robloxImg = document.getElementById('robloxImg');
        if (robloxImg) {
            handleImageHover(robloxImg, 'https://cdn.clippsly.com/streamings/roblox_hover.png');
        }
    });
</script>

                <?php
                echo '</div>';

                // Display the badges or the "no badges" message
                if (!empty($artist['badges'])) {
                    $badgesData = file_get_contents('badges.json');
                    $badges = json_decode($badgesData, true)['badges'];
                    echo '<h1>Odznaki artysty</h1>';
                    echo '<div class="badge-container">';
                    echo '<div class="badge-list-container">';
                    echo '<ul class="badge-list">';
                    foreach ($artist['badges'] as $badgeName) {
                        $badgeImage = '';
                        $badgeDescription = '';

                        foreach ($badges as $badge) {
                            if ($badge['name'] === $badgeName) {
                                $badgeImage = $badge['image'];
                                $badgeDescription = $badge['description'];
                                break;
                            }
                        }

                        if (!empty($badgeImage)) {
                            echo '<li>';
                            echo '<img src="' . $badgeImage . '" alt="' . $badgeName . ' Badge" title="' . $badgeDescription . '">';
                            echo '<span>' . $badgeName . '</span>';
                            echo '</li>';
                        }
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<p>Ten artysta nie ma odznak.</p>';
                }

                echo '<ul>';

                // Display the artist\'s tracks
                $tracks = json_decode(file_get_contents('roster.json'), true);
                echo '<h1>Utwory artysty</h1>';
                foreach ($tracks as $track) {
                    if ($track['Artist'] === $artist['name']) {
                        echo '<li>';
                        echo '<a href="track?id=' . $track['ID'] . '">' . $track['Title'] . '</a>';
                        echo '</li>';
                    }
                }


                echo '</ul>';
            } else {
                echo '<h1>Ups, wygląda na to, że nasza wyszukiwarka minęła tego artystę! 🚀🕺 #404Błąd 😄🎶</h1>';
                echo '<p>Błąd 404</p>';
                echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly404NotFound.png"/>';
            }
        } else {
            echo "<h1>Przepraszamy, mamy tu moment „kryzysu tożsamości” – wygląda na to, że nazwisko artysty przeszło w tryb incognito! 🕵️‍♂️🎤 #400Błąd 😄🔍</h1>";
            echo '<p>Błąd 400</p>';
            echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly400BadRequest.png"/>';
        }
        ?>
    </div>
    <div class="sticky-link">
        <a href="https://prezentacja.clippsly.com/support?Category=Technical%20Support">
        Błędy na stronie? Zgłoś to!
        </a>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>