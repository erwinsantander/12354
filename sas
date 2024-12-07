<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>

<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price','expiration-date', 'product-barcode');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     $p_expiration = remove_junk($db->escape($_POST['expiration-date']));
     $p_barcode = remove_junk($db->escape($_POST['product-barcode']));
     
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }

     // Check if the category already exists in the products table
     $category_check_query = "SELECT * FROM products WHERE categorie_id = '{$p_cat}' LIMIT 1";
     $category_check = $db->query($category_check_query);

     if ($category_check->num_rows > 0) {
       $session->msg("d", "This category is already assigned to another product. Please choose a different one.");
       redirect('add_product.php', false);
     } else {
       // Category is not duplicated, proceed with adding the product
       $date    = make_date();
       $query  = "INSERT INTO products (";
       $query .=" name,quantity,buy_price,sale_price,categorie_id,media_id,date,expiration_date,barcode";
       $query .=") VALUES (";
       $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', '{$p_expiration}', '{$p_barcode}'";
       $query .=")";

       if($db->query($query)){
         $session->msg('s',"Product added ");
         redirect('add_product.php', false);
       } else {
         $session->msg('d',' Sorry failed to add!');
         redirect('product.php', false);
       }
     }
   } else {
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Product</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" id="add-product-form" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                 <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title">
             </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Product Category</option>
                  <?php  foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id'] ?>">
                      <?php echo $cat['name'] ?></option>
                  <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control" name="product-photo">
                    <option value="">Select Product Photo</option>
                  <?php  foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int)$photo['id'] ?>">
                      <?php echo $photo['file_name'] ?></option>
                  <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
             <div class="row">
               <div class="col-md-4">
                 <div class="input-group">
                   <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                   </span>
                   <input type="text" class="form-control" name="product-quantity" id="product-quantity" placeholder="Product Quantity">
                </div>
               </div>
               <div class="col-md-4">
                 <div class="input-group">
                   <span class="input-group-addon">
                   <i class="fas fa-money-bill-alt"></i>
                   </span>
                   <input type="text" class="form-control" name="buying-price" id="buying-price" placeholder="Buying Price">
                   <span class="input-group-addon">.00</span>
                </div>
               </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                    <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <input type="text" class="form-control" name="saleing-price" id="saleing-price" placeholder="Selling Price">
                    <span class="input-group-addon">.00</span>
                 </div>
                 <br>
                </div>
                <div class="col-md-4">
                <span><label for="expiration-date">Expiration Date</label></span>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="date" class="form-control" id="expiration-date" name="expiration-date" placeholder="Expiration Date">
              </div>
            </div>
            
            <div class="col-md-4">
            <span><label for="expiration-date">Barcode</label></span>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-barcode"></i>
                </span>
                <input type="text" class="form-control" name="product-barcode" id="product-barcode" placeholder="Product Barcode">
              </div>
            </div>

            </div>
            <br>
            <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Prevent form submission on Enter key for barcode input
      document.getElementById('product-barcode').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
          event.preventDefault(); // Stop the default form submission
          return false;
        }
      });

      // Prevent form submission on Enter key for all inputs
      var form = document.getElementById('add-product-form');
      var inputs = form.querySelectorAll('input, select');
      
      inputs.forEach(function(input) {
        input.addEventListener('keydown', function(event) {
          if (event.key === 'Enter') {
            event.preventDefault();
            return false;
          }
        });
      });

      document.getElementById('expiration-date').addEventListener('change', function() {
        var selectedDate = new Date(this.value);
        var today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Please select a future date for expiration.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              this.value = '';
            }
          });
        }
      });

      function restrictToNumbers(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point
        var parts = input.value.split('.');
        if (parts.length > 2) {
          parts.pop();
          input.value = parts.join('.');
        }
      }

      document.getElementById('product-quantity').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
      });

      document.getElementById('buying-price').addEventListener('input', function() {
        restrictToNumbers(this);
      });

      document.getElementById('saleing-price').addEventListener('input', function() {
        restrictToNumbers(this);
      });

      document.getElementById('product-barcode').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
      });
    });
  </script>
  <?php if ($msg): ?>
<script>
    Swal.fire({
        icon: '<?php echo $msg['type']; ?>',
        title: '<?php echo $msg['message']; ?>',
        position: 'center',
        showConfirmButton: true
    });
</script>
<?php endif; ?>

<?php include_once('layouts/footer.php'); ?>