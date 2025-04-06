<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Account Settings</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
          <li class="breadcrumb-item active">Account Settings</li>
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
          
          // Clear flash messages immediately after retrieving them
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

          <?php if(validation_errors()): ?>
            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
              <div class="lh-1"><?php echo validation_errors(); ?></div>
            </div>
          <?php endif; ?>
        </div>

        <div class="card">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Update Information</h5>
          </div>
          <div class="card-body">
            <form role="form" action="<?php echo base_url('users/setting') ?>" method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo $user_data['username'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $user_data['email'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter first name" value="<?php echo $user_data['firstname'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter last name" value="<?php echo $user_data['lastname'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo $user_data['phone'] ?>" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label d-block">Gender</label>
                    <div class="form-check form-check-inline" style="margin-top: 8px;">
                      <input type="radio" class="form-check-input" name="gender" id="male" value="1" <?php if($user_data['gender'] == 1) echo "checked"; ?>>
                      <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input type="radio" class="form-check-input" name="gender" id="female" value="2" <?php if($user_data['gender'] == 2) echo "checked"; ?>>
                      <label class="form-check-label" for="female">Female</label>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="alert alert-info text-bg-light alert-dismissible d-flex align-items-center" role="alert">
                    <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Leave the password field empty if you don't want to change.</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" autocomplete="off">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm password" autocomplete="off">
                    <div class="invalid-feedback" id="password-error" style="display: none;">
                      Password does not match
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <div class="text-end">
                  <button type="submit" class="btn btn-info">Save Changes</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Initialize auto-dismiss alerts
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

  initializeAutoDismissAlerts();

  // Password validation
  $('#cpassword').on('keyup', function() {
    var password = $('#password').val();
    var cpassword = $(this).val();
    
    if (cpassword !== '') {
      if (password !== cpassword) {
        $(this).removeClass('is-valid').addClass('is-invalid');
        $('#password-error').show();
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
        $('#password-error').hide();
      }
    } else {
      $(this).removeClass('is-valid is-invalid');
      $('#password-error').hide();
    }
  });
});
</script>

 
