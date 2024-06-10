<?php
ob_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addItem'])) {
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemStocks = $_POST['itemStocks'];

        $stmt = $conn->prepare("INSERT INTO items (name, price, stocks) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $itemName, $itemPrice, $itemStocks);

        if ($stmt->execute() === TRUE) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Monitoring System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            display: flex;
            min-height: 100vh;
            justify-content: space-between;
            padding: 20px;
        }
        .form-container {
            width: 30%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            
        }
        .form-container label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input{
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 4px;
            width: 95%;
            border: 1px solid #ccc;
            
        }
        .form-container button {
            background-color: #238E6B;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
            width: 99%;
        }
        .table-container {
            width: 70%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #238E6B;
            color: #fff;
        }
        .action-links {
            cursor: pointer;
            color: #0072ff;
        }
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: #333;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .back-button:hover {
            color: #238E6B;
        }
        #responseMessage {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }
        #searchForm {
            display: flex;
            margin-bottom: 20px;
        }

        #searchBar {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

    </style>
</head>
<body>
    <header>
        <h1>Stock Monitoring System</h1>
    </header>

    <div class="container">
        <div class="form-container">
            <form id="addForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="itemName">Item Name:</label>
                <input type="text" id="itemName" name="itemName" required>
                
                <label for="itemPrice">Price:</label>
                <input type="number" id="itemPrice" name="itemPrice" step="0.01" required>
                
                <label for="itemStocks">Stocks:</label>
                <input type="number" id="itemStocks" name="itemStocks" required>
                
                <button type="submit" name="addItem">Add Item <i class="fas fa-plus"></i></button>
            </form>
        </div>

        <div class="table-container">
            <form id="searchForm">
                <input type="text" id="searchBar" placeholder="Search items..." oninput="searchItems(this.value)">
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Stocks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemTableBody">
                    <?php
                    $sql = "SELECT * FROM items";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['price']}</td>
                                    <td>{$row['stocks']}</td>
                                    <td>
                                        <a class='action-links' href='#' onclick='editItem({$row['id']}, \"name\")'><i class='fas fa-edit'></i></a> |
                                        <a class='action-links' href='#' onclick='editItem({$row['id']}, \"price\")'><i class='fas fa-dollar-sign'></i></a> |
                                        <a class='action-links' href='#' onclick='editItem({$row['id']}, \"stocks\")'><i class='fas fa-boxes'></i></a> |
                                        <a class='action-links' href='#' onclick='confirmDelete({$row['id']})'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No results found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <p id="noResults" style="display: none;">No results found</p>

    <a class="back-button" href="main.php"><i class="fas fa-arrow-left"></i></a>

    <script>
        function editItem(itemId, field) {
            var newValue = prompt("Enter new value for " + field);
            if (newValue !== null) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "edit_item.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Error updating item: " + xhr.statusText);
                        }
                    }
                };
                xhr.send("itemId=" + itemId + "&field=" + field + "&value=" + encodeURIComponent(newValue));
            }
        }

        function confirmDelete(itemId) {
            var confirmDelete = confirm("Are you sure you want to delete this item?");
            if (confirmDelete) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_item.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Error deleting item: " + xhr.statusText);
                        }
                    }
                };
                xhr.send("itemId=" + itemId);
            }
        }

        function searchItems(query) {
            var tableBody = document.getElementById("itemTableBody");
            var rows = tableBody.getElementsByTagName("tr");
            var noResults = document.getElementById("noResults");

            var found = false;

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var itemName = cells[1].innerText.toLowerCase();
                if (itemName.includes(query.toLowerCase())) {
                    rows[i].style.display = "";
                    found = true;
                } else {
                    rows[i].style.display = "none";
                }
            }

            noResults.style.display = found ? "none" : "";
        }
    </script>
</body>
</html>
