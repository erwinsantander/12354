<?php
require_once('includes/load.php');

if (isset($_GET['product_id'])) {
    $product_id = $db->escape((int)$_GET['product_id']);
    
    $query = "SELECT price FROM products WHERE id = '{$product_id}' LIMIT 1";
    $result = $db->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode(['price' => $row['price']]);
    } else {
        echo json_encode(['price' => 0]);
    }
}
?>
