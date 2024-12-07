<?php
include_once('includes/load.php');

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
    
    // Escape the input to prevent SQL injection
    $username_or_email = $db->escape($username_or_email);
    $password = $db->escape($password);
    
    // Modify query to check both username and email, and select status
    $sql = sprintf("SELECT id, username, password, user_level, status FROM users WHERE (username='%s' OR email='%s') LIMIT 1", 
                   $username_or_email, 
                   $username_or_email);
    
    $result = $db->query($sql);
    
    if($db->num_rows($result)){
        $user = $db->fetch_assoc($result);
        
        // Check if the user is inactive
        if ($user['status'] != 1) {
            $_SESSION['message'] = json_encode([
                'type' => 'error',
                'text' => 'Your account is inactive. Please wait for admin approval.'
            ]);
            return false; // User is not active
        }
        
        // Check if the password matches
        if (password_verify($password, $user['password']) || 
            sha1($password) === $user['password']) {
            // If old SHA1 hash is used, rehash with bcrypt
            if (sha1($password) === $user['password']) {
                $new_hash = password_hash($password, PASSWORD_BCRYPT);
                $update_sql = sprintf("UPDATE users SET password='%s' WHERE id=%d", 
                                      $db->escape($new_hash), 
                                      $user['id']);
                $db->query($update_sql);
            }
            // Return the user ID and user level
            return ['id' => $user['id'], 'user_level' => $user['user_level']];
        }
    }
   
    return false;
}

// Validate required fields
$req_fields = array('password');
validate_fields($req_fields);

// Get username/email and password from POST
$username_or_email = remove_junk($_POST['username']) ?: remove_junk($_POST['email']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    // Attempt to authenticate
    $user_data = authenticate($username_or_email, $password);

    if ($user_data) {
        // Reset login attempts on successful login
        $_SESSION['login_attempts'] = 0;
        
        $session->login($user_data['id']);
        updateLastLogIn($user_data['id']);

        // Set a success message
        $_SESSION['message'] = json_encode([
            'type' => 'success',
            'text' => 'Login successful!'
        ]);

        // Redirect based on user level
        if ($user_data['user_level'] == 1) {
            header("Location: admin.php");
        } else if ($user_data['user_level'] == 2) {
            header("Location: cashier.php");
        }
        exit();
    } else {
        // Check if the user exists but is inactive
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

        // Increment login attempts
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 3) {
            // Lockout user and set lockout time
            $_SESSION['lockout_time'] = time() + $lockout_duration;
            $_SESSION['message'] = json_encode([
                'type' => 'error',
                'text' => 'Too many failed login attempts. Please try again in 3 minutes.'
            ]);
        } else {
            // Incorrect login message
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
    // Handle validation errors
    $session->msg("d", $errors);
    redirect('index.php', false);
}