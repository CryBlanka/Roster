<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../admin");
    exit;
}

// Function to generate a unique ID for a new entry
function generate_unique_id($data) {
    $ids = array_column($data, "ID");
    $new_id = max($ids) + 1;
    return str_pad($new_id, 3, "0", STR_PAD_LEFT);
}

// Function to read the roster data from roster.json
function read_roster_data() {
    $file_contents = file_get_contents('../roster.json');
    if ($file_contents === false) {
        return array();
    }
    return json_decode($file_contents, true);
}

// Function to write the roster data to roster.json
function write_roster_data($data) {
    $updated_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('../roster.json', $updated_data);
}



// Function to add a new entry to the roster
function add_roster_entry($artist, $title, $label, $spotify, $apple, $roblox, $audio, $isrc, $upc) {
    $data = read_roster_data();

    $new_entry = array(
        "Artist" => $artist,
        "Title" => $title,
        "Label" => $label,
        "Spotify" => $spotify,
        "Apple" => $apple,
        "Roblox" => $roblox,
        "Audio" => $audio,
        "ISRC" => $isrc,
        "UPC" => $upc,
        "ID" => generate_unique_id($data)
    );

    $data[] = $new_entry;
    write_roster_data($data);

    echo "<script>alert('Utwór został dodany');</script>";
}

// Function to edit an existing entry in the roster
function edit_roster_entry($entry_id, $artist, $title, $label, $spotify, $apple, $roblox, $audio, $isrc, $upc) {
    $data = read_roster_data();

    foreach ($data as &$entry) {
        if ($entry['ID'] === $entry_id) {
            $entry['Artist'] = $artist;
            $entry['Title'] = $title;
            $entry['Label'] = $label;
            $entry['Spotify'] = $spotify;
            $entry['Apple'] = $apple;
            $entry['Roblox'] = $roblox;
            $entry['Audio'] = $audio;
            $entry['ISRC'] = $isrc;
            $entry['UPC'] = $upc;
            break;
        }
    }

    write_roster_data($data);

    echo "<script>alert('Utwór został zaktualizowany');</script>";
}

// Function to remove an entry from the roster
function remove_roster_entry($entry_id) {
    $data = read_roster_data();

    $entry_to_remove = null;
    foreach ($data as $index => $entry) {
        if ($entry['ID'] === $entry_id) {
            $entry_to_remove = $entry;
            unset($data[$index]);
            break;
        }
    }

    if ($entry_to_remove) {
        write_roster_data(array_values($data));
        echo "<script>alert('Utwór został usunięty');</script>";
    }
}

