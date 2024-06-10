<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$stmt = $conn->prepare("SELECT name FROM items WHERE name LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

$itemNames = [];
while ($row = $result->fetch_assoc()) {
    $itemNames[] = ['name' => $row['name']];
}

echo json_encode($itemNames);

$stmt->close();
$conn->close();
