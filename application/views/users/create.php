<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New User</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Create New User</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Required fields alert -->
                <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
                    <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
                </div>

                <!-- Flash Messages and Validation Errors Section -->
                <div id="alerts-section" class="mb-3">
                    <div id="flash-messages">
                    <?php
                    // Check and display flash messages
                    $success = $this->session->flashdata('success');
                    $error = $this->session->flashdata('error');
                    
                    // Immediately clear these from session
                    $this->session->unset_userdata('success');
                    $this->session->unset_userdata('error');
                    
                    if($success): ?>
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1"><?php echo $success; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1"><?php echo $error; ?></div>
                        </div>
                    <?php endif; ?>
                    </div>

                    <!-- Validation errors will be inserted here -->
                    <div id="validation-errors"></div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <form role="form" id="createUserForm" enctype="multipart/form-data">
                            <div class="row">                           
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <div class="d-flex align-items-center gap-4" style="margin-top: -10px;">
                                            <div style="width: 400px;">
                                                <input type="file" class="form-control form-control-md" id="profile_image" name="profile_image" accept="image/*">
                                            </div>
                                            <div>
                                                <img id="imagePreview" src="<?php echo base_url('assets/images/users/default.jpg'); ?>" class="img-fluid avatar-xl rounded-circle" style="width: 60px; height: 60px; object-fit: cover;" alt="Profile Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off">
                                    </div>
                                </div>
                            
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="groups" class="form-label">Groups <span class="text-danger">*</span></label>
                                        <select class="form-select" id="groups" name="groups">
                                            <option value="">Select Groups</option>
                                            <?php foreach ($group_data as $k => $v): ?>
                                                <option value="<?php echo $v['id'] ?>"><?php echo $v['group_name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="cpassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Password does not match.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="fname" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="lname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-2">Gender <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3" style="margin-top: 5px">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="male" value="1">
                                                <label class="form-check-label" for="male">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="female" value="2">
                                                <label class="form-check-label" for="female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('users/') ?>" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-info">Create User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#groups").select2();

    $("#mainUserNav").addClass('active');
    $("#createUserNav").addClass('active');
    
    // Image preview functionality
    $("#profile_image").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $("#imagePreview")
                    .attr("src", e.target.result)
                    .show();
            };
            reader.readAsDataURL(file);
        } else {
            $("#imagePreview").hide();
        }
    });
    
    // Function to create alert HTML
    function createAlertHTML(message, type = 'danger') {
        return `
            <div class="alert alert-${type} text-bg-${type} alert-dismissible d-flex align-items-center auto-dismiss mb-2" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <iconify-icon icon="${type === 'success' ? 'solar:check-read-line-duotone' : 'solar:danger-triangle-bold-duotone'}" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1">${message}</div>
            </div>
        `;
    }
    
    // Auto-dismiss functionality for flash messages
    function initializeAutoDismissAlerts() {
        $(".auto-dismiss").each(function() {
            const $alert = $(this);
            const timer = setTimeout(function() {
                $alert.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);

            // Clear timeout if manually closed
            $alert.find('.btn-close').on('click', function() {
                clearTimeout(timer);
            });
        });
    }

    // Initialize auto-dismiss alerts
    initializeAutoDismissAlerts();

    // Password match validation
    function validatePasswordMatch() {
        const password = $("#password").val();
        const confirmPassword = $("#cpassword").val();
        
        if (confirmPassword) {  // Only validate if confirm password has a value
            if (password !== confirmPassword) {
                $("#cpassword").addClass('is-invalid');
                return false;
            } else {
                $("#cpassword").removeClass('is-invalid');
                return true;
            }
        }
        return true; // Return true if confirm password is empty
    }
    
    // Add event listeners for password fields
    $("#password, #cpassword").on('keyup', validatePasswordMatch);

    // Handle form submission
    $("#createUserForm").on('submit', function(e) {
        e.preventDefault();
        
        // Validate password match before submission
        if (!validatePasswordMatch()) {
            // Scroll to password field if validation fails
            $('html, body').animate({
                scrollTop: $("#cpassword").offset().top - 100
            }, 200);
            return;
        }
        
        // Create FormData object to handle file uploads
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
        submitBtn.prop('disabled', true);
        
        // Clear any existing validation errors
        $("#validation-errors").empty();
        
        $.ajax({
            url: '<?php echo base_url('users/create') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if(data.success) {
                        // Show success message
                        $("#validation-errors").empty().append(createAlertHTML(data.message, 'success'));
                        initializeAutoDismissAlerts();
                        
                        // Scroll to the success message
                        $('html, body').animate({
                            scrollTop: $("#alerts-section").offset().top - 100
                        }, 200);
                        
                        // Redirect after 5 seconds
                        setTimeout(function() {
                            window.location.href = data.redirect || '<?php echo base_url('users/') ?>';
                        }, 5000);
                    } else {
                        // Show validation errors
                        if (Array.isArray(data.errors)) {
                            // Handle multiple errors
                            data.errors.forEach(function(error) {
                                $("#validation-errors").append(createAlertHTML(error));
                            });
                        } else {
                            // Handle single error message
                            $("#validation-errors").append(createAlertHTML(data.errors));
                        }
                        initializeAutoDismissAlerts();
                        
                        // Scroll to errors
                        $('html, body').animate({
                            scrollTop: $("#alerts-section").offset().top - 100
                        }, 200);
                    }
                } catch(e) {
                    // If response is a redirect (success case)
                    if(response.includes('users/')) {
                        window.location.href = '<?php echo base_url('users/') ?>';
                    }
                }
            },
            error: function(xhr, status, error) {
                // Show error message
                $("#validation-errors").append(
                    createAlertHTML('An error occurred while creating the user. Please try again.')
                );
                initializeAutoDismissAlerts();
                
                // Scroll to errors
                $('html, body').animate({
                    scrollTop: $("#alerts-section").offset().top - 100
                }, 200);
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
  });
</script>
