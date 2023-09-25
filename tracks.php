<!DOCTYPE html>
<html>
<head>
    <title>Utwory - Demo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .track-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">

        <h1>Utwory</h1>
        <table>
            <thead>
                <tr>
                    <th>Nazwa utworu</th>
                    <th>Artysta</th>
                    <th>Label</th>
                    <th>ISRC</th>
                    <th>UPC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Function to read the roster data from roster.json
                function read_roster_data() {
                    $file_contents = file_get_contents('roster.json');
                    if ($file_contents === false) {
                        return array();
                    }
                    return json_decode($file_contents, true);
                }

                // Display Open Graph tags for sharing on Discord
                echo '<meta property="og:title" content="Clippsly Roster">';
                echo '<meta property="og:description" content="List of all tracks on Clippsly.">';
                echo '<meta property="og:image" content="https://cdfn.clippsly.com/wp-content/uploads/2023/08/RefreshedClippslyLogo1.png">';
                echo '<meta property="og:image:width" content="600">';
                echo '<meta property="og:image:height" content="600">';
                echo '<meta name="theme-color" content="#AD03DE">';

                $roster_data = read_roster_data();
foreach ($roster_data as $entry) {
    echo "<tr>";
    echo "<td><a href='track?id=" . $entry['ID'] . "' target='_blank' class='track-button'>" . $entry['Title'] . "</a></td>";
    echo "<td>" . $entry['Artist'] . "</td>";
    echo "<td>" . $entry['Label'] . "</td>";
    echo "<td>" . $entry['ISRC'] . "</td>";
    echo "<td>" . $entry['UPC'] . "</td>";
    echo "</tr>";
}
                ?>
            </tbody>
        </table>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>
