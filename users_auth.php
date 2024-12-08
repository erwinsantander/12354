<?php 
include_once('includes/load.php');

$req_fields = array('username', 'password');
validate_fields($req_fields);

$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    $user_id = authenticate($username, $password);
    if ($user_id) {
        $session->login($user_id);
        updateLastLogIn($user_id);
        
        // Success message using SweetAlert
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome to Inventory Management System',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'admin.php';
            });
        });
        </script>";
        exit();
        
    } else {
        // Incorrect password message using SweetAlert2
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'Incorrect username or password',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php';
                }
            });
        });
        </script>";
        exit();
    }
} else {
    $session->msg("d", $errors);
    redirect('index.php', false);
}
?>