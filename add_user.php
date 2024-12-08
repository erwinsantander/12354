<?php
  $page_title = 'Add User';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $groups = find_all('user_groups');
?>
<?php
  if(isset($_POST['add_user'])){
   
    $req_fields = array('full-name','username','password','level', 'email'); // Add email to required fields
    validate_fields($req_fields);

    // Validate the email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address.';
    }

    if(empty($errors)){
      $name       = remove_junk($db->escape($_POST['full-name']));
      $username   = remove_junk($db->escape($_POST['username']));
      $password   = remove_junk($db->escape($_POST['password']));
      $email      = remove_junk($db->escape($_POST['email'])); // Get email
      $user_level = (int)$db->escape($_POST['level']);
      $password   = sha1($password);

      $query = "INSERT INTO users (";
      $query .= "name, username, password, user_level, email, status";
      $query .= ") VALUES (";
      $query .= " '{$name}', '{$username}', '{$password}', '{$user_level}', '{$email}', '1'";
      $query .= ")";

      if($db->query($query)){
        // Success
        $session->msg('s',"User account has been created!");
        redirect('add_user.php', false);
      } else {
        // Failed
        $session->msg('d','Sorry, failed to create account!');
        redirect('add_user.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_user.php', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row" style="margin-left: 250px; margin-top: 24px; margin-right: 10px;">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New User</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="full-name" placeholder="Full Name">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="email">Email</label> <!-- Added Email Field -->
                <input type="email" class="form-control" name="email" placeholder="Email Address">
            </div>
            <div class="form-group">
              <label for="level">User Role</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </div>
        </form>
        </div>

      </div>

    </div>
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
