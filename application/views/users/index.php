  <!-- Content Wrapper. Contains page content -->
<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Users</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
          <li class="breadcrumb-item active">Manage Users</li>
      </ol>
      </div>
    </div>

      <div class="row">
      <div class="col-12">
        <div id="messages">
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

        <div class="card">
          <div class="card-header border-bottom">
            <div class="d-flex flex-wrap justify-content-between gap-2">
              <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px;">
                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a user" style="width: 100%;">
                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
              </div>
              <div class="d-flex gap-1">
                <?php if(in_array('viewUser', $user_permission)): ?>
                  <button type="button" class="btn btn-light" id="showUsersBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Users</button>
                <?php endif; ?>
                <?php if(in_array('createUser', $user_permission)): ?>
                  <button type="button" class="btn btn-soft-info" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="ti ti-plus me-1"></i> Create New User
                  </button>
                <?php endif; ?>
                
                <div class="dropdown user-actions" style="display: none;">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="actionsBtn">
                    <i class="ti ti-settings me-1"></i> Actions
                  </button>
                  <ul class="dropdown-menu">
                    <?php if(in_array('updateUser', $user_permission)): ?>
                      <li>
                        <a class="dropdown-item d-flex align-items-center edit-item" href="#">
                          <i class="ti ti-edit me-2"></i> Edit
                        </a>
                      </li>
                    <?php endif; ?>
                    <?php if(in_array('deleteUser', $user_permission)): ?>
                      <li>
                        <a class="dropdown-item d-flex align-items-center text-danger delete-item" href="#">
                          <i class="ti ti-trash me-2"></i> Delete
                        </a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover text-nowrap mb-0" id="userTable">
              <thead class="bg-dark-subtle" id="userTableHead">
                <tr>
                  <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                  </th>
                  <th></th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Group</th>
                </tr>
                </thead>
              <tbody id="userTableBody">
                <!-- Users will be loaded here dynamically -->
                </tbody>
              </table>
          </div>

          <div class="card-footer" id="userFooter">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted" id="userRange">
                <!-- Range info will be inserted here -->
              </div>
              <ul class="pagination mb-0">
                <!-- Pagination will be inserted here -->
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Create User Modal -->
  <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="createUserModalLabel">Create New User</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <!-- Required fields alert -->
          <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
            <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
          </div>

          <!-- Flash Messages and Validation Errors Section -->
          <div id="alerts-section" class="mb-3">
            <div id="flash-messages"></div>
            <div id="validation-errors"></div>
          </div>

          <!-- Form Card -->
          <div class="card">
            <div class="card-body">
              <form role="form" id="createUserForm" enctype="multipart/form-data">
                <div class="row">                           
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="profile_image" class="form-label">Profile Image</label>
                      <div class="d-flex align-items-center gap-4" style="margin-top: -10px;">
                        <div style="width: 220px;">
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
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-info" id="submitCreateUser">Create User</button>
        </div>
      </div>
    </div>
  </div>
<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title text-danger" id="deleteUserTitle">
          <i class="ti ti-trash me-2"></i>Delete User
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="mb-4">
          <div class="avatar-lg mx-auto">
            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
              <i class="ti ti-trash fs-24"></i>
            </div>
          </div>
        </div>
        <div class="text-muted mb-4">
          <p id="deleteUserMessage" class="fs-5 mb-0">Are you sure you want to delete this user? This action cannot be undone.</p>
        </div>
        <input type="hidden" id="selectedUserIds" value="">
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteUser">
          <i class="ti ti-trash me-1"></i>Delete User
        </button>
      </div>
    </div>
  </div>
