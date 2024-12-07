<?php
ob_start();
require_once('includes/load.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['code']) || count($_POST['code']) !== 5) {
        $_SESSION['message'] = json_encode([
            'type' => 'error',
            'text' => 'Invalid verification code.'
        ]);
        redirect('verify.php');
    }

    // Combine the verification code digits
    $verification_code = implode('', $_POST['code']);

    // Sanitize the input
    $verification_code = $db->escape($verification_code);

    // Find the user with the matching verification code
    $query = "SELECT id, email FROM users WHERE code = '{$verification_code}' AND verified = 0";
    $result = $db->query($query);

    if ($db->num_rows($result) > 0) {
        // Fetch the user details
        $user = $db->fetch_assoc($result);

        // Update the user's verified status and set username to email
        $update_query = "UPDATE users SET verified = 1, code = '', username = '{$user['email']}' WHERE id = '{$user['id']}'";
        $db->query($update_query);

        // Set success message
        $_SESSION['message'] = json_encode([
            'type' => 'success', 
            'text' => 'Account verified successfully!'
        ]);
        redirect('index.php');
    } else {
        // No matching code found
        $_SESSION['message'] = json_encode([
            'type' => 'error', 
            'text' => 'Invalid or expired verification code.'
        ]);
        redirect('verify.php');
    }
} else {
    // Direct access prevention
    $_SESSION['message'] = json_encode([
        'type' => 'error', 
        'text' => 'Invalid access.'
    ]);
    redirect('verify.php');
}

ob_end_flush();