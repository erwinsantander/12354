<?php
header('Content-Type: application/json');

// Database connection configuration
$host = 'localhost';
$dbname = 'inventory_system';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $pdo->beginTransaction();

    // Get cart data from POST request
    $jsonData = file_get_contents('php://input');
    $cart = json_decode($jsonData, true);

    if (empty($cart)) {
        throw new Exception('Empty cart data');
    }

    // Prepare SQL statements
    $checkStmt = $pdo->prepare("SELECT quantity FROM products WHERE barcode = :barcode");
    $updateStmt = $pdo->prepare("UPDATE products SET quantity = quantity - :qty WHERE barcode = :barcode AND quantity >= :qty");

    $successUpdates = [];
    $failedUpdates = [];

    // Process each cart item
    foreach ($cart as $item) {
        $barcode = $item['barcode'];
        $quantity = $item['quantity'];

        // Check current stock first
        $checkStmt->execute([':barcode' => $barcode]);
        $currentStock = $checkStmt->fetchColumn();

        if ($currentStock === false || $currentStock < $quantity) {
            $failedUpdates[] = [
                'barcode' => $barcode,
                'reason' => 'Insufficient stock',
                'current_stock' => $currentStock,
                'requested_quantity' => $quantity
            ];
            continue;
        }

        // Execute update for specific quantity
        $updateStmt->execute([
            ':qty' => $quantity,
            ':barcode' => $barcode
        ]);

        if ($updateStmt->rowCount() > 0) {
            $successUpdates[] = [
                'barcode' => $barcode,
                'quantity_reduced' => $quantity,
                'previous_stock' => $currentStock,
                'new_stock' => $currentStock - $quantity
            ];
        } else {
            $failedUpdates[] = [
                'barcode' => $barcode,
                'reason' => 'Update failed',
                'quantity' => $quantity
            ];
        }
    }

    // Commit transaction if all updates successful
    if (empty($failedUpdates)) {
        $pdo->commit();
    } else {
        $pdo->rollBack();
    }

    // Prepare response
    $response = [
        'success' => empty($failedUpdates),
        'successfulUpdates' => $successUpdates,
        'failedUpdates' => $failedUpdates
    ];

    echo json_encode($response);

} catch(Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Handle errors
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>