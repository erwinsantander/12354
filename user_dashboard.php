<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');

  // Check if the user is logged in
  if (!$session->isUserLoggedIn(true)) { 
    redirect('index.php', false);
  }

  if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = ['type' => 'error', 'message' => 'You must log in first.'];
    redirect('login.php');
}

// Check if the logged-in user is a standard user
if ($_SESSION['user_level'] != 3) {
    $_SESSION['msg'] = ['type' => 'error', 'message' => 'Access denied.'];
    redirect('login.php');
}

  // Fetch products from the database
  $products = find_all('products'); // Assuming 'find_all' function retrieves all products from the 'products' table

?>

<?php include_once('layouts/header.php'); ?>
<div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  
  
  
  <!-- Display Products -->
  <div class="col-md-12">
    <h3>Available Products</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Sale Price</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Check if there are products
        if ($products && is_array($products)) {
          foreach ($products as $product) {
            // Fetch associated image from media table (assuming each product has a corresponding image)
            $product_image = find_by_id('media', $product['media_id']); // Adjust according to your DB structure
            $image_url = $product_image ? 'uploads/products/' . $product_image['file_name'] : 'uploads/products/default.jpg'; // Fallback image if no image exists
            
            // Display product with popover for image
            echo "<tr>";
            echo "<td>";
            echo "<a href='#' data-toggle='popover' title='{$product['name']}' data-content='<img src=\"{$image_url}\" class=\"img-fluid\" alt=\"{$product['name']} Image\">'>";
            echo "{$product['name']}";
            echo "</a>";
            echo "</td>";
            echo "<td>₱{$product['sale_price']}</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='2'>No products available</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Bootstrap JS and initialize popovers -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    // Initialize popovers
    $('[data-toggle="popover"]').popover({
      trigger: 'hover', // Trigger popover on hover
      placement: 'top',  // Position the popover above the product name
      html: true         // Allow HTML content in the popover
    });
  });
</script>

<?php include_once('layouts/footer.php'); ?>
