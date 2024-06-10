<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Purchase</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        .purchase-page {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: linear-gradient(to right bottom, #93d1e2, #0072ff);
            color: white;
        }
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }
        input[type="text"], input[type="number"], button[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid black;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        input[type="text"], input[type="number"] {
            background-color: #f0f0f0;
            color: #333;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            background-color: #e0e0e0;
            border-color: #000;
        }
        #totalCost {
            font-weight: bold;
            color: #238E6B;
        }
        button[type="submit"] {
            background-color: #238E6B;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        button[type="submit"]:hover {
            background-color: #1C7B5A;
            transform: translateY(-4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: #fff;
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
    </style>
    <script>
        function fetchItemNames(query) {
            if (query.length < 2) return;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `autocomplete.php?query=${encodeURIComponent(query)}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const dataList = document.getElementById('itemNames');
                    dataList.innerHTML = '';
                    const items = JSON.parse(xhr.responseText);
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.name;
                        dataList.appendChild(option);
                    });
                }
            };
            xhr.send();
        }

        function fetchItemPrice(itemName) {
            if (itemName.length < 2) return;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_price.php?itemName=${encodeURIComponent(itemName)}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.price) {
                        document.getElementById('price').value = response.price;
                        calculateTotalCost();
                    }
                }
            };
            xhr.send();
        }

        function calculateTotalCost() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const totalCost = price * quantity;
            document.getElementById('totalCost').value = totalCost.toFixed(2);
        }

        function submitForm(event) {
            event.preventDefault();
            const form = document.getElementById('purchaseForm');
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_purchase.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const responseMessage = document.getElementById('responseMessage');
                    responseMessage.textContent = response.message;
                    responseMessage.style.display = 'block';
                    if (response.success) {
                        form.reset();
                        document.getElementById('totalCost').value = '';
                    }
                }
            };
            xhr.send(formData);
        }
    </script>
</head>
<body>
<div class="purchase-page">
    <form id="purchaseForm" onsubmit="submitForm(event)">
        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" list="itemNames" onkeyup="fetchItemNames(this.value)" onchange="fetchItemPrice(this.value)" required>
        <datalist id="itemNames"></datalist>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" readonly required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" oninput="calculateTotalCost()" required>

        <label for="totalCost">Total Cost:</label>
        <input type="text" id="totalCost" readonly>

        <button type="submit">Purchase</button>
    </form>

    <a class="back-button" href="main.php"><i class="fas fa-arrow-left"></i></a>

    <div id="responseMessage" style="display: none;"></div>
</div>
</body>
</html>
