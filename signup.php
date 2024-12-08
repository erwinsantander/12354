<?php
// signup.php
require_once('includes/load.php');
require_once('email_verification.php');

// Redirect if already logged in
if ($session->isUserLoggedIn()) {
    redirect('home.php', false);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Generate a random 10-digit ID
    $random_id = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

    // Collect and sanitize input
    $name = $db->escape($_POST['full_name']);
    $user_level = 2; // Default to Cashier
    $email = !empty($_POST['signup_email']) ? $db->escape($_POST['signup_email']) : null;
    $password = $db->escape($_POST['signup_password']);
    $confirm_password = $db->escape($_POST['confirm_password']);

    // Validation
    if (empty($name))
        $errors[] = "Name is required.";
    if (empty($email))
        $errors[] = "Email is required.";
    if (empty($password))
        $errors[] = "Password is required.";
    if ($password !== $confirm_password)
        $errors[] = "Passwords do not match.";

    // Check if email already exists
    $email_check = $db->query("SELECT * FROM users WHERE email = '{$email}'");
    if ($db->num_rows($email_check) > 0)
        $errors[] = "Email already registered.";

    // Check if the random ID already exists
    $id_check = $db->query("SELECT * FROM users WHERE id = '{$random_id}'");
    while ($db->num_rows($id_check) > 0) {
        // Regenerate ID if it already exists
        $random_id = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        $id_check = $db->query("SELECT * FROM users WHERE id = '{$random_id}'");
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $default_image = 'no_image.jpg';
        $status = 0;
        $verified = 0;

        // Prepare SQL with the new random ID
        $sql = "INSERT INTO users (id, name, password, user_level, image, email, status, last_login, code, verified, created_at) 
                VALUES ('{$random_id}', '{$name}', '{$hashed_password}', {$user_level}, '{$default_image}', '{$email}', {$status}, NULL, '{$verification_code}', {$verified}, NOW())";

        if ($db->query($sql)) {
            if (sendVerificationEmail($email, $verification_code)) {
                $_SESSION['signup_email'] = $email; // Store email for verification page
                $session->msg("s", "Account created successfully. Please verify your email.");
                redirect('verify.php', false);
            } else {
                $session->msg("d", "Account created, but failed to send verification email.");
                redirect('verify.php', false);
            }
        } else {
            $session->msg("d", "Failed to create account. Please try again.");
        }
    } else {
        $session->msg("d", implode(' ', $errors));
    }
}

include_once('layouts/header.php');
?>