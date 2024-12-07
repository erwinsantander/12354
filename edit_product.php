<?php
  $page_title = 'Edit product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price','expiration-date'  );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = (int)$_POST['product-categorie'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_buy   = remove_junk($db->escape($_POST['buying-price']));
       $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       $p_expiration = remove_junk($db->escape($_POST['expiration-date']));
       $current_date = date('Y-m-d');
       if ($p_expiration <= $current_date) {
           $errors = "Expiration date must be in the future.";
           $session->msg('d', $errors);
           redirect('edit_product.php?id='.$product['id'], false);
       }
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
       $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',media_id='{$media_id}',expiration_date='{$p_expiration}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
  
</div>
  <div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value=""> Select a categorie</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value=""> No image</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>





              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="text" class="form-control" name="product-quantity" id="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Buying price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                      <i class="fas fa-money-bill-alt"></i>
                      </span>
                      <input type="text" class="form-control" name="buying-price" id="buying-price" value="<?php echo remove_junk($product['buy_price']);?>">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
                 </div>
                  <div class="col-md-4">
                   <div class="form-group">
                     <label for="qty">Selling price</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                       <i class="fas fa-money-bill-alt"></i>
                       </span>
                       <input type="text" class="form-control" name="saleing-price" id="selling-price" value="<?php echo remove_junk($product['sale_price']);?>">
                       <span class="input-group-addon">.00</span>
                    </div>
                   </div>
                  </div>
                  <div class="col-md-4">
                    <span><label for="expiration-date">Expiration Date</label></span>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="date" class="form-control" id="expiration-date" name="expiration-date" value="<?php echo remove_junk($product['expiration_date']); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
               </div>
              </div>
              <button type="submit" name="product" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>
  <script>
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

    document.getElementById('selling-price').addEventListener('input', function() {
        restrictToNumbers(this);
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
