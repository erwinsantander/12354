$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission
        
        var formData = $(this).serialize();
        
        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Successful login
                    showModal('success', 'Welcome to Inventory Management System');
                    setTimeout(function() {
                        window.location.href = 'admin.php'; // Redirect after 2 seconds
                    }, 2000);
                } else {
                    // Error or incorrect login
                    showModal('error', 'Sorry Username/Password incorrect.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
    
    function showModal(type, message) {
        var modal = $('#loginModal');
        var modalContent = modal.find('.modal-content');
        
        // Clear previous classes
        modalContent.removeClass('modal-success modal-error');
        
        // Set classes based on type
        if (type === 'success') {
            modalContent.addClass('modal-success');
            modalContent.html('<div class="modal-icon"><i class="fas fa-check-circle"></i></div><div class="modal-message">' + message + '</div>');
        } else if (type === 'error') {
            modalContent.addClass('modal-error');
            modalContent.html('<div class="modal-icon"><i class="fas fa-times-circle"></i></div><div class="modal-message">' + message + '</div>');
        }
        
        // Display the modal
        modal.modal('show');
    }
});
