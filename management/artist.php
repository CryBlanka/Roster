<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
header("X-Robots-Tag: noindex, nofollow", true);

// Check if the user is not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../admin");
    exit;
}

// Function to generate a unique ID for a new artist
function generate_unique_id($data) {
    $ids = array_column($data, "ID");
    $new_id = max($ids) + 1;
    return str_pad($new_id, 3, "0", STR_PAD_LEFT);
}

// Function to read the artist data from artlist.json
function read_artist_data() {
    $file_contents = file_get_contents('../artlist.json');
    if ($file_contents === false) {
        return array();
    }
    return json_decode($file_contents, true);
}

// Function to write the artist data to artlist.json
function write_artist_data($data) {
    $updated_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('../artlist.json', $updated_data);
}

function send_artist_reinstated_email($artistEmail, $artistName, $artist) {
    // Construct the email subject
    $emailSubjectToArtist = "Account reinstated: $artistName";

    // Email body for the artist using your provided template
    $userEmailBody = '<div class="container" style="max-width: 600px; margin: 0px auto; padding: 20px; border-radius: 10px; background-color: rgb(255, 255, 255);">
    <div class="logo" style="text-align: center; margin-bottom: 20px">
        <img src="https://cdn.clippsly.com/brand_assets/logo2023full.png" alt="Clippsly Logo" style="max-width: 200px">
        <br>
    </div>
    <h1 style="margin: 0px 0px 20px; padding: 0px; font-size: 24px; text-align: center;">
        Account Reinstated!
        <br>
    </h1>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        We sincerely apologize for any inconvenience caused. Your account has been reinstated after a review of your recent activities. We value your presence on our platform and understand that sometimes misunderstandings can occur.
        <br>
    </p>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        We have reevaluated the situation and have decided to lift the restrictions on your account. You can now access all the features and services without any limitations.
        <br>
    </p>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        If you have any questions or require further assistance, please feel free to reach out to our support team. We are here to help you with any concerns you may have.
        <br>
    </p>
    <div class="buttonish" style="text-align: center">
        <a class="button" href="https://clippsly.com/" style="display: inline-block; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-top: 20px; background-color: rgb(33, 150, 243); color: rgb(255, 255, 255);" target="_blank">
            Visit Clippsly
        </a>
        <br>
    </div>
    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 12px; color: rgb(119, 119, 119);">
        <p style="margin: 0px 0px 10px; padding: 0px;">
            Thank you for choosing Clippsly. We appreciate your understanding and look forward to providing you with an excellent experience.
            <br>
        </p>
        <div class="logo2" style="text-align: center;">
            <img src="https://cdn.clippsly.com/brand_assets/logo2023fullblack.png" alt="Clippsly Mini Logo" style="max-width: 100px;">
            <br>
        </div>
        <p style="margin: 0px 0px 10px; padding: 0px;">
            Clippsly Ltd, Unit A 82 James Carter Road, Mildenhall Industrial Estate, Mildenhall, Suffolk, England, IP28 7DE
            <br>
        </p>
    </div>
</div>
<div>
    <br>
</div>';

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

function send_artist_banned_email($artistEmail, $artistName, $artist) {
    // Construct the email subject
    $emailSubjectToArtist = "Account deleted: $artistName";

    // Email body for the artist using your provided template
    $userEmailBody = '

    <div class="container" style="max-width: 600px; margin: 0px auto; padding: 20px; border-radius: 10px; background-color: rgb(255, 255, 255);">
    <div class="logo" style="text-align: center; margin-bottom: 20px">
        <img src="https://cdn.clippsly.com/brand_assets/logo2023full.png" alt="Clippsly Logo" style="max-width: 200px">
        <br>
    </div>
    <h1 style="margin: 0px 0px 20px; padding: 0px; font-size: 24px; text-align: center;">
        Account deleted!
        <br>
    </h1>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        We regret to inform you that your account has violated our Terms &amp; Conditions. Upholding the integrity of our platform is our top priority, and we take violations seriously. After reviewing your account activity, it has come to our attention that certain actions or behaviors have contravened our guidelines. To ensure fairness and maintain a safe environment for all users, appropriate measures will be taken to bring your account back into compliance.
        <br>
    </p>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        We understand that there may be circumstances or explanations behind the violations, and we want to provide you with an opportunity to appeal. You have 24 hours from the receipt of this email to reply and present your case. We will carefully consider your response and take it into account during our decision-making process.
        <br>
    </p>
    <p style="margin: 0px 0px 10px; padding: 0px;">
        Please take this time to thoroughly review our Terms &amp; Conditions to better understand the nature of the violations. If you require any clarification or have questions regarding the specific issues, our support team is available to assist you.
        <br>
    </p>
    <div class="buttonish" style="text-align: center">
        <a class="button" href="https://clippsly.com/terms-and-conditions/" style="display: inline-block; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-top: 20px; background-color: rgb(33, 150, 243); color: rgb(255, 255, 255);" target="_blank">
            Terms &amp; Conditions
        </a>
        <br>
    </div>
    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 12px; color: rgb(119, 119, 119);">
        <p style="margin: 0px 0px 10px; padding: 0px;">
            You received this email to provide information and updates around your Clippsly account.
            <br>
        </p>
        <div class="logo2" style="text-align: center;">
            <img src="https://cdn.clippsly.com/brand_assets/logo2023fullblack.png" alt="Clippsly Mini Logo" style="max-width: 100px;">
            <br>
        </div>
        <p style="margin: 0px 0px 10px; padding: 0px;">
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
            "email" => "support@clippsly.com",
            "name" => "Team Clippsly"
        ],
        "content" => [
            [
                "type" => "text/html",
                "value" => $userEmailBody
            ]
        ]
    ];

    $apiKey = "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"; // Replace with your actual SendGrid API Key
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

