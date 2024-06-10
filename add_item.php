<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    if (isset($_POST['addItem'])) {
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemStocks = $_POST['itemStocks'];

        $stmt = $conn->prepare("INSERT INTO items (name, price, stocks) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $itemName, $itemPrice, $itemStocks);

        if ($stmt->execute() === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
