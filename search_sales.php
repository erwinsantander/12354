<?php
require_once('includes/load.php');

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';

    // Escape user input
    $query = $db->escape($query);

    // SQL query to search for sales based on product name
    $sales_query = "
        SELECT sales.id, sales.product_id, sales.price, sales.qty, sales.date, name AS product_name
        FROM sales
        JOIN products ON sales.product_id = products.id
        WHERE products.name LIKE '%{$query}%'
    ";
    $sales_result = $db->query($sales_query);

    $sales = [];
    while ($row = $sales_result->fetch_assoc()) {
        $sales[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($sales);
}
