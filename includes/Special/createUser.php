<?php
// Set header to JSON for the response
header("Content-Type: application/json");

use MediaWiki\MediaWikiServices;

// Get the JSON input from the request body
$input = file_get_contents("php://input");

// Decode JSON input to an associative array
$data = json_decode($input, true);

if ($data) {
    // read the data
    $pubkey = $data['npub'];
    $signature = $data['signature'];


    $username = $pubkey;
    // TODO: generate a random password
    $password = "password";
    
    
    $userFactory = MediaWikiServices::getInstance()->getUserFactory();
    $user = $userFactory->newFromName($username);

    // from includes/installer/Installer.php
    if ($user->getId() == 0) {
        $user->addToDatabase();
        $status = $user->changeAuthenticationData([
            'username' => $user->getName(),
            'password' => $password,
            'retype' => $password,
        ]);
        $user->saveSettings();

        if ($status->isGood()) {
            $status = "Account created successfully!";
        } else {
            $status = "Error creating account: " . $status->getWikiText();
        }
    } else {
        $status = "Username already exists. Please choose another one.";
    }


    // Respond with a JSON message
    echo json_encode([
        "status" => "success",
        "message" => "Data received",
        "receivedData" => [
            "npub" => $pubkey,
            "signature" => $signature
        ]
    ]);
} else {
    // Error response if no data is received
    echo json_encode([
        "status" => "error",
        "message" => "No data received"
    ]);
}
?>