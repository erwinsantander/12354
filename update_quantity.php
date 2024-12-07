<?php
require_once('includes/load.php');
page_require_level(2);

if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $additional_quantity = (int)$_POST['quantity'];

   
    if($additional_quantity < 0) {
        $session->msg('d', 'Quantity cannot be negative.');
        redirect('product.php', false);
    }

  
    $product = find_by_id('products', $product_id);
    if(!$product) {
        $session->msg('d', 'Product not found.');
        redirect('product.php', false);
    }

    
    $current_quantity = (int)$product['quantity'];
    $new_quantity = $current_quantity + $additional_quantity;

   
    $sql = "UPDATE products SET quantity = '{$new_quantity}' WHERE id = '{$product_id}'";
    if($db->query($sql)) {
        $session->msg('s', 'Product quantity updated successfully.');
    } else {
        $session->msg('d', 'Failed to update product quantity.');
    }
    redirect('product.php', false);
} else {
    $session->msg('d', 'Invalid request.');
    redirect('product.php', false);
}
?>
