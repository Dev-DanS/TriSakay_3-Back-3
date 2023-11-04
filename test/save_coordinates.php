<?php

require '../db/dbconn.php';

$liveID = 5; // Hardcoded as per your requirement
$latitude = isset($_POST['latitude']) ? (float) $_POST['latitude'] : null;
$longitude = isset($_POST['longitude']) ? (float) $_POST['longitude'] : null;

if ($latitude !== null && $longitude !== null) {
    $database = Database::getInstance();

    // The SQL query with placeholders for the values
    $sql = "UPDATE live SET LiveLat = :LiveLat, LiveLng = :LiveLng, DateUpdated = NOW() WHERE liveID = :liveID";

    // Preparing the query
    $stmt = $database->executeQuery($sql, [
        ':LiveLat' => $latitude,
        ':LiveLng' => $longitude,
        ':liveID' => $liveID
    ]);

    if ($stmt) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to update coordinates']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing coordinates']);
}
