<?php
header("X-Robots-Tag: noindex, nofollow", true);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $category = $_POST["category"];
    $content = $_POST["content"];

    // Construct email subject
    $emailSubject = "$category ($name and $email)";

    // Construct email body for sending to demo@deafult.com
    $emailBodyToSupport = "Name: $name\nEmail: $email\nCategory: $category\nContent:\n$content";

    // Send email to support using PHP's mail function
    mail("demo@deafult.com", $emailSubject, $emailBodyToSupport);

    // Construct email body for sending to user
    $userEmailBody = '
    <div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: rgb(255, 255, 255); border-radius: 10px">
    <div class="logo" style="text-align: center; margin-bottom: 20px">
        <img src="https://clippsly.com/wp-content/uploads/2023/02/ClippslyBannerNoBG-1.png" alt="Clippsly Logo" style="max-width: 200px">
        <br>
    </div>
    <h1 style="margin: 0; padding: 0; font-size: 24px; margin-bottom: 20px">
        Welcome to Clippsly!
        <br>
    </h1>
    <p style="margin: 0; padding: 0; margin-bottom: 10px">
        Hello ' . htmlspecialchars($name) . ',
        <br>
    </p>
    <p style="margin: 0; padding: 0; margin-bottom: 10px">
        Thank you contacting Clippsly! We will get back to you soon.
        <br>
    </p>
    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 12px; color: rgb(119, 119, 119)">
        <p style="margin: 0; padding: 0; margin-bottom: 10px">
            You received this email to provide information and updates around your Clippsly account.
            <br>
        </p>
        <div class="logo2" style="text-align: center">
            <img src="https://clippsly.com/wp-content/uploads/2023/05/ClippslyBannerNoBG-1-1.png" alt="Clippsly Mini Logo" style="max-width: 100px">
            <br>
        </div>
        <p style="margin: 0; padding: 0; margin-bottom: 10px">
            Clippsly Ltd, Unit A 82 James Carter Road, Mildenhall Industrial Estate, Mildenhall, Suffolk, England, IP28 7DE
            <br>
        </p>
    </div>
</div>
<div>
    <br>
</div>
    ';

    // Send email to user using cURL
    $apiKey = "sendgrid api key";
    $url = "https://api.sendgrid.com/v3/mail/send";

    $headers = array(
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    );

    $userData = array(
        "personalizations" => array(
            array(
                "to" => array(
                    array(
                        "email" => $email
                    )
                ),
                "subject" => "Thank you for contacting us (" . $category . ")"
            )
        ),
        "from" => array(
            "email" => "demo@deafult.com",
            "name" => "Team Clippsly"
        ),
        "content" => array(
            array(
                "type" => "text/html",
                "value" => $userEmailBody
            )
        )
    );

    $userCh = curl_init();
    curl_setopt($userCh, CURLOPT_URL, $url);
    curl_setopt($userCh, CURLOPT_POST, 1);
    curl_setopt($userCh, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($userCh, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($userCh, CURLOPT_RETURNTRANSFER, true);

    $userResponse = curl_exec($userCh);
    curl_close($userCh);

    // Construct the email data for sending to support@clippsly.com
    $supportEmailData = [
        "personalizations" => [
            [
                "to" => [
                    [
                        "email" => "support@clippsly.com"
                    ]
                ],
                "subject" => $category . " (" . $name . " - " . $email . ")"
            ]
        ],
        "from" => [
            "email" => "support@clippsly.com",
            "name" => "Team Clippsly"
        ],
        "content" => [
            [
                "type" => "text/plain",
                "value" => "Name: " . $name . "\n" .
                    "Email: " . $email . "\n" .
                    "Category: " . $category . "\n\n" .
                    "Content:\n" . $content
            ]
        ]
    ];

    // Convert the data to JSON format
    $supportEmailDataJson = json_encode($supportEmailData);

    // Send the support email using cURL and the SendGrid API
    $supportCh = curl_init();
    curl_setopt($supportCh, CURLOPT_URL, $url);
    curl_setopt($supportCh, CURLOPT_POST, 1);
    curl_setopt($supportCh, CURLOPT_POSTFIELDS, $supportEmailDataJson);
    curl_setopt($supportCh, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($supportCh, CURLOPT_RETURNTRANSFER, true);

    $supportResponse = curl_exec($supportCh);
    curl_close($supportCh);

    // Redirect user to a confirmation page
    header("Location: thank-you");
    exit();
}