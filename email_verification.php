<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

function sendVerificationEmail($email, $verification_code) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration (replace with your actual SMTP settings)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'montgomeryaurelia06@gmail.com';  // Your Gmail address
        $mail->Password   = 'oylq mpnj adlw iuod';     // Use an App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email details
        $mail->setFrom('montgomeryaurelia06@gmail.com', 'Inventory Management System');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body    = "
            <html>
            <body>
                <h2>Email Verification</h2>
                <p>Your 5-digit verification code is:</p>
                <h1 style='color: #dc2626; letter-spacing: 10px;'>{$verification_code}</h1>
                <p>This code will expire in 10 minutes.</p>
            </body>
            </html>
        ";

        // Send the email
        return $mail->send();
    } catch (Exception $e) {
        // Log the error
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

function validateVerificationCode($email, $entered_code) {
    global $db;
    
    // Query to find the user and check the verification code
    $sql = "SELECT id, code, verified, 
            TIMESTAMPDIFF(MINUTE, created_at, NOW()) as code_age 
            FROM users 
            WHERE email = '{$email}'";
    
    $result = $db->query($sql);
    $user = $db->fetch_assoc($result);
    
    // Check if code matches and is less than 10 minutes old
    if ($user && $user['code'] == $entered_code && $user['code_age'] <= 10) {
        // Update user as verified
        $update_sql = "UPDATE users SET verified = 1, code = NULL WHERE id = {$user['id']}";
        $db->query($update_sql);
        return true;
    }
    
    return false;
}

// Optional: Resend verification code function
function resendVerificationCode($email) {
    global $db;
    
    // Generate new verification code
    $new_code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
    
    // Update the verification code in the database
    $sql = "UPDATE users SET code = '{$new_code}', created_at = NOW() WHERE email = '{$email}'";
    
    if ($db->query($sql)) {
        // Send new verification email
        return sendVerificationEmail($email, $new_code);
    }
    
    return false;
}
?>