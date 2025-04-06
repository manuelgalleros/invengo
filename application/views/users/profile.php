<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">My Profile</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-8">
        <div class="text-center mb-4 mt-4">
          <div class="profile-image mx-auto mb-3">
            <?php if(isset($user_data['profile_image']) && !empty($user_data['profile_image'])): ?>
              <img src="<?php echo base_url('assets/images/users/'.$user_data['profile_image']); ?>" 
                   alt="Profile Image" 
                   class="rounded-circle img-thumbnail"
                   style="width: 120px; height: 120px; object-fit: cover;">
            <?php else: ?>
              <div class="avatar-lg mx-auto">
                <div class="avatar-title bg-info-subtle text-info rounded-circle fs-24">
                  <?php echo strtoupper(substr($user_data['firstname'], 0, 1) . substr($user_data['lastname'], 0, 1)); ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <h5 class="fs-16 mb-1"><?php echo $user_data['firstname'] . ' ' . $user_data['lastname']; ?></h5>
          <p class="text-muted mb-4">
            <span class="badge bg-info-subtle text-info px-2 py-1"><?php echo $user_group['group_name']; ?></span>
          </p>
        </div>

        <div class="bg-light rounded-3 p-4 mb-5">
          <div class="table-responsive">
            <table class="table table-borderless table-sm mb-0">
              <tbody>
                <tr>
                  <th class="ps-0" style="width: 150px;">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-user-circle me-2 fs-18"></i>
                      Username
                    </div>
                  </th>
                  <td class="text-muted"><?php echo $user_data['username']; ?></td>
                </tr>
                <tr>
                  <th class="ps-0">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-mail me-2 fs-18"></i>
                      Email
                    </div>
                  </th>
                  <td class="text-muted"><?php echo $user_data['email']; ?></td>
                </tr>
                <tr>
                  <th class="ps-0">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-gender-bigender me-2 fs-18"></i>
                      Gender
                    </div>
                  </th>
                  <td class="text-muted"><?php echo ($user_data['gender'] == 1) ? 'Male' : 'Female'; ?></td>
                </tr>
                <tr>
                  <th class="ps-0">
                    <div class="d-flex align-items-center">
                      <i class="ti ti-phone me-2 fs-18"></i>
                      Phone
                    </div>
                  </th>
                  <td class="text-muted"><?php echo $user_data['phone']; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

<style>
.avatar-lg {
  height: 150px;
  width: 150px;
}

.avatar-title {
  align-items: center;
  display: flex;
  font-weight: 500;
  height: 100%;
  justify-content: center;
  width: 100%;
}

.table > :not(caption) > * > * {
  padding: 0.75rem 0.75rem;
}

.bg-light {
  background-color: rgba(0,0,0,.025) !important;
}
</style>

 