</div>
  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <!-- Required fields alert -->
          <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
            <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
          </div>

          <!-- Flash Messages and Validation Errors Section -->
          <div id="edit-alerts-section" class="mb-3">
            <div id="edit-flash-messages"></div>
            <div id="edit-validation-errors"></div>
          </div>

          <!-- Form Card -->
          <div class="card">
            <div class="card-body">
              <form role="form" id="editUserForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="row">                           
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_profile_image" class="form-label">Profile Image</label>
                      <div class="d-flex align-items-center gap-4" style="margin-top: -10px;">
                        <div style="width: 220px;">
                          <input type="file" class="form-control form-control-md" id="edit_profile_image" name="profile_image" accept="image/*">
                        </div>
                        <div>
                          <img id="editImagePreview" src="" class="img-fluid avatar-xl rounded-circle" style="width: 60px; height: 60px; object-fit: cover;" alt="Profile Preview">
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_username" class="form-label">Username <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="edit_username" name="username" placeholder="Username" autocomplete="off">
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" class="form-control" id="edit_email" name="email" placeholder="Email" autocomplete="off">
                    </div>
                  </div>
              
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_groups" class="form-label">Groups <span class="text-danger">*</span></label>
                      <select class="form-select" id="edit_groups" name="groups">
                        <option value="">Select Groups</option>
                        <?php foreach ($group_data as $k => $v): ?>
                          <option value="<?php echo $v['id'] ?>"><?php echo $v['group_name'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_fname" class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="edit_fname" name="fname" placeholder="First name" autocomplete="off">
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_lname" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="edit_lname" name="lname" placeholder="Last name" autocomplete="off">
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_phone" class="form-label">Phone</label>
                      <input type="text" class="form-control" id="edit_phone" name="phone" placeholder="Phone" autocomplete="off">
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label mb-2">Gender</label>
                      <div class="d-flex gap-3" style="margin-top: 5px">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" id="edit_male" value="1">
                          <label class="form-check-label" for="edit_male">Male</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" id="edit_female" value="2">
                          <label class="form-check-label" for="edit_female">Female</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
                      <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
                      <div class="lh-1">Leave the password field empty if you don't want to change.</div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="edit_password" name="password" placeholder="Password" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="edit_cpassword" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="edit_cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                      <div class="invalid-feedback">
                        Password does not match.
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-info" id="submitEditUser">Save Changes</button>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";

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

    $(document).ready(function() {
  // Initialize Bootstrap dropdowns and modal
  var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
  var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl);
  });

  // Initialize Select2 in modal
  $("#groups").select2({
    dropdownParent: $('#createUserModal')
  });

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
  $("#submitCreateUser").on('click', function() {
    // Validate password match before submission
    if (!validatePasswordMatch()) {
      return;
    }
    
    // Create FormData object to handle file uploads
    const formData = new FormData($("#createUserForm")[0]);
    
    // Show loading state
    const submitBtn = $(this);
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
            
            // Reset form
            $("#createUserForm")[0].reset();
            $("#imagePreview").attr("src", "<?php echo base_url('assets/images/users/default.jpg'); ?>");
            
            // Close modal and refresh table after delay
            setTimeout(function() {
              $("#createUserModal").modal('hide');
              loadUserTable();
            }, 2000);
          } else {
            // Show validation errors
            if (Array.isArray(data.errors)) {
              data.errors.forEach(function(error) {
                $("#validation-errors").append(createAlertHTML(error));
              });
            } else {
              $("#validation-errors").append(createAlertHTML(data.errors));
            }
            initializeAutoDismissAlerts();
          }
        } catch(e) {
          if(response.includes('users/')) {
            $("#createUserModal").modal('hide');
            loadUserTable();
          }
        }
      },
      error: function(xhr, status, error) {
        $("#validation-errors").append(
          createAlertHTML('An error occurred while creating the user. Please try again.')
        );
        initializeAutoDismissAlerts();
      },
      complete: function() {
        // Reset button state
        submitBtn.html(originalText);
        submitBtn.prop('disabled', false);
      }
    });
  });

  // Reset form and errors when modal is closed
  $('#createUserModal').on('hidden.bs.modal', function () {
    $("#createUserForm")[0].reset();
    $("#imagePreview").attr("src", "<?php echo base_url('assets/images/users/default.jpg'); ?>");
    $("#validation-errors").empty();
    $("#cpassword").removeClass('is-invalid');
  });

  // Initialize auto-dismiss alerts
  initializeAutoDismissAlerts();

  // Clear user table initially
  $("#userTableBody").html('');
  
  // Initialize user actions visibility
  toggleUserActions();
  
  // Show users button click handler
  $("#showUsersBtn").click(function() {
    loadUserTable();
    $(this).hide();
  });

  // Search functionality
  $('#searchBox').on('keyup', function() {
    var searchText = $(this).val();
    if ($("#userTableBody").children().length > 0) {
      loadUserTable(1, searchText);
    }
  });
  
  // Function to update actions button visibility
  function toggleUserActions() {
    let checkedCount = $(".user-checkbox:checked").length;
    
    if (checkedCount > 0) {
      $('.user-actions').show();
    } else {
      $('.user-actions').hide();
    }
    
    // Show/hide the edit option based on number of selections
    if (checkedCount === 1) {
      $('.edit-item').removeClass('hidden').css('display', 'flex');
    } else {
      $('.edit-item').addClass('hidden').css('display', 'none');
    }
  }
  
  // Select all checkbox functionality
  $("#selectAll").on("change", function() {
    let isChecked = $(this).prop("checked");
    $(".user-checkbox").prop("checked", isChecked);
    toggleUserActions();
  });
  
  // Individual checkbox change detection
  $(document).on("change", ".user-checkbox", function() {
    if ($('.user-checkbox:checked').length === $('.user-checkbox').length) {
      $('#selectAll').prop('checked', true);
    } else {
      $('#selectAll').prop('checked', false);
    }
    toggleUserActions();
  });
  
  // Handle edit action
  $(document).on('click', '.edit-item', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    let selectedUserId = $(".user-checkbox:checked").val();
    if(selectedUserId) {
      // Clear previous errors
      $("#edit-validation-errors").empty();
      
      // Show loading state in modal
      $("#editUserModal").modal('show');
      
      // Add loading overlay to form
      $("#editUserForm").append('<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75" id="editFormLoader"><div class="spinner-border text-info" role="status"></div></div>');
      
      // Fetch user data
      $.ajax({
        url: base_url + 'users/edit/' + selectedUserId,
        type: 'GET',
        success: function(response) {
          try {
            const userData = JSON.parse(response);
            
            // Remove loading overlay
            $("#editFormLoader").remove();
            
            // Populate form fields
            $("#edit_user_id").val(userData.id);
            $("#edit_username").val(userData.username);
            $("#edit_email").val(userData.email);
            $("#edit_groups").val(userData.group_id).trigger('change');
            $("#edit_fname").val(userData.firstname);
            $("#edit_lname").val(userData.lastname);
            $("#edit_phone").val(userData.phone);
            $("#editImagePreview").attr("src", base_url + "assets/images/users/" + (userData.profile_image || 'default.jpg'));
            
            // Set gender
            if(userData.gender == 1) {
              $("#edit_male").prop('checked', true);
            } else if(userData.gender == 2) {
              $("#edit_female").prop('checked', true);
            }
            
          } catch(e) {
            $("#editFormLoader").remove();
            $("#edit-validation-errors").html(createAlertHTML('Failed to load user data. Please try again.'));
            initializeAutoDismissAlerts();
          }
        },
        error: function(xhr, status, error) {
          $("#editFormLoader").remove();
          $("#edit-validation-errors").html(createAlertHTML('Failed to load user data. Please try again.'));
          initializeAutoDismissAlerts();
        }
      });
    }
  });

  // Handle edit form submission
  $("#submitEditUser").on('click', function() {
    // Validate password match before submission
    if (!validateEditPasswordMatch()) {
      return;
    }
    
    // Create FormData object to handle file uploads
    const formData = new FormData($("#editUserForm")[0]);
    
    // Show loading state
    const submitBtn = $(this);
    const originalText = submitBtn.html();
    submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
    submitBtn.prop('disabled', true);
    
    // Clear any existing validation errors
    $("#edit-validation-errors").empty();
    
    $.ajax({
      url: base_url + 'users/edit/' + $("#edit_user_id").val(),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        try {
          const data = JSON.parse(response);
          if(data.success) {
            // Show success message in the main messages area with username
            $("#messages").html(createAlertHTML(data.message, 'success'));
            initializeAutoDismissAlerts();
            
            // Close modal and refresh table immediately
            $("#editUserModal").modal('hide');
            loadUserTable();
          } else {
            // Show validation errors
            if (Array.isArray(data.errors)) {
              data.errors.forEach(function(error) {
                $("#edit-validation-errors").append(createAlertHTML(error));
              });
            } else {
              $("#edit-validation-errors").append(createAlertHTML(data.errors));
            }
            initializeAutoDismissAlerts();
          }
        } catch(e) {
          // If redirect response, just refresh the table
          if(response.includes('users/')) {
            $("#editUserModal").modal('hide');
            loadUserTable();
          }
        }
      },
      error: function(xhr, status, error) {
        $("#edit-validation-errors").append(
          createAlertHTML('An error occurred while updating the user. Please try again.')
        );
        initializeAutoDismissAlerts();
      },
      complete: function() {
        // Reset button state
        submitBtn.html(originalText);
        submitBtn.prop('disabled', false);
      }
    });
  });

  // Reset form and errors when edit modal is closed
  $('#editUserModal').on('hidden.bs.modal', function () {
    $("#editUserForm")[0].reset();
    $("#edit-validation-errors").empty();
    $("#edit_cpassword").removeClass('is-invalid');
  });

  // Initialize Select2 for edit modal
  $("#edit_groups").select2({
    dropdownParent: $('#editUserModal')
  });

  // Edit image preview functionality
  $("#edit_profile_image").change(function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        $("#editImagePreview")
          .attr("src", e.target.result)
          .show();
      };
      reader.readAsDataURL(file);
    }
  });

  // Password match validation for edit form
  function validateEditPasswordMatch() {
    const password = $("#edit_password").val();
    const confirmPassword = $("#edit_cpassword").val();
    
    if (confirmPassword) {  // Only validate if confirm password has a value
      if (password !== confirmPassword) {
        $("#edit_cpassword").addClass('is-invalid');
        return false;
      } else {
        $("#edit_cpassword").removeClass('is-invalid');
        return true;
      }
    }
    return true; // Return true if confirm password is empty
  }

  // Add event listeners for password fields in edit form
  $("#edit_password, #edit_cpassword").on('keyup', validateEditPasswordMatch);

  // Add CSS for the loading overlay
  $("<style>")
    .prop("type", "text/css")
    .html(`
      #editUserForm {
        position: relative;
      }
      #editFormLoader {
        z-index: 1000;
      }
      /* Hide edit option by default */
      .edit-item.hidden {
        display: none !important;
      }
    `)
    .appendTo("head");
});