function send_artist_approval_email($artistEmail, $trackTitle, $artist) {
    // Construct the email subject
    $emailSubjectToArtist = "Track Approval: $trackTitle";

    // Email body for the artist using your provided template
    $userEmailBody = '
    <div class="container" style="max-width: 600px; margin: 0px auto; padding: 20px; border-radius: 10px; background-color: rgb(255, 255, 255);">
        <div class="logo" style="text-align: center; margin-bottom: 20px">
            <img src="https://cdn.clippsly.com/brand_assets/logo2023full.png" alt="Clippsly Logo" style="max-width: 200px">
            <br>
        </div>
        <h1 style="margin: 0px 0px 20px; padding: 0px; font-size: 24px; text-align: center;">
            Track approved!
            <br>
        </h1>
        <p style="margin: 0px 0px 10px; padding: 0px; text-align: center;">
        ' . htmlspecialchars($trackTitle) . ' - <a href="https://roster.clippsly.com/artist?name=' . htmlspecialchars($artist) . '">https://roster.clippsly.com/artist?name=' . htmlspecialchars($artist) . '</a>
            <br>
        </p>
        <div class="footer" style="margin-top: 40px; text-align: center; font-size: 12px; color: rgb(119, 119, 119);">
            <p style="margin: 0px 0px 10px; padding: 0px; text-align: center;">
                You received this email to provide information and updates around your Clippsly account.
                <br>
            </p>
            <div class="logo2" style="text-align: center;">
                <img src="https://cdn.clippsly.com/brand_assets/logo2023fullblack.png" alt="Clippsly Mini Logo" style="max-width: 100px;">
                <br>
            </div>
            <p style="margin: 0px 0px 10px; padding: 0px; text-align: center;">
                Clippsly Ltd, Unit A 82 James Carter Road, Mildenhall Industrial Estate, Mildenhall, Suffolk, England, IP28 7DE
                <br>
            </p>
        </div>
    </div>
    <div>
        <br>
    </div>
    ';

    // Send email to the artist using SendGrid API
    $userData = [
        "personalizations" => [
            [
                "to" => [
                    [
                        "email" => $artistEmail // Replace with the artist's actual email address
                    ]
                ],
                "subject" => $emailSubjectToArtist
            ]
        ],
        "from" => [
            "email" => "demo@deafult.com",
            "name" => "Team Clippsly"
        ],
        "content" => [
            [
                "type" => "text/html",
                "value" => $userEmailBody
            ]
        ]
    ];

    $apiKey = "sendgrid api key"; // Replace with your actual SendGrid API Key
    $url = "https://api.sendgrid.com/v3/mail/send";

    $headers = array(
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    );

    $userCh = curl_init();
    curl_setopt($userCh, CURLOPT_URL, $url);
    curl_setopt($userCh, CURLOPT_POST, 1);
    curl_setopt($userCh, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($userCh, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($userCh, CURLOPT_RETURNTRANSFER, true);

    $userResponse = curl_exec($userCh);
    curl_close($userCh);
}


// Function to read the artlist data from artlist.json
function read_artlist_data() {
    $file_contents = file_get_contents('../artlist.json');
    if ($file_contents === false) {
        return array();
    }
    return json_decode($file_contents, true);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['roster_submit'])) {
        $artist = $_POST['artist'];
        $title = $_POST['title'];
        $label = $_POST['label'];
        $spotify = $_POST['spotify'];
        $apple = $_POST['apple'];
        $roblox = $_POST['roblox'];
        $audio = $_POST['audio'];
        $isrc = $_POST['isrc'];
        $upc = $_POST['upc'];

        if (empty($artist) || empty($title) || empty($label)) {
            echo "<script>alert('Proszę wypełnić wszystkie wymagane pola');</script>";
        } else {
            $entry_id = $_POST['entry_id'];

                        // Read the artist's data from artlist.json
                        $artlist_data = read_artlist_data();

                        // Find the artist's email by matching the artist's name
                        $artistEmail = "";
                        foreach ($artlist_data as $artistEntry) {
                            if ($artistEntry['name'] === $artist) {
                                $artistEmail = $artistEntry['email'];
                                break;
                            }
                        }

            if (empty($entry_id)) {
                add_roster_entry($artist, $title, $label, $spotify, $apple, $roblox, $audio, $isrc, $upc);
                send_artist_approval_email($artistEmail, $title, $artist);
            } else {
                edit_roster_entry($entry_id, $artist, $title, $label, $spotify, $apple, $roblox, $audio, $isrc, $upc);
                send_artist_approval_email($artistEmail, $title, $artist);
            }
        }
    } elseif (isset($_POST['edit_submit'])) {
        $entry_id = $_POST['entry_id'];

        // Retrieve the entry details from the roster data
        $roster_data = read_roster_data();
        $entry = array_filter($roster_data, function($e) use ($entry_id) {
            return $e['ID'] === $entry_id;
        });

        // Populate the form with the entry details
        if (!empty($entry)) {
            $entry = array_values($entry)[0];
            echo "<script>
                document.getElementById('artist').value = '{$entry['Artist']}';
                document.getElementById('title').value = '{$entry['Title']}';
                document.getElementById('label').value = '{$entry['Label']}';
                document.getElementById('spotify').value = '{$entry['Spotify']}';
                document.getElementById('apple').value = '{$entry['Apple']}';
                document.getElementById('roblox').value = '{$entry['ROBLOX']}';
                document.getElementById('audio').value = '{$entry['Audio']}';
                document.getElementById('isrc').value = '{$entry['ISRC']}';
                document.getElementById('upc').value = '{$entry['UPC']}';
                document.getElementsByName('entry_id')[0].value = '{$entry['ID']}';
            </script>";
        }
    } elseif (isset($_POST['remove_submit'])) {
        $entry_id = $_POST['entry_id'];

        if (!empty($entry_id)) {
            remove_roster_entry($entry_id);
        }
    }
}

// Read the roster data from roster.json
$roster_data = read_roster_data();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Roster Admin - Clippsly</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container-adm">
        <h1>Roster Admin</h1>
        <!-- Logout link -->
        <br>
        <a href="../logout">Wyloguj</a>

        <!-- Roster Form -->
        <h2>Dodaj/edytuj utwór</h2>
        <form method="POST" action="">
            <input type="hidden" name="entry_id" value="">
            <label for="artist">Artysta:</label>
            <input type="text" id="artist" name="artist" required><br>

            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="label">Label:</label>
            <input type="text" id="label" name="label" required><br>

            <label for="spotify">Spotify:</label>
            <input type="text" id="spotify" name="spotify"><br>

            <label for="apple">Apple Music:</label>
            <input type="text" id="apple" name="apple"><br>

            <label for="roblox">Roblox Catalog ID:</label>
            <input type="text" id="roblox" name="roblox"><br>

            <label for="audio">Plik audio:</label>
            <input type="text" id="audio" name="audio"><br>

            <label for="isrc">ISRC:</label>
            <input type="text" id="isrc" name="isrc"><br>

            <label for="upc">UPC:</label>
            <input type="text" id="upc" name="upc"><br>

            <input type="submit" name="roster_submit" value="Wyślij">
        </form>

        <!-- Roster Table -->
        <h2>Roster</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Artysta</th>
                    <th>Tytuł</th>
                    <th>Label</th>
                    <th>Spotify</th>
                    <th>Apple Music</th>
                    <th>ISRC</th>
                    <th>UPC</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($roster_data as $entry) {
                    echo "<tr>";
                    echo "<td>" . $entry['ID'] . "</td>";
                    echo "<td>" . $entry['Artist'] . "</td>";
                    echo "<td>" . $entry['Title'] . "</td>";
                    echo "<td>" . $entry['Label'] . "</td>";
                    echo "<td>" . $entry['Spotify'] . "</td>";
                    echo "<td>" . $entry['Apple'] . "</td>";
                    echo "<td>" . $entry['ISRC'] . "</td>";
                    echo "<td>" . $entry['UPC'] . "</td>";
                    echo "<td>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='entry_id' value='" . $entry['ID'] . "'>";
                    echo "<input type='submit' name='edit_submit' value='Edytuj'>";
                    echo "<input type='submit' name='remove_submit' value='Usuń'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <img class="sticky-image" src="https://roster.clippsly.com/management/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html>

