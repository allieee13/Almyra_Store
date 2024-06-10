<?php
include 'db_connection.php';

if (isset($_POST['itemId']) && isset($_POST['field']) && isset($_POST['value'])) {
    $itemId = intval($_POST['itemId']);
    $field = $_POST['field'];
    $value = $_POST['value'];

    $allowedFields = ['name', 'price', 'stocks'];
    if (in_array($field, $allowedFields)) {
        
        if ($field == 'price' || $field == 'stocks') {
            $value = floatval($value);
        } else {
            $value = $conn->real_escape_string($value);
        }

        $stmt = $conn->prepare("UPDATE items SET $field = ? WHERE id = ?");
        if ($field == 'price' || $field == 'stocks') {
            $stmt->bind_param("di", $value, $itemId);
        } else {
            $stmt->bind_param("si", $value, $itemId);
        }

        if ($stmt->execute() === TRUE) {
            echo "Item updated successfully";
        } else {
            echo "Error updating item: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();