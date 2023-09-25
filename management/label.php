<?php
session_start();
header("X-Robots-Tag: noindex, nofollow", true);

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../admin");
    exit;
}

// Function to generate a unique ID for a new label
function generate_unique_id($data) {
    $ids = array_column($data['labels'], "ID");
    $new_id = max($ids) + 1;
    return str_pad($new_id, 3, "0", STR_PAD_LEFT);
}

// Function to read the label data from labels.json
function read_label_data() {
    $file_contents = file_get_contents('../labels.json');
    if ($file_contents === false) {
        return array();
    }
    return json_decode($file_contents, true);
}

// Function to write the label data to labels.json
function write_label_data($data) {
    $updated_data = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('../labels.json', $updated_data);
}


// Function to add a new label to the list
function add_label($name, $logo) {
    $data = read_label_data();

    $new_label = array(
        "ID" => generate_unique_id($data),
        "name" => $name,
        "logo" => $logo,
    );

    $data['labels'][] = $new_label;
    write_label_data($data);

    echo "<script>alert('Label został dodany');</script>";
}

// Function to edit an existing label
function edit_label($label_id, $name, $logo) {
    $data = read_label_data();

    $updated_label = null;
    foreach ($data['labels'] as &$label) {
        if ($label['ID'] === $label_id) {
            $label['name'] = $name;
            $label['logo'] = $logo;
            $updated_label = $label;
            break;
        }
    }

    write_label_data($data);

    echo "<script>alert('Label został zaktualizowany');</script>";
}

// Function to remove a label from the list
function remove_label($label_id) {
    $data = read_label_data();

    $removed_label = null;
    foreach ($data['labels'] as $index => $label) {
        if ($label['ID'] === $label_id) {
            $removed_label = $label;
            unset($data['labels'][$index]);
            break;
        }
    }

    write_label_data($data);

        echo "<script>alert('Label został usunięty');</script>";
}



// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['label_submit'])) {
        $name = $_POST['name'];
        $logo = $_POST['logo'];

        if (empty($name) || empty($logo)) {
            echo "<script>alert('Please fill in all the required fields');</script>";
        } else {
            $label_id = $_POST['label_id'];

            if (empty($label_id)) {
                add_label($name, $logo);
            } else {
                edit_label($label_id, $name, $logo);
            }
        }
    } elseif (isset($_POST['edit_submit'])) {
        $label_id = $_POST['label_id'];

        // Retrieve the label details from the label data
        $label_data = read_label_data();
        $label = array_filter($label_data['labels'], function($l) use ($label_id) {
            return $l['ID'] === $label_id;
        });

        // Populate the form with the label details
        if (!empty($label)) {
            $label = array_values($label)[0];
            echo "<script>
                document.getElementById('name').value = '{$label['name']}';
                document.getElementById('logo').value = '{$label['logo']}';
                document.getElementsByName('label_id')[0].value = '{$label['ID']}';
            </script>";
        }
    } elseif (isset($_POST['remove_submit'])) {
        $label_id = $_POST['label_id'];

        if (!empty($label_id)) {
            remove_label($label_id);
        }
    }
}

// Read the label data from labels.json
$label_data = read_label_data();

$delete_permission = $_SESSION['can_delete'];
$edit_permission = $_SESSION['can_edit'];
$create_permission = $_SESSION['can_create'];
$terminate_permission = $_SESSION['can_terminate'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Label Admin - Demo</title>
    <link rel="icon" type="image/x-icon" href="https://clippsly.com/wp-content/uploads/2023/06/clippsly-favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container-adm">
        <h1>Label Admin</h1>
        <!-- Logout link -->
        <br>
        <a href="../logout">Wyloguj</a>
        <?php 
    
    if ($create_permission === 0) {
        echo "You don't have permission to create labels.";
        echo "<br>";
    } else {    
        ?>
        <!-- Label Form -->
        <h2>Dodaj/edytuj label</h2>
        <form method="POST" action="">
            <input type="hidden" name="label_id" value="">
            <label for="name">Nazwa:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="logo">Logo:</label>
            <input type="text" id="logo" name="logo" required><br>

            <input type="submit" name="label_submit" value="Wyślij">
        </form>
        <?php } ?>

        <!-- Label Table -->
        <h2>Lista labeli</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Logo</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($label_data['labels'] as $label) {
                    echo "<tr>";
                    echo "<td>" . $label['ID'] . "</td>";
                    echo "<td>" . $label['name'] . "</td>";
                    echo "<td><img src='" . $label['logo'] . "' width='50' height='50' alt='" . $label['name'] . " Logo'></td>";
                    echo "<td>";

                    // Add conditional checks for edit and remove buttons
                    if ($edit_permission === 0) {
                        echo "You do not have permission to edit.";
                        echo "<br>";
                    } else {
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='label_id' value='" . $label['ID'] . "'>";
                        echo "<input type='submit' name='edit_submit' value='Edytuj'>";
                    }
                    
                    if ($delete_permission === 0) {
                        echo "You do not have permission to delete.";
                        echo "<br>";
                    }    else {
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='label_id' value='" . $label['ID'] . "'>";
                        echo "<input type='submit' name='remove_submit' value='Usuń'>";
                    }

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
