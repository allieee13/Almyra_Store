<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Main Page</title>
<style>
  body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f2f2f2; 
    color: #333;
  }

    body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f2f2f2; 
    background-image: linear-gradient(to bottom right, #007bff, #00bcd4); /* Gradient background */
  }

  .container {
    text-align: center;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  }

  .container h2 {
    margin-bottom: 20px;
    font-size: 2rem;
    color: #007bff; 
    text-transform: uppercase;
    font-family: verdana 
  }

  .buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
  button {
    padding: 12px 24px;
    margin: 10px;
    border: none;
    border-radius: 30px;
    background-color: #007bff; 
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
  }
  button:hover {
    background-color: #0056b3; 
    transform: translateY(-4px); 
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); 
  }
</style>
</head>
<body>

<div class="container">
  <h2>Welcome to Almyra Store</h2>
  <div class="buttons">
    <!-- Button 1: Purchase -->
    <form action="purchase.php">
      <button type="submit">Purchase</button>
    </form>

    <!-- Button 2: Admin -->
    <form action="index.php">
      <button type="submit">Manage Item</button>
    </form>
  </div>
</div>

</body>
</html>