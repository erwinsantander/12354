<?php
    ob_start();
    require_once('includes/load.php');
    if ($session->isUserLoggedIn()) {
        redirect('admin.php', false);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Inventory Management System - Login/Signup</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-primary': '#dc2626',
                        'brand-secondary': '#16a34a'
                    }
                }
            }
        }
    </script>
    <style>
        /* Ensure SweetAlert is positioned at the top */
        .swal2-container {
            z-index: 9999;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-200 to-red-300">
    <?php
    // Check for messages from previous redirects
    if (isset($_SESSION['message'])) {
        $message = json_decode($_SESSION['message'], true);
        unset($_SESSION['message']);
    }
    ?>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="container mx-auto px-4 flex justify-center items-center">
            <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h1 id="page-title" class="text-3xl font-bold text-brand-primary mb-2">Login</h1>
                        <p class="text-gray-600">Inventory Management System</p>
                    </div>

                    <div id="form-container">
                        <!-- Login Form -->
                        <form method="post" action="auth.php" id="login-form" class="space-y-4">
                            <div id="username-field">
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="username"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Enter username">
                                </div>
                            </div>

                            <div id="email-field" class="hidden">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Enter email">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="w-full pl-10 pr-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Enter password" required>
                                    <button type="button" id="show-password"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-primary text-white py-2 rounded-md hover:bg-red-700 transition duration-300">
                                Login
                            </button>
                        </form>

                        <!-- Signup Form (Hidden by Default) -->
                        <form method="post" action="signup.php" id="signup-form" class="space-y-4 hidden">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="full_name"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Enter full name" required>
                                </div>
                            </div>

                            <div id="signup-email-field">
                                <label for="signup_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="signup_email"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Enter email" required>
                                </div>
                            </div>

                            <div>
                                <label for="signup_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="signup_password" name="signup_password"
                                        class="w-full pl-10 pr-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Create password" required>
                                    <button type="button" id="show-signup-password"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-primary"
                                        placeholder="Confirm password" required>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-secondary text-white py-2 rounded-md hover:bg-green-700 transition duration-300">
                                Create Account
                            </button>
                        </form>
                    </div>

                    <!-- Switch between Login and Signup -->
                    <div class="text-center mt-4">
                        <p id="switch-form-text" class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="#" id="switch-form" class="text-brand-primary hover:underline">Sign Up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php if (isset($message)): ?>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: '<?php echo $message['type'] === 'success' ? '' : ''; ?>',
                    text: '<?php echo $message['text']; ?>',
                    icon: '<?php echo $message['type']; ?>',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    background: '<?php echo $message['type'] === 'success' ? '#d1f2eb' : '#f2d7d5'; ?>',
                    customClass: {
                        popup: 'swal2-toast-custom-class'
                    }
                }).then(() => {
                    <?php if ($message['type'] === 'success'): ?>
                        window.location.href = '<?php echo $_SESSION['redirect_url'] ?? 'home.php'; ?>';
                    <?php endif; ?>
                });
            });
        <?php endif; ?>

        function setupPasswordToggle(passwordId, toggleButtonId) {
            const passwordField = document.getElementById(passwordId);
            const toggleButton = document.getElementById(toggleButtonId);
            const icon = toggleButton.querySelector('i');
            toggleButton.addEventListener('click', function () {
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }

        setupPasswordToggle('password', 'show-password');
        setupPasswordToggle('signup_password', 'show-signup-password');

        // Form switching logic using event delegation
        document.addEventListener('click', function (e) {
            // Check if the clicked element or its parent is the switch form link
            const switchFormLink = e.target.closest('#switch-form');

            if (switchFormLink) {
                e.preventDefault();
                const loginForm = document.getElementById('login-form');
                const signupForm = document.getElementById('signup-form');
                const pageTitle = document.getElementById('page-title');
                const switchFormText = document.getElementById('switch-form-text');

                // Check which form is currently visible
                if (loginForm.classList.contains('hidden')) {
                    // Switch back to login form
                    loginForm.classList.remove('hidden');
                    signupForm.classList.add('hidden');
                    pageTitle.textContent = 'Login';
                    switchFormText.innerHTML = 'Don\'t have an account? <a href="#" id="switch-form" class="text-brand-primary hover:underline">Sign Up</a>';
                } else {
                    // Switch to signup form
                    loginForm.classList.add('hidden');
                    signupForm.classList.remove('hidden');
                    pageTitle.textContent = 'Sign Up';
                    switchFormText.innerHTML = 'Already have an account? <a href="#" id="switch-form" class="text-brand-primary hover:underline">Login</a>';
                }
            }
        });
    </script>

    <?php include_once('layouts/footer.php'); ?>
</body>
</html>