<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
// Check user permission
page_require_level(3);

// Initialize variables
$msg = [];
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

        $sql = "INSERT INTO sales (product_id, qty, price, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_total}', '{$s_date}')";

        if ($db->query($sql)) {
            update_product_qty($s_qty, $p_id);
            $session->msg('s', "Sale added.");
            redirect('add_sale.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to add!');
            redirect('add_sale.php', false);
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

include_once('layouts/header.php');
?>

<div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
  <div class="col-md-6">
    <form method="post" action="ajax.php" autocomplete="on" id="sug-form">
        
          <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>

<div class="row"style="margin-left: 250px; margin-top: 24px; margin-right: 10px;" >
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th> Item </th>
              <th> Price </th>
              <th> Qty </th>
              <th> Total </th>
              <th> Date</th>
              <th> Action</th>
            </thead>
            <tbody id="product_info">
              <?php while ($sale = $sales_result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                  <td><?php echo htmlspecialchars($sale['price']); ?></td>
                  <td><?php echo htmlspecialchars($sale['qty']); ?></td>
                  <td><?php echo htmlspecialchars($sale['price'] * $sale['qty']); ?></td>
                  <td><?php echo htmlspecialchars($sale['date']); ?></td>
                  <td><a href="buy_sale.php?id=<?php echo htmlspecialchars($sale['id']); ?>" class="btn btn-warning">Buy</a>
                  <!--<a href="delete_sale.php?id=<?php echo htmlspecialchars($sale['id']); ?>" class="btn btn-warning" style="background-color:grey">Delete</a></td>-->
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </form>
      </div>
    </div>
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