// Function to add a new artist to the list
function add_artist($name, $logo, $label, $badges, $banned) {
    $data = read_artist_data();

    $new_artist = array(
        "ID" => generate_unique_id($data),
        "name" => $name,
        "logo" => $logo,
        "label" => $label,
        "badges" => $badges,
        "Banned" => $banned, // Set the "Banned" status
    );

    $data[] = $new_artist;
    write_artist_data($data);

    echo "<script>alert('Artysta został dodany');</script>";
}

// Function to edit an existing artist
function edit_artist($artist_id, $name, $logo, $label, $badges, $banned) {
    $data = read_artist_data();

    foreach ($data as &$artist) {
        if ($artist['ID'] === $artist_id) {
            $old_artist = $artist;
            $artist['name'] = $name;
            $artist['logo'] = $logo;
            $artist['label'] = $label;
            $artist['badges'] = $badges;

            // Check if the "Banned" status changed from false to true
            if (!$old_artist['Banned'] && $banned) {
                send_artist_banned_email($old_artist['email'], $name, $old_artist);
            }

            // Check if the "Banned" status changed from true to false
            if ($old_artist['Banned'] && !$banned) {
                send_artist_reinstated_email($old_artist['email'], $name, $old_artist);
            }

            $artist['Banned'] = $banned; // Update the "Banned" status
            break;
        }
    }

    write_artist_data($data);


    echo "<script>alert('Artysta został zaktualizowany'');</script>";
}


// Function to remove an artist from the list
function remove_artist($artist_id) {
    $data = read_artist_data();

    $removed_artist = null;
    foreach ($data as $index => $artist) {
        if ($artist['ID'] === $artist_id) {
            $removed_artist = $artist;
            unset($data[$index]);
            break;
        }
    }

    write_artist_data(array_values($data));

    echo "<script>alert('Artysta został usunięty');</script>";
}


// Function to assign a badge to an artist
function assign_badge($artist_id, $badge_name) {
    $artist_data = read_artist_data();
    $badges_data = read_badges_data();

    foreach ($artist_data as &$artist) {
        if ($artist['ID'] === $artist_id) {
            if (!isset($artist['badges'])) {
                $artist['badges'] = array();
            }
            if (!in_array($badge_name, $artist['badges'])) {
                $artist['badges'][] = $badge_name;
                break;
            }
        }
    }

    write_artist_data($artist_data);

    echo "<script>alert('Badge assigned to the artist');</script>";
}

// Function to remove a badge from an artist
function remove_badge($artist_id, $badge_name) {
    $artist_data = read_artist_data();

    foreach ($artist_data as &$artist) {
        if ($artist['ID'] === $artist_id) {
            if (isset($artist['badges'])) {
                $index = array_search($badge_name, $artist['badges']);
                if ($index !== false) {
                    unset($artist['badges'][$index]);
                    $artist['badges'] = array_values($artist['badges']);
                }
                break;
            }
        }
    }

    write_artist_data($artist_data);

    echo "<script>alert('Badge removed from the artist');</script>";
}

// Function to read the badges data from badges.json
function read_badges_data() {
    $file_contents = file_get_contents('../badges.json');
    if ($file_contents === false) {
        return array();
    }
    return json_decode($file_contents, true)['badges'];
}

