<?php
include 'db_connection.php';

if (isset($_POST['itemId'])) {
    $itemId = intval($_POST['itemId']);

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute() === TRUE) {
        echo "Item deleted successfully";
    } else {
        echo "Error deleting item: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
