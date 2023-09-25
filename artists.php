<!DOCTYPE html>
<html>
<head>
    <title>Artyści - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .label {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 5px;
            background-color: #e9e9e9;
            color: #333;
            font-size: 12px;
            margin-left: 5px;
        }

        .label-logo {
            display: inline-block;
            border-radius: 23%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            width: 12px;
            height: 12px;
            vertical-align: middle;
            margin-left: 5px;
        }

        a {
            width: 290px; /* Adjust the width as desired */
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $labelsData = json_decode(file_get_contents('labels.json'), true);
        $labels = $labelsData['labels'];
        $artists = json_decode(file_get_contents('artlist.json'), true);

        // Get the number of artists
        $artistCount = count($artists);

        echo '<h1>Lista artystów</h1>';
        echo '<p><strong>Liczba artystów:</strong> ' . $artistCount . '</p>';
        echo '<ul>';
        foreach ($artists as $artist) {
            echo '<li>';
            echo '<a href="artist?name=' . urlencode($artist['name']) . '">' . $artist['name'] . '<span class="label">' . $artist['label'];
            echo '<img class="label-logo" src="' . getLabelLogo($labels, $artist['label']) . '" alt="' . $artist['label'] . ' Logo"></span></a>';
            echo '</li>';
        }
        echo '</ul>';

        function getLabelLogo($labels, $labelName) {
            foreach ($labels as $label) {
                if ($label['name'] === $labelName) {
                    return $label['logo'];
                }
            }
            return '';
        }
        ?>
            <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
    </div>
</body>
</html>
