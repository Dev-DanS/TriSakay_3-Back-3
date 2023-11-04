<?php
require_once '../db/dbconn.php'; // Adjust the path to where your Database class is located

$database = Database::getInstance();

$response = array();

// Assuming liveID is passed via POST request
$liveID = isset($_POST['liveID']) ? $_POST['liveID'] : '';

if ($liveID) {
    // Prepare your query
    $sql = "SELECT LiveLat, LiveLng, DateUpdated FROM live WHERE liveID = :liveID";

    // Execute the query and fetch the data
    try {
        $stmt = $database->executeQuery($sql, ['liveID' => $liveID]);
        $data = $database->fetchSingleRow($stmt);

        if ($data) {
            $response['LiveLat'] = $data['LiveLat'];
            $response['LiveLng'] = $data['LiveLng'];
            $response['DateUpdated'] = $data['DateUpdated'];
        }

    } catch (PDOException $e) {
        // Handle exception
        $response['error'] = "An error occurred: " . $e->getMessage();
    }
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>