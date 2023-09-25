<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .label-logo {
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .notfound {
            display: inline-block;
            width: 150px;
            height: 150px;
            margin-left: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_GET['label'])) {
            $labelName = $_GET['label'];

            // Read the JSON file and decode the data
            $json = file_get_contents('labels.json');
            $data = json_decode($json, true);

            $labels = $data['labels'];

            // Find the label in the data by name
            $label = null;
            foreach ($labels as $lbl) {
                if ($lbl['name'] === $labelName) {
                    $label = $lbl;
                    break;
                }
            }

            // Display the label info if found
            if ($label) {
                echo '<h1>' . $label['name'] . '</h1>';
                echo '<img class="label-logo" src="' . $label['logo'] . '" alt="' . $label['name'] . ' Logo" width="200">';

                // Get the artists associated with the label
                $artists = json_decode(file_get_contents('artlist.json'), true);

                // Filter the artists by the label name
                $artistsByLabel = array_filter($artists, function($artist) use ($labelName) {
                    return $artist['label'] === $labelName;
                });

                // Display the artist list
                echo '<h2>Lista artystów</h2>';
                echo '<ul>';
                foreach ($artistsByLabel as $artist) {
                    echo '<li>';
                    echo '<a href="artist?name=' . urlencode($artist['name']) . '">' . $artist['name'] . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo "<h1>Wygląda na to, że weszliśmy w labelową wersję Narnii – chowa się ona gdzieś w cyfrowej szafie! 🧙‍♂️🏞️ #LabelNotFound 😄🔍</h1>";
                echo '<p>Błąd 404</p>';
                echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly404NotFound.png"/>';
            }
        } else {
            echo "<h1>Uh-oh, wygląda na to, że nazwa labelu zdecydowała się na incognito! 🕵️‍♀️📀 #InvalidLabelName 😄🕶️</h1>";
            echo '<p>Błąd 400</p>';
            echo '<img class="notfound" alt="" src="https://cdn.clippsly.com/brand_assets/Clippsly400BadRequest.png"/>';
        }

        // Generate Discord embed meta tags
        if ($label) {
            echo '<title>' . $label['name'] . ' - Demo</title>';
            echo '<meta property="og:title" content="' . $label['name'] . ' - Demo">';
            echo '<meta property="og:description" content="Check info about ' . $label['name'] . '">';
            echo '<meta property="og:image" content="' . $label['logo'] . '">';
            echo '<meta property="og:image:width" content="600">';
            echo '<meta property="og:image:height" content="600">';
            echo '<meta name="theme-color" content="#ff7420">';
            echo '<meta property="og:url" content="' . 'https://prezentacja.clippsly.com/label?label=' . urlencode($label['name']) . '">';
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
