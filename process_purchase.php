<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$itemName = $_POST['itemName'];
$quantity = $_POST['quantity'];

$stmt = $conn->prepare("SELECT stocks FROM items WHERE name=?");
$stmt->bind_param("s", $itemName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentStock = $row['stocks'];

    if ($currentStock >= $quantity) {
        $newStock = $currentStock - $quantity;
        $updateStmt = $conn->prepare("UPDATE items SET stocks=? WHERE name=?");
        $updateStmt->bind_param("is", $newStock, $itemName);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Purchase successful. New stock for $itemName: $newStock"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
        }
        $updateStmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => "Not enough stock for $itemName. Current stock: $currentStock"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found.']);
}

$stmt->close();
$conn->close();
