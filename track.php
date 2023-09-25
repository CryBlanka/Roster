<!DOCTYPE html>
<html>
<head>
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
        .button {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            width: 11em;
        }
        .button img {
            display: inline-block;
            border-radius: 23%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            width: 20px;
            height: 20px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .notfound {
            display: inline-block;
            width: 150px;
            height: 150px;
            margin-left: 5px;
            vertical-align: middle;
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

        .artist-profile {
            display: inline-block;
            text-align: right;
        }

        .player{
        display: flex;
        align-items: center; /* Vertically center elements */
    }

    .player button {
        /* Your existing styles */
        width: 50px;
        height: 50px;
        margin: 0; /* Remove margin to ensure proper alignment */
        display: flex;
        align-items: center; /* Center content horizontally */
        justify-content: center; /* Center content vertically */
        background-color: transparent; /* Remove background color to avoid interference */
        border: none; /* Remove border for a cleaner look */
    }

    .player audio {
        display: none;
    }

    /* Center the button image within the button */
    .player img {
        display: block; /* Use block display for the image */
    }

    /* Additional adjustments for button hover state */
    .player button:hover {
        background-color: transparent; /* Keep background transparent on hover */
    }

        .artist-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 16px;
        }

        .artist-label {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        </a>
        <?php
        // Check if the track ID is provided in the query string
        if (isset($_GET['id'])) {
            $trackId = $_GET['id'];

            // Read the JSON file and decode the data
            $json = file_get_contents('roster.json');
            $roster = json_decode($json, true);

            // Find the track in the roster by ID
            $track = null;
            foreach ($roster as $t) {
                if ($t['ID'] === $trackId) {
                    $track = $t;
                    break;
                }
            }

            function generateAudioPlayer($audioSource) {
                if (!empty($audioSource)) {
                    echo '<div class="player">';
                    echo '<button class="player button" id="play-pause-btn"><img class="player img" id="play-pause-img" src="https://cdn.clippsly.com/controls/play.png" alt="Play"></button>';
                    echo '<audio class="player audio" id="audio-player" src="' . $audioSource . '" type="audio/mpeg"></audio>';
                    echo '</div>';
                    echo '<script>
                        var audioPlayer = document.getElementById("audio-player");
                        var playPauseBtn = document.getElementById("play-pause-btn");
                        var playPauseImg = document.getElementById("play-pause-img");
                
                        playPauseBtn.addEventListener("click", function() {
                            if (audioPlayer.paused) {
                                audioPlayer.play();
                                playPauseImg.src = "https://cdn.clippsly.com/controls/pause.png";
                            } else {
                                audioPlayer.pause();
                                audioPlayer.currentTime = 0;
                                playPauseImg.src = "https://cdn.clippsly.com/controls/play.png";
                            }
                        });
                
                        audioPlayer.addEventListener("ended", function() {
                            playPauseImg.src = "https://cdn.clippsly.com/controls/play.png";
                        });
                    </script>';
                }
            }

            if ($track) {
                echo '<div class="streaming-container">';
                echo '<h1>' . $track['Title'] . '</h1>';
                generateAudioPlayer($track['Audio']);
                echo '</div>';
                echo '<div class="artist-profile">';
                echo '<a class="button" href="artist?name=' . urlencode($track['Artist']) . '">';
                echo '<span class="artist-name">' . $track['Artist'] . '</span>';
                echo '<img src="' . getArtistProfilePicture($track['Artist']) . '" alt="' . $track['Artist'] . ' Profile">';
                echo '</a>';
                echo '</div>';

                // Check if the Label field is available
                if (isset($track['Label'])) {
                    $labelName = $track['Label'];
                    $labelLogo = '';
                    // Read the labels data and find the label by name
                    $labelsData = file_get_contents('labels.json');
                    $labels = json_decode($labelsData, true)['labels'];
                    foreach ($labels as $label) {
                        if ($label['name'] === $labelName) {
                            $labelLogo = $label['logo'];
                            break;
                        }
                    }

                    echo '<p><strong>Label</strong> ';
                    echo '<a class="button" href="label?label=' . urlencode($labelName) . '">';
                    echo $labelName;
                    echo '<img class="logo" src="' . $labelLogo . '" alt="' . $labelName . ' Logo">';
                    echo '</a></p>';
                }
                if (isset($track['ISRC']) && !empty($track['ISRC'])) {
                    echo '<p><strong>ISRC:</strong> ' . $track['ISRC'] . ' ·  <strong>UPC:</strong> ' . $track['UPC'] . '</p>';        
                }

        echo '<div class="streaming-container">';

                if (isset($track['Spotify']) && !empty($track['Spotify'])) {
                    $spotifyTrackId = $track['Spotify'];
                    echo '<p><a class="streaming spotify" href="https://open.spotify.com/track/' . $spotifyTrackId . '" target="_blank">';
                    echo '<img id="spotifyImg" class="streaming-img" alt="Spotify" title="Spotify" src="https://cdn.clippsly.com/streamings/spotify.png"/>';
                    echo '</a></p>';
                }
    
                if (isset($track['Apple']) && !empty($track['Apple'])) {
                    $appleTrackId = $track['Apple'];
                    echo '<p><a class="streaming" href="https://music.apple.com/album/' . $appleTrackId . '" target="_blank">';
                    echo '<img id="appleImg" class="streaming-img" alt="Apple Music" title="Apple Music" src="https://cdn.clippsly.com/streamings/apple-music.png"/>';
                    echo '</a></p>';
                }

                if (isset($track['ROBLOX']) && !empty($track['ROBLOX'])) {
                    $robloxTrackId = $track['ROBLOX'];
                    echo '<p><a class="streaming" href="https://roblox.com/library/' . $robloxTrackId . '" target="_blank">';
                    echo '<img id="robloxImg" class="streaming-img" alt="ROBLOX" title="ROBLOX" src="https://cdn.clippsly.com/streamings/roblox.png"/>';
                    echo '</a></p>';
                }

                if (isset($track['YouTube']) && !empty($track['YouTube'])) {
                    $youtubeTrackId = $track['YouTube'];
                    echo '<p><a class="streaming" href="https://youtube.com/watch?v=' . $youtubeTrackId . '" target="_blank">';
                    echo '<img id="youtubeImg" class="streaming-img" alt="YouTube" title="YouTube" src="https://cdn.clippsly.com/streamings/yt.png"/>';
                    echo '</a></p>';
                }

                if (isset($track['TIDAL']) && !empty($track['TIDAL'])) {
                    $tidalTrackId = $track['TIDAL'];
                    echo '<p><a class="streaming" href="https://tidal.com/browse/track/' . $tidalTrackId . '" target="_blank">';
                    echo '<img id="tidalImg" class="streaming-img" alt="TIDAL" title="TIDAL" src="https://cdn.clippsly.com/streamings/tidal.png"/>';
                    echo '</a></p>';
                }

                if (isset($track['Amazon']) && !empty($track['Amazon'])) {
                    $amazonTrackId = $track['Amazon'];
                    echo '<p><a class="streaming" href="https://music.amazon.com/albums/' . $amazonTrackId . '" target="_blank">';
                    echo '<img id="amazonImg" class="streaming-img" alt="Amazon Music" title="Amazon Music" src="https://cdn.clippsly.com/streamings/amazon-music.png"/>';
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

        const appleImg = document.getElementById('appleImg');
        if (appleImg) {
            handleImageHover(appleImg, 'https://cdn.clippsly.com/streamings/apple-music_hover.png');
        }

        const robloxImg = document.getElementById('robloxImg');
        if (robloxImg) {
            handleImageHover(robloxImg, 'https://cdn.clippsly.com/streamings/roblox_hover.png');
        }

        const youtubeImg = document.getElementById('youtubeImg');
        if (youtubeImg) {
            handleImageHover(youtubeImg, 'https://cdn.clippsly.com/streamings/yt_hover.png');
        }

        const tidalImg = document.getElementById('tidalImg');
        if (tidalImg) {
            handleImageHover(tidalImg, 'https://cdn.clippsly.com/streamings/tidal_hover.png');
        }

        const amazonImg = document.getElementById('amazonImg');
        if (amazonImg) {
            handleImageHover(amazonImg, 'https://cdn.clippsly.com/streamings/amazon-music_hover.png');
        }
    });
</script>

                <?php
        echo '</div>';
                                

                // Display Open Graph tags for sharing on Discord
                echo '<title>' . $track['Title'] . ' - Demo</title>';
                echo '<meta property="og:title" content="Demo Roster">';
                echo '<meta property="og:title" content="' . $track['Title'] . ' by ' . $track['Artist'] . ' - Demo">';
                echo '<meta property="og:description" content="Check info about ' . $track['Title'] . ' by ' . $track['Artist'] . '">';
                echo '<meta property="og:image" content="' . getArtistProfilePicture($track['Artist']) . '">';
                echo '<meta property="og:image:width" content="600">';
                echo '<meta property="og:image:height" content="600">';
                echo '<meta name="theme-color" content="#9F2B68">';
                echo '<meta property="og:url" content="' . 'https://prezentacja.clippsly.com/track?id=' . $track['ID'] . '">';

            } else {
                echo "<h1>Wygląda na to, że bierzemy udział w muzycznej grze w chowanego, a ten utwór jest profesjonalistą w ukrywaniu się! 🎵🙈 #TrackNotFound 😄🔍</h1>";
                echo '<p>Błąd 404</p>';
                echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly404NotFound.png"/>';
            }
        } else {
            echo "<h1>Ups! Wygląda na to, że identyfikator utworu bawi się w chowanego. Czas wezwać detektywów muzycznych! 🕵️‍♂️🎶 #InvalidTrackID 😄🔍</h1>";
            echo '<p>Błąd 400</p>';
            echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly400BadRequest.png"/>';
        }

        // Function to retrieve artist's profile picture from artlist.json
        function getArtistProfilePicture($artistName) {
            $artistsData = file_get_contents('artlist.json');
            $artists = json_decode($artistsData, true);

            foreach ($artists as $artist) {
                if ($artist['name'] === $artistName) {
                    return $artist['logo'];
                }
            }

            return 'https://clippsly.com/wp-content/uploads/2023/06/cropped-clippsly-favicon.png';
        }
        ?>
    </div>
    <!-- Move head section to the bottom -->
    <script>
        // Discord Embed
        window.onload = function () {
            var metaTag = document.createElement('meta');
            metaTag.setAttribute('property', 'og:type');
            metaTag.setAttribute('content', 'music.song');
            document.head.appendChild(metaTag);
        };
    </script>
        <div class="sticky-link">
        <a href="https://prezentacja.clippsly.com/support?Category=Technical%20Support">
        Błędy na stronie? Zgłoś to!
        </a>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>
