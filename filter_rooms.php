<?php
require("connection.php");

// Retrieve filter values from GET parameters
$department = isset($_GET['department']) ? $_GET['department'] : '';
$capacity = isset($_GET['capacity']) ? (int) $_GET['capacity'] : 0;
$lab = isset($_GET['lab']) ? (int) $_GET['lab'] : -1; // -1 means no filter
$smartboard = isset($_GET['smartboard']) ? (int) $_GET['smartboard'] : -1; // -1 means no filter

// Build the SQL query dynamically based on filters
$sql = "SELECT * FROM rooms WHERE 1=1";

// Apply filters to the query
if ($department) {
    $sql .= " AND department = :department";
}
if ($capacity > 0) {
    $sql .= " AND capacity >= :capacity";
}
if ($lab != -1) {
    $sql .= " AND lab = :lab";
}
if ($smartboard != -1) {
    $sql .= " AND smartboard = :smartboard";
}

$stmt = $db->prepare($sql);

// Bind parameters if they exist
if ($department) {
    $stmt->bindParam(':department', $department);
}
if ($capacity > 0) {
    $stmt->bindParam(':capacity', $capacity);
}
if ($lab != -1) {
    $stmt->bindParam(':lab', $lab);
}
if ($smartboard != -1) {
    $stmt->bindParam(':smartboard', $smartboard);
}

$stmt->execute();

// Fetch filtered rooms
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as a JSON object
echo json_encode(['rooms' => $rooms]);
?>