// Function to load users table with pagination
function loadUserTable(page = 1, search = '') {
  $.ajax({
    url: base_url + "users/getUsers",
    type: "GET",
    data: { 
      page: page,
      search: search
    },
    dataType: "json",
    success: function(response) {
      let html = '';
      
      if (response.users && response.users.length > 0) {
        response.users.forEach(function(user) {
          html += '<tr>' +
            '<td class="ps-3">' +
              '<input type="checkbox" class="form-check-input user-checkbox" value="' + user.id + '">' +
            '</td>' +
            '<td style="width: 40px;">' +
              '<img src="' + base_url + 'assets/images/users/' + (user.profile_image || 'default.jpg') + '"' +
                   'class="rounded-circle"' +
                   'style="width: 32px; height: 32px; object-fit: cover;"' +
                   'alt="' + user.username + '\'s profile">' +
            '</td>' +
            '<td>' + user.username + '</td>' +
            '<td>' + user.email + '</td>' +
            '<td>' + user.firstname + ' ' + (user.lastname || '') + '</td>' +
            '<td>' + (user.phone || '') + '</td>' +
            '<td>' +
              '<span class="badge text-bg-info">' + user.group_name + '</span>' +
            '</td>' +
          '</tr>';
        });
      } else {
        html = '<tr><td colspan="7" class="text-center">No users found</td></tr>';
      }
      
      $("#userTableBody").html(html);
      
      // Update pagination if provided
      if (response.users && response.users.length > 0) {
        // Update pagination status
        const start = (page - 1) * 10 + 1;
        const end = Math.min(start + response.users.length - 1, response.total_users);
        const total = response.total_users;
        
        $("#userRange").html('Showing ' + start + ' to ' + end + ' of ' + total + ' users').fadeIn();

        // Generate pagination
        let totalPages = Math.ceil(total / 10);
        let paginationHtml = '';
        
        // Always show pagination container
        $(".pagination").show();
        
        // First page button
        paginationHtml += '<li class="page-item' + (page <= 1 ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadUserTable(' + 1 + ', \'' + search + '\')"><i class="ti ti-chevrons-left"></i></a></li>';
        
        // Previous button
        paginationHtml += '<li class="page-item' + (page <= 1 ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadUserTable(' + (page - 1) + ', \'' + search + '\')">Previous</a></li>';
        
        // Page numbers
        let startPage = Math.max(1, page - 2);
        let endPage = Math.min(totalPages, page + 2);
        
        // Ensure we always show 5 pages if possible
        if (endPage - startPage < 4) {
          if (startPage === 1) {
            endPage = Math.min(5, totalPages);
          } else if (endPage === totalPages) {
            startPage = Math.max(1, totalPages - 4);
          }
        }
        
        for(let i = startPage; i <= endPage; i++) {
          paginationHtml += '<li class="page-item' + (i === parseInt(page) ? ' active' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadUserTable(' + i + ', \'' + search + '\')">' + i + '</a></li>';
        }
        
        // Next button
        paginationHtml += '<li class="page-item' + (page >= totalPages ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadUserTable(' + (page + 1) + ', \'' + search + '\')">Next</a></li>';
        
        // Last page button
        paginationHtml += '<li class="page-item' + (page >= totalPages ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadUserTable(' + totalPages + ', \'' + search + '\')"><i class="ti ti-chevrons-right"></i></a></li>';
        
        $(".pagination").html(paginationHtml);
        $("#userFooter").show();
      } else {
        $("#userRange").html('Showing 0 to 0 of 0 entries').fadeIn();
        $(".pagination").html('');
        $("#userFooter").show();
      }
      
      // Reset checkboxes and actions
      $('#selectAll').prop('checked', false);
      $('.user-actions').hide();
    },
    error: function(xhr, status, error) {
      $("#userTableBody").html(
        '<tr>' +
          '<td colspan="7" class="text-center text-danger">' +
            '<div class="d-flex align-items-center justify-content-center">' +
              '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>' +
              'Failed to load users' +
            '</div>' +
          '</td>' +
        '</tr>'
      );
    }
  });
}

// Handle delete action
$(document).on('click', '.delete-item', function(e) {
  e.preventDefault();
  e.stopPropagation();
  
  // Get all selected user IDs
  let selectedUserIds = [];
  $(".user-checkbox:checked").each(function() {
    selectedUserIds.push($(this).val());
  });
  
  if(selectedUserIds.length > 0) {
    // Populate delete modal with user info
    if(selectedUserIds.length === 1) {
      // Single user deletion
      let username = $(".user-checkbox:checked").closest('tr').find('td:eq(2)').text();
      $("#deleteUserTitle").text("Delete User");
      $("#deleteUserMessage").html(`Are you sure you want to delete user <strong>${username}</strong>? This action cannot be undone.`);
    } else {
      // Multiple user deletion
      $("#deleteUserTitle").text("Delete Multiple Users");
      $("#deleteUserMessage").html(`Are you sure you want to delete <strong>${selectedUserIds.length}</strong> selected users? This action cannot be undone.`);
    }
    
    // Store selected user IDs in modal
    $("#selectedUserIds").val(JSON.stringify(selectedUserIds));
    
    // Show delete modal
    $("#deleteUserModal").modal('show');
  }
});

// Handle delete confirmation
$("#confirmDeleteUser").on('click', function() {
  const selectedUserIds = JSON.parse($("#selectedUserIds").val());
  
  if(selectedUserIds.length === 0) {
    return;
  }
  
  // Show loading state
  const deleteBtn = $(this);
  const originalText = deleteBtn.html();
  deleteBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Deleting...');
  deleteBtn.prop('disabled', true);
  
  // Process deletion sequentially
  let successCount = 0;
  let errorCount = 0;
  let processedCount = 0;
  
  function processNextDeletion(index) {
    if(index >= selectedUserIds.length) {
      // All deletions processed, show results
      const message = `Successfully deleted ${successCount} user${successCount !== 1 ? 's' : ''}` + 
                     (errorCount > 0 ? `, failed to delete ${errorCount} user${errorCount !== 1 ? 's' : ''}` : '');
      
      // Display message at the top using the global createAlertHTML function
      $("#messages").html(createAlertHTML(message, successCount > 0 ? 'success' : 'danger'));
      initializeAutoDismissAlerts();
      
      // Close modal and refresh table
      $("#deleteUserModal").modal('hide');
      loadUserTable();
      
      // Reset button state
      deleteBtn.html(originalText);
      deleteBtn.prop('disabled', false);
      return;
    }
    
    const userId = selectedUserIds[index];
    
    $.ajax({
      url: base_url + 'users/delete/' + userId,
      type: 'POST',
      data: { confirm: true },
      success: function(response) {
        successCount++;
        processedCount++;
        processNextDeletion(index + 1);
      },
      error: function() {
        errorCount++;
        processedCount++;
        processNextDeletion(index + 1);
      }
    });
  }
  
  // Start deletion process
  processNextDeletion(0);
    });
  </script>


