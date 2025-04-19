<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Log In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/FullLogo_Transparent.png">
    <script src="<?php echo base_url()?>assets/js/config.js"></script>
    <link href="<?php echo base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="<?php echo base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <style>
        /* Override for alert messages */
        .alert p, 
        .alert div p,
        .alert .d-flex div p {
            margin-bottom: 0 !important;
        }
        .alert .d-flex div {
            margin-bottom: 0;
        }
    </style>
</head>

<body>

<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                <a href="index.html" class="auth-brand mb-3" style="padding-top: 1rem">
                        <img src="<?php echo base_url()?>assets/images/invengo.png" alt="dark logo" width="160" class="logo-dark">
                        <img src="<?php echo base_url()?>assets/images/invengo.png" alt="logo light" height="160" class="logo-light">
                 </a>

                <h4 class="fw-semibold mb-2">Login your account</h4>

                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                <div id="alerts-container"></div>
               
                <form id="login-form" class="text-start mb-3">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input id="email" name="email" class="form-control" placeholder="Enter your email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                        </div>
                    </div>

                   

                    <div class="d-grid">
                        <button class="btn btn-info" type="submit" id="login-btn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="<?php echo base_url()?>assets/js/vendor.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/app.js"></script>
    
    <!-- AJAX Login Script -->
    <script>
    $(document).ready(function() {
        // Auto dismiss alerts after 5 seconds
        function setupAutoDismissAlerts() {
            $('.alert').each(function() {
                var $alert = $(this);
                setTimeout(function() {
                    $alert.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000); // 5 seconds
            });
        }
        
        // Run on page load for server-side alerts
        setupAutoDismissAlerts();
        
        // Handle form submission
        $('#login-form').submit(function(e) {
            e.preventDefault();
            
            // Change button text and disable while processing
            $('#login-btn').html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Logging in...').prop('disabled', true);
            
            // Get form data
            var formData = {
                email: $('#email').val(),
                password: $('#password').val()
            };
            
            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('auth/login_ajax') ?>',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Clear previous alerts
                        $('#alerts-container').empty();
                        
                        // Show success message with proper styling
                        var successAlert = '<div class="alert alert-success text-bg-success alert-dismissible mb-3" role="alert">' +
                            '<div class="d-flex">' +
                            '<iconify-icon icon="solar:check-circle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                            '<div class="mb-0">' + response.message + '</div>' +
                            '</div>' +
                        '</div>';
                        $('#alerts-container').html(successAlert);
                        
                        // Don't auto-dismiss success messages before redirect
                        
                        // Redirect to dashboard after delay
                        setTimeout(function() {
                            window.location.href = response.redirect || '<?php echo base_url('dashboard') ?>';
                        }, 1000);
                    } else {
                        // Clear previous alerts
                        $('#alerts-container').empty();
                        
                        // Handle multiple error messages
                        if (Array.isArray(response.message)) {
                            // Create separate alert for each message
                            var alertsHtml = '';
                            response.message.forEach(function(msg) {
                                if (msg.trim()) {
                                    alertsHtml += '<div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">' +
                                        '<div class="d-flex">' +
                                        '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                                        '<div class="mb-0">' + msg.trim() + '</div>' +
                                        '</div>' +
                                    '</div>';
                                }
                            });
                            $('#alerts-container').html(alertsHtml);
                        } else if (typeof response.message === 'string') {
                            // Handle string that might contain multiple error messages
                            var alertsHtml = '';
                            
                            // Check if the message contains multiple lines
                            var messages = response.message.split(/\r?\n/);
                            if (messages.length > 1) {
                                // Multiple errors in one string, create separate alerts
                                messages.forEach(function(msg) {
                                    if (msg.trim()) {
                                        alertsHtml += '<div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">' +
                                            '<div class="d-flex">' +
                                            '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                                            '<div class="mb-0">' + msg.trim() + '</div>' +
                                            '</div>' +
                                        '</div>';
                                    }
                                });
                                $('#alerts-container').html(alertsHtml);
                            } else {
                                // Single error message
                                var errorAlert = '<div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">' +
                                    '<div class="d-flex">' +
                                    '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                                    '<div class="mb-0">' + response.message.trim() + '</div>' +
                                    '</div>' +
                                '</div>';
                                $('#alerts-container').html(errorAlert);
                            }
                        } else {
                            // Unknown error format, show generic error
                            var errorAlert = '<div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">' +
                                '<div class="d-flex">' +
                                '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                                '<div class="mb-0">An error occurred. Please try again.</div>' +
                                '</div>' +
                            '</div>';
                            $('#alerts-container').html(errorAlert);
                        }
                        
                        // Set up auto dismiss for error alerts
                        setupAutoDismissAlerts();
                        
                        $('#login-btn').html('Login').prop('disabled', false);
                    }
                },
                error: function() {
                    // Clear previous alerts
                    $('#alerts-container').empty();
                    
                    // Show generic error message with proper styling
                    var errorAlert = '<div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">' +
                        '<div class="d-flex">' +
                        '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-2"></iconify-icon>' +
                        '<div class="mb-0">An error occurred. Please try again.</div>' +
                        '</div>' +
                    '</div>';
                    $('#alerts-container').html(errorAlert);
                    
                    // Set up auto dismiss for error alerts
                    setupAutoDismissAlerts();
                    
                    $('#login-btn').html('Login').prop('disabled', false);
                }
            });
        });
    });
    </script>

</body>

</html>