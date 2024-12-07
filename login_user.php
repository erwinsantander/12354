<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn()) { redirect('home.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<body>
    
<div class="login-page" style="
    border-radius:10%; 
    margin-top: 130px; 
    border-block-color: unset; 
    background-color: #fff; /* Adjust this to your preferred background color */
    box-shadow: 0 0 15px rgb(255 6 5); /* Glowing effect: adjust color and spread as needed */
    padding: 20px; /* Add padding to ensure content doesn't touch the edges */
">
    <div class="text-center">
       <h1>Login Panel</h1>
       <h4>Inventory Management System</h4>
     </div>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-danger" style="border-radius:10%">Login</button>
        </div>
    </form>
</div>

<style>
    body{
    background-image: url('/libs/images/logo1.png');
    background-size: cover;
    background-repeat: no-repeat;
    }
    
</style>
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
</body>