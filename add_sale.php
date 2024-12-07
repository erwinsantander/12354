<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Check user permission
page_require_level(3);

// Initialize variables
$msg = [];
$errors = []; // Initialize errors array for validation
$products = [];

// Handle the sale addition
if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'quantity', 'price', 'total', 'date');
    validate_fields($req_fields);
    
    if (empty($errors)) {
        $p_id = $db->escape((int)$_POST['s_id']);
        $s_qty = $db->escape((int)$_POST['quantity']);
        $s_total = $db->escape($_POST['total']);
        $date = $db->escape($_POST['date']);
        $s_date = make_date();

        // Check if the same product is already sold on the same date
        $check_sale_query = "SELECT * FROM sales WHERE product_id = '{$p_id}' AND date = '{$date}' LIMIT 1";
        $check_sale_result = $db->query($check_sale_query);
        
        if ($check_sale_result->num_rows > 0) {
            // Product already sold on the same date, show an error
            $session->msg('d', 'This product has already been sold on the selected date.');
            redirect('add_sale.php', false);
        } else {
            // Check if there is enough stock for the product
            $product_query = "SELECT quantity FROM products WHERE id = '{$p_id}' LIMIT 1";
            $product_result = $db->query($product_query);
            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                if ($s_qty > $product['quantity']) {
                    // Insufficient stock
                    $session->msg('d', 'Not enough stock available.');
                    redirect('add_sale.php', false);
                } else {
                    // Proceed with adding the sale
                    $sql = "INSERT INTO sales (product_id, qty, price, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_total}', '{$s_date}')";

                    if ($db->query($sql)) {
                        update_product_qty($s_qty, $p_id); // Update the stock
                        $session->msg('s', "Sale added.");
                        redirect('add_sale.php', false);
                    } else {
                        $session->msg('d', 'Sorry, failed to add!');
                        redirect('add_sale.php', false);
                    }
                }
            }
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}

// Fetch sales for display with product names
$sales_query = "
    SELECT sales.id, sales.product_id, sales.price, sales.qty, sales.date, name AS product_name
    FROM sales
    JOIN products ON sales.product_id = products.id
";
$sales_result = $db->query($sales_query);

// Fetch products for the drop-down menu
$products_query = "SELECT id, name FROM products";
$products_result = $db->query($products_query);

include_once('layouts/header.php');
?>

<div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
  <div class="col-md-6">
    <!-- Sale addition form -->
    <form method="post" action="add_sale.php">
        <div class="form-group">
            <label for="s_id">Product:</label>
            <select class="form-control" name="s_id" id="s_id" required>
                <option value="">Select Product</option>
                <?php while ($product = $products_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" name="quantity" id="quantity" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" class="form-control" name="price" id="price" required>
        </div>
        <div class="form-group">
            <label for="total">Total:</label>
            <input type="text" class="form-control" name="total" id="total" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" id="date" required>
        </div>
        <button type="submit" name="add_sale" class="btn btn-primary">Add Sale</button>
    </form>
  </div>
</div>

<?php if (isset($msg)): ?>
<script>
    Swal.fire({
        icon: '<?php echo htmlspecialchars($msg['type']); ?>',
        title: '<?php echo htmlspecialchars($msg['message']); ?>',
        position: 'center',
        showConfirmButton: true
    });
</script>
<?php endif; ?>

<?php include_once('layouts/footer.php'); ?>