// Function to write the badges data to badges.json
function write_badges_data($data) {
    $updated_data = json_encode(array('badges' => $data), JSON_PRETTY_PRINT);
    file_put_contents('../badges.json', $updated_data);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['artist_submit'])) {
        $name = $_POST['name'];
        $logo = $_POST['logo'];
        $label = $_POST['label'];

        // Convert the selected "Banned" value to a boolean
        $banned = $_POST['banned'] === 'true';

        if (empty($name) || empty($logo) || empty($label)) {
            echo "<script>alert('Please fill in all the required fields');</script>";
        } else {
            $artist_id = $_POST['artist_id'];

            // Get the selected badges from the form submission
            $badges = $_POST['badges'] ?? array();

            if (empty($artist_id)) {
                add_artist($name, $logo, $label, $badges, $banned); // Pass $banned here
            } else {
                edit_artist($artist_id, $name, $logo, $label, $badges, $banned); // Pass $banned here
            }
        }
    } elseif (isset($_POST['edit_submit'])) {
        $artist_id = $_POST['artist_id'];

        // Retrieve the artist details from the artist data
        $artist_data = read_artist_data();
        $artist = array_filter($artist_data, function($a) use ($artist_id) {
            return $a['ID'] === $artist_id;
        });
        
        if (!empty($artist)) {
            $artist = array_values($artist)[0];
            echo "<script>
                document.getElementById('name').value = '{$artist['name']}';
                document.getElementById('logo').value = '{$artist['logo']}';
                document.getElementById('label').value = '{$artist['label']}';
                document.getElementsByName('artist_id')[0].value = '{$artist['ID']}';

                // Convert the selected 'Banned' value to a boolean
                var banned = " . ($artist['Banned'] ? 'true' : 'false') . ";
                document.getElementById('banned').value = banned;
                
                var badges = " . json_encode($artist['badges']) . ";
                var checkboxes = document.getElementsByName('badges[]');
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = (badges.indexOf(checkboxes[i].value) !== -1);
                }
            </script>";
        }
        
    } elseif (isset($_POST['remove_submit'])) {
        $artist_id = $_POST['artist_id'];

        if (!empty($artist_id)) {
            remove_artist($artist_id);
        }
    } elseif (isset($_POST['assign_badge_submit'])) {
        $artist_id = $_POST['artist_id'];
        $badge_name = $_POST['badge_name'];
        assign_badge($artist_id, $badge_name);
    } elseif (isset($_POST['remove_badge_submit'])) {
        $artist_id = $_POST['artist_id'];
        $badge_name = $_POST['badge_name'];
        remove_badge($artist_id, $badge_name);
    }
}

// Read the artist data from artlist.json
$artist_data = read_artist_data();



$delete_permission = $_SESSION['can_delete'];
$edit_permission = $_SESSION['can_edit'];
$create_permission = $_SESSION['can_create'];
$terminate_permission = $_SESSION['can_terminate'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Artist Admin - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container-adm">
        <h1>Artist Admin</h1>
        <!-- Logout link -->
        <br>
        <a href="../logout">Logout</a>

<!-- Artist Form -->
<?php 
    
    if ($create_permission === 0) {
        echo "You don't have permission to create artists.";
        echo "<br>";
    } else {    
        ?>
<h2>Dodaj/edytuj artystę</h2>
<form method="POST" action="">
    <input type="hidden" name="artist_id" value="">
    <label for="name">Nazwa:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="logo">Logo:</label>
    <input type="text" id="logo" name="logo" required><br>

    <label for="label">Label:</label>
    <input type="text" id="label" name="label" required><br>

    <!-- Dropdown menu for "Banned" status -->
    <?php 
    
    if ($terminate_permission === 0) {
        echo "You don't have permission to terminate artists.";
        echo "<br>";
    } else {    
        ?>
    <label for="banned">Banned:</label>
    <select id="banned" name="banned">
        <option value="true">True</option>
        <option value="false">False</option>
    </select><br>
<?php } ?>

    <!-- Badge Selector -->
    <label>Odznaki:</label><br>
    <?php
    $badges_data = read_badges_data();
    foreach ($badges_data as $badge) {
        echo "<input type='checkbox' name='badges[]' value='" . $badge['name'] . "'> " . $badge['name'] . "<br>";
    }
    ?>

    <input type="submit" name="artist_submit" value="Wyślij">
    <?php } ?>
</form>


                <!-- Artist Table -->
                <h2>Lista artystów</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Logo</th>
                    <th>Label</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($artist_data as $artist) {
                    echo "<tr>";
                    echo "<td>" . $artist['ID'] . "</td>";
                    echo "<td>" . $artist['name'] . "</td>";
                    echo "<td><img src='" . $artist['logo'] . "' width='50' height='50' alt='" . $artist['name'] . " Logo'></td>";
                    echo "<td>" . $artist['label'] . "</td>";
                    echo "<td>" . $artist['Banned'] . "</td>";
                    echo "<td>";

                    // Add conditional checks for edit and remove buttons
                    if ($edit_permission === 0) {
                        echo "You do not have permission to edit.";
                        echo "<br>";
                    } else {
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='artist_id' value='" . $artist['ID'] . "'>";
                        echo "<input type='submit' name='edit_submit' value='Edytuj'>";
                    }

                    if ($delete_permission === 0) {
                        echo "You do not have permission to delete.";
                        echo "<br>";
                    }    else {
                        echo "<input type='submit' name='remove_submit' value='Usuń'>";
                    }

                    echo "</form>";
                    
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <img class="sticky-image" src="https://cdn.clippsly.com/brand_assets/PoweredByClippsly.png" alt="Powered By Clippsly">
</body>
</html> 