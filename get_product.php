<?php
// Database connection configuration
$host = 'localhost';
$dbname = 'inventory_system';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get barcode from GET request
    $barcode = $_GET['barcode'] ?? '';

    // Prepare SQL statement to fetch product with quantity check
    $stmt = $pdo->prepare("SELECT barcode, name, sale_price, quantity 
                            FROM products 
                            WHERE barcode = :barcode AND quantity > 0 
                            LIMIT 1");
    $stmt->execute(['barcode' => $barcode]);

    // Fetch the product
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Send JSON response
    header('Content-Type: application/json');
    
    if ($product) {
        echo json_encode($product);
    } else {
        // Product not found or out of stock
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Product not found or out of stock']);
    }

} catch(PDOException $e) {
    // Handle database errors
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>