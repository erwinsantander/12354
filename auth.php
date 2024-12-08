<?php
include_once('includes/load.php');

// Your secret key
$secret_key = '6LecM5UqAAAAAFhygg3kZDc55NREG8iGR_dktKl9';

// Verify the reCAPTCHA response
/*
$recaptcha_token = $_POST['recaptcha_token'];
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_token}");
$response_keys = json_decode($response, true);

if (intval($response_keys["success"]) !== 1) {
    $_SESSION['message'] = json_encode([
        'type' => 'error',
        'text' => 'reCAPTCHA verification failed. Please try again.'
    ]);
    header('Location: index.php');
    exit();
}
*/
// Initialize session variables for login attempts and lockout time if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

$lockout_duration = 3 * 60; // Lockout duration in seconds (3 minutes)

// Check if the user is currently locked out
if (time() < $_SESSION['lockout_time']) {
    $remaining_time = $_SESSION['lockout_time'] - time();
    $_SESSION['message'] = json_encode([
        'type' => 'error',
        'text' => 'Too many failed login attempts. Please try again in ' . ceil($remaining_time / 60) . ' minutes.'
    ]);
    header('Location: index.php');
    exit();
}

// Function to authenticate user with bcrypt and backward compatibility
function authenticate($username_or_email='', $password='') {
    global $db;
    
    $username_or_email = $db->escape($username_or_email);
    $password = $db->escape($password);
    
    $sql = sprintf("SELECT id, username, password, user_level, status FROM users WHERE (username='%s' OR email='%s') LIMIT 1", 
                   $username_or_email, 
                   $username_or_email);
    
    $result = $db->query($sql);
    
    if($db->num_rows($result)){
        $user = $db->fetch_assoc($result);
        
        if ($user['status'] != 1) {
            $_SESSION['message'] = json_encode([
                'type' => 'error',
                'text' => 'Your account is inactive. Please wait for admin approval.'
            ]);
            return false;
        }
        
        if (password_verify($password, $user['password']) || 
            sha1($password) === $user['password']) {
            if (sha1($password) === $user['password']) {
                $new_hash = password_hash($password, PASSWORD_BCRYPT);
                $update_sql = sprintf("UPDATE users SET password='%s' WHERE id=%d", 
                                      $db->escape($new_hash), 
                                      $user['id']);
                $db->query($update_sql);
            }
            return ['id' => $user['id'], 'user_level' => $user['user_level']];
        }
    }
   
    return false;
}

$req_fields = array('password');
validate_fields($req_fields);

$username_or_email = remove_junk($_POST['username']) ?: remove_junk($_POST['email']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    $user_data = authenticate($username_or_email, $password);

    if ($user_data) {
        $_SESSION['login_attempts'] = 0;
        
        $session->login($user_data['id']);
        updateLastLogIn($user_data['id']);

        $_SESSION['message'] = json_encode([
            'type' => 'success',
            'text' => 'Login successful!'
        ]);

        if ($user_data['user_level'] == 1) {
            header("Location: admin.php");
        } else if ($user_data['user_level'] == 2) {
            header("Location: cashier.php");
        }
        exit();
    } else {
        $sql = sprintf("SELECT status FROM users WHERE (username='%s' OR email='%s') LIMIT 1", 
                       $username_or_email, 
                       $username_or_email);
        $result = $db->query($sql);
        if ($db->num_rows($result)) {
            $user = $db->fetch_assoc($result);
            if ($user['status'] != 1) {
                $_SESSION['message'] = json_encode([
                    'type' => 'error',
                    'text' => 'Your account is inactive. Please wait for admin approval.'
                ]);
                header('Location: index.php');
                exit();
            }
        }

        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lockout_time'] = time() + $lockout_duration;
            $_SESSION['message'] = json_encode([
                'type' => 'error',
                'text' => 'Too many failed login attempts. Please try again in 3 minutes.'
            ]);
        } else {
            $remaining_attempts = 3 - $_SESSION['login_attempts'];
            $_SESSION['message'] = json_encode([
                'type' => 'error',
                'text' => 'Incorrect username or password. Attempts remaining: ' . $remaining_attempts
            ]);
        }
        header('Location: index.php');
        exit();
    }
} else {
    $session->msg("d", $errors);
    redirect('index.php', false);
}