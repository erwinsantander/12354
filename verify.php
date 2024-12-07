<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Verify Account - Inventory Management System</title>
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
        /* Ensure SweetAlert is positioned correctly */
        .swal2-container {
            z-index: 9999 !important;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-orange-200 to-red-300">
    <?php
    // Ensure session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check for messages from previous redirects
    $message = null;
    if (isset($_SESSION['message'])) {
        $message = json_decode($_SESSION['message'], true);
        unset($_SESSION['message']);
    }
    ?>

    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Main Container -->
        <div class="container mx-auto px-4 flex justify-center items-center">
            <!-- Card Container -->
            <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-brand-primary mb-2">Verify Account</h1>
                        <p class="text-gray-600 mb-4">Please enter the 5-digit code sent to your email</p>
                    </div>
                    
                    <form method="post" action="process_verification.php" class="space-y-6">
                        <!-- Verification Code Input Fields -->
                        <div class="flex justify-center space-x-2">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <input type="text" 
                                       maxlength="1" 
                                       class="w-12 h-12 text-center text-2xl border-2 border-gray-300 rounded-lg focus:border-brand-primary focus:outline-none" 
                                       required 
                                       name="code[]" 
                                       autocomplete="off">
                            <?php endfor; ?>
                        </div>

                        <!-- Verify Button -->
                        <button type="submit" 
                                class="w-full bg-brand-primary text-white py-3 rounded-md hover:bg-red-700 transition duration-300 text-lg font-semibold">
                            Verify Code
                        </button>

                        <!-- Resend Code Link -->
                        <div class="text-center">
                            <p class="text-gray-600">Didn't receive the code?</p>
                            <button type="button" 
                                    id="resend-code" 
                                    class="text-brand-primary hover:underline focus:outline-none mt-1">
                                Resend Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Debugging function to show alert
        function showAlert(type, message) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? '' : '',
                text: message,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                background: type === 'success' ? '#d1f2eb' : '#f2d7d5',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }

        // Show message if exists
        document.addEventListener('DOMContentLoaded', function() {
            <?php if($message): ?>
                showAlert('<?php echo $message['type']; ?>', '<?php echo htmlspecialchars($message['text'], ENT_QUOTES); ?>');
            <?php endif; ?>

            // Debugging log
            console.log('Message data:', <?php echo json_encode($message); ?>);
        });

        // Auto-focus next input field
        const inputs = document.querySelectorAll('input[name="code[]"]');
        inputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                if (e.key !== 'Backspace' && input.value !== '') {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
            });
        });

        // Allow only numbers
        inputs.forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        });

        // Resend code functionality
        document.getElementById('resend-code').addEventListener('click', function() {
            // Add loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            this.disabled = true;

            // Simulate API call (replace with actual API call)
            setTimeout(() => {
                this.innerHTML = 'Code Resent!';
                setTimeout(() => {
                    this.innerHTML = 'Resend Code';
                    this.disabled = false;
                }, 3000);
            }, 2000);
        });
    </script>

    <?php include_once('layouts/footer.php'); ?>
</body>
</html>