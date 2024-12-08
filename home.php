<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn()) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
 
</div>
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