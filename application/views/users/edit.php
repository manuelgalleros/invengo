<!-- Content Wrapper. Contains page content -->
<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit User</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
          <li class="breadcrumb-item active">Edit User</li>
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

        <!-- Required fields alert -->
        <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
          <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
          <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
        </div>

        <!-- Form Card -->
        <div class="card">
          <div class="card-body">
            <form role="form" action="<?php echo base_url('users/edit/'.$user_data['id']) ?>" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="profile_image" class="form-label">Profile Image</label>
                    <div class="d-flex align-items-center gap-4" style="margin-top: -10px;">
                      <div style="width: 220px;">
                        <input type="file" class="form-control form-control-md" id="profile_image" name="profile_image" accept="image/*">
                      </div>
                      <div>
                        <img id="imagePreview" src="<?php echo base_url('assets/images/users/'.($user_data['profile_image'] ? $user_data['profile_image'] : 'default.jpg')); ?>" class="img-fluid avatar-xl rounded-circle" style="width: 60px; height: 60px; object-fit: cover;" alt="Profile Preview">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user_data['username'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user_data['email'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="groups" class="form-label">Groups <span class="text-danger">*</span></label>
                    <select class="form-select" id="groups" name="groups">
                      <option value="">Select Groups</option>
                      <?php foreach ($group_data as $k => $v): ?>
                        <option value="<?php echo $v['id'] ?>" <?php if($user_group['id'] == $v['id']) { echo 'selected'; } ?>><?php echo $v['group_name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="fname" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?php echo $user_data['firstname'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?php echo $user_data['lastname'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php echo $user_data['phone'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label class="form-label mb-2">Gender</label>
                    <div class="d-flex gap-3" style="margin-top: 5px">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="1" <?php if($user_data['gender'] == 1) { echo "checked"; } ?>>
                        <label class="form-check-label" for="male">Male</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="2" <?php if($user_data['gender'] == 2) { echo "checked"; } ?>>
                        <label class="form-check-label" for="female">Female</label>
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
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="mb-3">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                    <div class="invalid-feedback">
                      Password does not match.
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo base_url('users/') ?>" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-info">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#groups").select2();

  $("#mainUserNav").addClass('active');
  $("#manageUserNav").addClass('active');

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
      $("#imagePreview").attr("src", "<?php echo base_url('assets/images/users/'.($user_data['profile_image'] ? $user_data['profile_image'] : 'default.jpg')); ?>");
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

  // Form submission validation
  $("form").on('submit', function(e) {
    if (!validatePasswordMatch()) {
      e.preventDefault();
      return false;
    }
  });
});
</script>
