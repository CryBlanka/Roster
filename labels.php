<!DOCTYPE html>
<html>
<head>
    <title>Labele - Demo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .label {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 5px;
            color: #333;
            font-size: 12px;
            margin-left: 5px;
        }

        .label-logo {
            display: inline-block;
            border-radius: 25%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            width: 20px;
            height: 20px;
            vertical-align: middle;
            margin-left: 5px;
            margin-top: -4px;
        }

        a {
            width: 290px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $labelsData = file_get_contents('labels.json');
        $labels = json_decode($labelsData, true)['labels'];

        $labelCount = count($labels);

        echo '<h1>Labele</h1>';
        echo '<p><strong>Liczba labeli:</strong> ' . $labelCount . '</p>';
        echo '<ul>';
        foreach ($labels as $label) {
            echo '<li>';
            echo '<a href="label?label=' . urlencode($label['name']) . '">' . $label['name'] . '<span class="label">';
            echo '<img class="label-logo" src="' . $label['logo'] . '" alt="' . $label['name'] . ' Logo"></span></a>';
            echo '</li>';
        }
        echo '</ul>';
        ?>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>
