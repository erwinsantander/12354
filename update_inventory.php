<?php
require_once('includes/load.php');
page_require_level(2);

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$cart = $input['cart'];
$total = $input['total'];
$payment = $input['payment'];
$change = $input['change'];

try {
    // Begin a database transaction
    $db->begin_transaction();

    foreach ($cart as $item) {
        $barcode = $item['barcode'];
        $quantity = $item['quantity'];

        // Fetch current product details
        $product = find_by_barcode($barcode);

        if (!$product) {
            throw new Exception("Product not found: {$barcode}");
        }

        // Calculate new quantity
        $new_quantity = $product['quantity'] - $quantity;

        if ($new_quantity < 0) {
            throw new Exception("Insufficient stock for product: {$product['name']}");
        }

        // Update product quantity
        $query = "UPDATE products SET quantity = {$new_quantity} WHERE barcode = '{$barcode}'";
        $result = $db->query($query);

        if (!$result) {
            throw new Exception("Failed to update inventory for product: {$product['name']}");
        }

        // Log the sale (optional)
        $sale_data = [
            'product_id' => $product['id'],
            'qty' => $quantity,
            'price' => $item['price'],
            'total' => $item['total'],
            'date' => date('Y-m-d H:i:s')
        ];
        $db->insert('sales', $sale_data);
    }

    // Commit the transaction
    $db->commit();

    echo json_encode(['success' => true, 'message' => 'Inventory updated successfully']);
} catch (Exception $e) {
    // Rollback the transaction in case of any error
    $db->rollback();

    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}