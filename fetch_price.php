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

$itemName = isset($_GET['itemName']) ? $_GET['itemName'] : '';

$stmt = $conn->prepare("SELECT price FROM items WHERE name=?");
$stmt->bind_param("s", $itemName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['price' => $row['price']]);
} else {
    echo json_encode(['price' => null]);
}

$stmt->close();
$conn->close();