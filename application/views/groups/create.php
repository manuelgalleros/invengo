<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="page-content">
    <div class="page-container">
      <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
        <div class="flex-grow-1">
          <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Group</h4>
        </div>
        <div class="text-end">
          <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item active">Create New Group</li>
          </ol>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div id="messages">
            <?php if($this->session->flashdata('success')): ?>
              <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1"><?php echo $this->session->flashdata('success'); ?></div>
              </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
              <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1"><?php echo $this->session->flashdata('error'); ?></div>
              </div>
            <?php endif; ?>
          </div>
          <!-- Required fields alert -->
          <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
            <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
          </div>

          <div class="card">
            <form action="<?php echo base_url('groups/create') ?>" method="post">
              <div class="card-body">
                <div class="form-group mb-3">
                  <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name">
                </div>
                
                <div class="form-group">
                  <label for="permission" class="form-label">Permissions</label>

                  <table class="table table-borderless">
                    <thead class="bg-light">
                      <tr>
                        <th>Module</th>
                        <th style="width: 140px;" class="text-center">Create</th>
                        <th style="width: 140px;" class="text-center">Update</th>
                        <th style="width: 140px;" class="text-center">View</th>
                        <th style="width: 140px;" class="text-center">Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Users</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createUser" name="permission[]" value="createUser" data-switch="success">
                            <label for="createUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateUser" name="permission[]" value="updateUser" data-switch="success">
                            <label for="updateUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewUser" name="permission[]" value="viewUser" data-switch="success">
                            <label for="viewUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteUser" name="permission[]" value="deleteUser" data-switch="success">
                            <label for="deleteUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Groups</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createGroup" name="permission[]" value="createGroup" data-switch="success">
                            <label for="createGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateGroup" name="permission[]" value="updateGroup" data-switch="success">
                            <label for="updateGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewGroup" name="permission[]" value="viewGroup" data-switch="success">
                            <label for="viewGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteGroup" name="permission[]" value="deleteGroup" data-switch="success">
                            <label for="deleteGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Brands</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createBrand" name="permission[]" value="createBrand" data-switch="success">
                            <label for="createBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateBrand" name="permission[]" value="updateBrand" data-switch="success">
                            <label for="updateBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewBrand" name="permission[]" value="viewBrand" data-switch="success">
                            <label for="viewBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteBrand" name="permission[]" value="deleteBrand" data-switch="success">
                            <label for="deleteBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Categories</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createCategory" name="permission[]" value="createCategory" data-switch="success">
                            <label for="createCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateCategory" name="permission[]" value="updateCategory" data-switch="success">
                            <label for="updateCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewCategory" name="permission[]" value="viewCategory" data-switch="success">
                            <label for="viewCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteCategory" name="permission[]" value="deleteCategory" data-switch="success">
                            <label for="deleteCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Products</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createProduct" name="permission[]" value="createProduct" data-switch="success">
                            <label for="createProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateProduct" name="permission[]" value="updateProduct" data-switch="success">
                            <label for="updateProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewProduct" name="permission[]" value="viewProduct" data-switch="success">
                            <label for="viewProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteProduct" name="permission[]" value="deleteProduct" data-switch="success">
                            <label for="deleteProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Orders</td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="createOrder" name="permission[]" value="createOrder" data-switch="success">
                            <label for="createOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateOrder" name="permission[]" value="updateOrder" data-switch="success">
                            <label for="updateOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewOrder" name="permission[]" value="viewOrder" data-switch="success">
                            <label for="viewOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="deleteOrder" name="permission[]" value="deleteOrder" data-switch="success">
                            <label for="deleteOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Reports</td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewReports" name="permission[]" value="viewReports" data-switch="success">
                            <label for="viewReports" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                      </tr>
                      <tr>
                        <td>Profile</td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="viewProfile" name="permission[]" value="viewProfile" data-switch="success">
                            <label for="viewProfile" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                      </tr>
                      <tr>
                        <td>Setting</td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>
                            <input type="checkbox" id="updateSetting" name="permission[]" value="updateSetting" data-switch="success">
                            <label for="updateSetting" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                          </div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                        <td class="text-center">
                          <div>-</div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-end">
                  <a href="<?php echo base_url('groups/') ?>" class="btn btn-danger">Cancel</a>
                  <button type="submit" class="btn btn-info ms-2">Create Group</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
  // Add CSS for switch centering
  $("<style>")
    .prop("type", "text/css")
    .html(`
      /* Center switches in table cells */
      td.text-center div {
        display: flex;
        justify-content: center;
        align-items: center;
      }
      
      /* Ensure consistent spacing */
      [data-switch] + label {
        margin: 0 auto;
      }
    `)
    .appendTo("head");

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
        $alert.remove();
      });
    });
  }

  // Initialize alerts on page load
  initializeAutoDismissAlerts();
});
</script>

