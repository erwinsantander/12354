<?php
ob_start();
require_once('includes/load.php');

// Check if the token is present in the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['message'] = json_encode([
        'type' => 'error', 
        'text' => 'Invalid or missing verification token.'
    ]);
    redirect('login.php');
}

// Sanitize the token
$token = $db->escape($_GET['token']);

// Validate the token and find the corresponding user
$query = "SELECT id, email FROM users WHERE verification_token = '{$token}' AND verified = 0";
$result = $db->query($query);

if ($db->num_rows($result) === 0) {
    $_SESSION['message'] = json_encode([
        'type' => 'error', 
        'text' => 'Invalid or expired verification token.'
    ]);
    redirect('login.php');
}

// Fetch the user details
$user = $db->fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['code']) || count($_POST['code']) !== 5) {
        $_SESSION['message'] = json_encode([
            'type' => 'error',
            'text' => 'Invalid verification code.'
        ]);
        redirect("verify.php?token={$token}");
    }

    // Combine the verification code digits
    $verification_code = implode('', $_POST['code']);

    // Sanitize the input
    $verification_code = $db->escape($verification_code);

    // Check if the verification code matches
    $code_query = "SELECT * FROM user_verification_codes 
                   WHERE user_id = '{$user['id']}' 
                   AND code = '{$verification_code}' 
                   AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
    $code_result = $db->query($code_query);

    if ($db->num_rows($code_result) > 0) {
        // Update the user's verified status and clear token and verification code
        $update_query = "UPDATE users SET 
                         verified = 1, 
                         verification_token = '', 
                         username = '{$user['email']}'
                         WHERE id = '{$user['id']}'";
        $db->query($update_query);

        // Delete used verification code
        $delete_code_query = "DELETE FROM user_verification_codes 
                               WHERE user_id = '{$user['id']}' 
                               AND code = '{$verification_code}'";
        $db->query($delete_code_query);

        // Set success message
        $_SESSION['message'] = json_encode([
            'type' => 'success', 
            'text' => 'Account verified successfully!'
        ]);
        redirect('login.php');
    } else {
        // Invalid or expired verification code
        $_SESSION['message'] = json_encode([
            'type' => 'error', 
            'text' => 'Invalid or expired verification code.'
        ]);
        redirect("verify.php?token={$token}");
    }
}

// If not POST, show verification page with the token
ob_end_flush();