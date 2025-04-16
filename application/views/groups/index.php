<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Groups</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
          <li class="breadcrumb-item active">Manage Groups</li>
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
        </div>

        <div class="card">
          <div class="card-header border-bottom">
            <div class="d-flex flex-wrap justify-content-between gap-2">
              <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px;">
                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a group" style="width: 100%;">
                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
              </div>
              <div class="d-flex gap-1">
                <?php if(in_array('viewGroup', $user_permission)): ?>
                  <button type="button" class="btn btn-light" id="showGroupsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Groups</button>
                <?php endif; ?>
          <?php if(in_array('createGroup', $user_permission)): ?>
                  <button type="button" class="btn btn-soft-info" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    <i class="ti ti-plus me-1"></i> Create New Group
                  </button>
          <?php endif; ?>

                <div class="dropdown group-actions" style="display: none;">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="actionsBtn">
                    <i class="ti ti-settings me-1"></i> Actions
                  </button>
                  <ul class="dropdown-menu">
                    <?php if(in_array('updateGroup', $user_permission)): ?>
                      <li id="editItemContainer" style="display: none;">
                        <a class="dropdown-item d-flex align-items-center edit-item" href="#">
                          <i class="ti ti-edit me-2"></i> Edit
                        </a>
                      </li>
                    <?php endif; ?>
                    <?php if(in_array('deleteGroup', $user_permission)): ?>
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
            <table class="table table-hover text-nowrap mb-0" id="groupTable">
              <thead class="bg-dark-subtle" id="groupTableHead">
                <tr>
                  <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                  </th>
                  <th>Group Name</th>
                  <th>Permissions</th>
                </tr>
                </thead>
              <tbody id="groupTableBody">
                  <?php if($groups_data): ?>                  
                    <?php foreach ($groups_data as $k => $v): ?>
                      <tr>
                      <td class="ps-3">
                        <input type="checkbox" class="form-check-input group-checkbox" value="<?php echo $v['id']; ?>">
                      </td>
                        <td><?php echo $v['group_name']; ?></td>
                      <td>
                        <button type="button" class="btn btn-sm btn-soft-dark view-permissions-btn" data-id="<?php echo $v['id']; ?>">
                          <i class="ti ti-eye me-1"></i> View
                        </button>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php endif; ?>
              </tbody>
            </table>
          </div>
          
          <div class="card-footer" id="groupFooter">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted" id="groupRange">
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

  <!-- Create Group Modal -->
  <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="createGroupModalLabel">Create New Group</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="createGroupForm" action="<?php echo base_url('groups/create') ?>" method="post">
          <div class="modal-body">
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
                    <th style="width: 140px;" class="text-center">Delete/Archive</th>
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
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info">Create Group</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Group Modal -->
  <div class="modal fade" id="deleteGroupModal" tabindex="-1" aria-labelledby="deleteGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header border-0 pb-0">
          <h4 class="modal-title text-danger" id="deleteGroupTitle">
            <i class="ti ti-trash me-2"></i>Delete Group
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
            <p id="deleteGroupMessage" class="fs-5 mb-0">Are you sure you want to delete this group? This action cannot be undone.</p>
            <p class="mt-2 mb-0">Deleting a group will not delete the users in that group.</p>
          </div>
          <form action="" method="post" id="deleteGroupForm">
            <input type="hidden" id="group_id" name="group_id">
          </form>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ti ti-x me-1"></i>Cancel
          </button>
          <button type="button" class="btn btn-danger" id="confirmDeleteGroup">
            <i class="ti ti-trash me-1"></i>Delete Group
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Group Modal -->
  <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="editGroupModalLabel">Edit Group</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post" id="editGroupForm">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label for="edit_group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_group_name" name="group_name" placeholder="Enter group name">
            </div>
            
            <div class="form-group">
              <label for="permission" class="form-label">Permissions</label>
              <div id="edit_permission_container">
                <!-- Permissions will be loaded here -->
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-info">Update Group</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Permissions Modal -->
  <div class="modal fade" id="viewPermissionsModal" tabindex="-1" aria-labelledby="viewPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="viewPermissionsModalLabel">Group Permissions</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 class="fw-bold mb-3" id="permissionGroupName">Group Name</h5>
          
          <div class="table-responsive" id="permissionsContainer">
            <table class="table table-borderless">
              <thead class="bg-light">
                <tr>
                  <th style="width: 120px;">Module</th>
                  <th style="width: 140px;" class="text-center">Create</th>
                  <th style="width: 140px;" class="text-center">Update</th>
                  <th style="width: 140px;" class="text-center">View</th>
                  <th style="width: 140px;" class="text-center">Delete/Archive</th>
                </tr>
              </thead>
              <tbody id="permissionsTableBody">
                <!-- Permissions will be loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";
  
  // Add CSS for switch centering
  $(document).ready(function() {
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

  // Clear all flash messages
  function clearAllMessages() {
    $("#messages").empty();
  }

  // Add a message
  function addMessage(message, type = 'danger') {
    $("#messages").append(createAlertHTML(message, type));
    initializeAutoDismissAlerts();
  }

  // Function to load groups table with pagination
  function loadGroupTable(page = 1, search = '') {
    $.ajax({
      url: base_url + "groups/getGroups",
      type: "GET",
      data: { 
        page: page,
        search: search
      },
      dataType: "json",
      success: function(response) {
        let html = '';
        
        if (response.groups && response.groups.length > 0) {
          response.groups.forEach(function(group) {
            html += '<tr>' +
              '<td class="ps-3">' +
                '<input type="checkbox" class="form-check-input group-checkbox" value="' + group.id + '">' +
              '</td>' +
              '<td>' + group.group_name + '</td>' +
              '<td>' +
                '<button type="button" class="btn btn-sm btn-soft-dark view-permissions-btn" data-id="' + group.id + '">' +
                  '<i class="ti ti-eye me-1"></i> View' +
                '</button>' +
              '</td>' +
            '</tr>';
          });
        } else {
          html = '<tr><td colspan="3" class="text-center">No groups found</td></tr>';
        }
        
        $("#groupTableBody").html(html);
        
        // Update pagination if provided
        if (response.groups && response.groups.length > 0) {
          // Update pagination status
          const start = (page - 1) * 10 + 1;
          const end = Math.min(start + response.groups.length - 1, response.total_groups);
          const total = response.total_groups;
          
          $("#groupRange").html('Showing ' + start + ' to ' + end + ' of ' + total + ' groups').fadeIn();

          // Generate pagination
          let totalPages = Math.ceil(total / 10);
          let paginationHtml = '';
          
          // Always show pagination container
          $(".pagination").show();
          
          // Previous button
          paginationHtml += '<li class="page-item' + (page <= 1 ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadGroupTable(' + (page - 1) + ', \'' + search + '\')">Previous</a></li>';
          
          // Page numbers
          for(let i = 1; i <= totalPages; i++) {
            paginationHtml += '<li class="page-item' + (i === parseInt(page) ? ' active' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadGroupTable(' + i + ', \'' + search + '\')">' + i + '</a></li>';
          }
          
          // Next button
          paginationHtml += '<li class="page-item' + (page >= totalPages ? ' disabled' : '') + '"><a class="page-link" href="javascript:void(0);" onclick="loadGroupTable(' + (page + 1) + ', \'' + search + '\')">Next</a></li>';
          
          $(".pagination").html(paginationHtml);
          $(".pagination").show();
          $("#groupRange").show();
        } else {
          $("#groupRange").html('Showing 0 to 0 of 0 entries').fadeIn();
          $(".pagination").html('');
          $("#groupRange").show();
        }
        
        // Reset checkboxes and actions
        $('#selectAll').prop('checked', false);
        $('.group-actions').hide();
        
        // Initialize group actions visibility
        toggleGroupActions();
      },
      error: function(xhr, status, error) {
        $("#groupTableBody").html(
          '<tr>' +
            '<td colspan="3" class="text-center text-danger">' +
              '<div class="d-flex align-items-center justify-content-center">' +
                '<iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>' +
                'Failed to load groups' +
              '</div>' +
            '</td>' +
          '</tr>'
        );
      }
    });
  }

  // Function to update actions button visibility
  function toggleGroupActions() {
    let checkedCount = $(".group-checkbox:checked").length;
    
    if (checkedCount > 0) {
      $('.group-actions').show();
    } else {
      $('.group-actions').hide();
    }
    
    // Show/hide the edit option based on number of selections
    if (checkedCount === 1) {
      $('#editItemContainer').show();
    } else {
      $('#editItemContainer').hide();
    }
  }

    $(document).ready(function() {
    initializeAutoDismissAlerts();
    
    // Clear group table initially
    $("#groupTableBody").html('');
    $("#groupFooter").show();
    $("#groupRange").hide();
    $(".pagination").hide();
    
    // Show groups button click handler
    $("#showGroupsBtn").click(function() {
      loadGroupTable();
      $(this).hide();
    });
    
    // Initialize group actions visibility
    toggleGroupActions();
    
    // Create group form submit handler
    $("#createGroupForm").on('submit', function(e) {
      e.preventDefault();
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          // Close the modal
          $('#createGroupModal').modal('hide');
          
          // Clear form fields
          $("#createGroupForm")[0].reset();
          
          // Clear existing messages
          clearAllMessages();
          
          if(response.success) {
            // Show success message with group name
            addMessage(`Group <strong>${response.group_name}</strong> was successfully created.`, 'success');
            
            // Reload the group table to reflect the changes
            if($("#groupTableBody").children().length > 0) {
              loadGroupTable();
            }
          } else {
            // Show error message
            addMessage(response.message || 'Error creating group');
          }
        },
        error: function(xhr, status, error) {
          // Clear existing messages
          clearAllMessages();
          
          // Show error message
          addMessage('An error occurred while creating the group');
          
          // Close the modal
          $('#createGroupModal').modal('hide');
        }
      });
    });
    
    // Search functionality
    $('#searchBox').on('keyup', function() {
      var searchText = $(this).val().toLowerCase();
      if ($("#groupTableBody").children().length > 0) {
        loadGroupTable(1, searchText);
      }
    });
    
    // Select all checkbox functionality
    $("#selectAll").on("change", function() {
      let isChecked = $(this).prop("checked");
      $(".group-checkbox").prop("checked", isChecked);
      toggleGroupActions();
    });
    
    // Individual checkbox change detection
    $(document).on("change", ".group-checkbox", function() {
      if ($('.group-checkbox:checked').length === $('.group-checkbox').length) {
        $('#selectAll').prop('checked', true);
      } else {
        $('#selectAll').prop('checked', false);
      }
      toggleGroupActions();
    });
    
    // Handle edit action
    $(document).on('click', '.edit-item', function(e) {
      e.preventDefault();
      let groupId = $(".group-checkbox:checked").val();
      
      if(groupId) {
        // Set the form action
        $('#editGroupForm').attr('action', base_url + 'groups/edit/' + groupId);
        
        // Fetch the group data
        $.ajax({
          url: base_url + 'groups/get_group_permissions',
          type: 'POST',
          data: { group_id: groupId },
          dataType: 'json',
          success: function(response) {
            if(response.success) {
              // Set group name
              $('#edit_group_name').val(response.group_name);
              
              // Ensure permissions is an array
              const permissions = Array.isArray(response.permissions) ? response.permissions : [];
              
              // Populate permissions table
              let permissionHtml = `
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
                          <input type="checkbox" id="edit_createUser" name="permission[]" value="createUser" data-switch="success" ${permissions.includes('createUser') ? 'checked' : ''}>
                          <label for="edit_createUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateUser" name="permission[]" value="updateUser" data-switch="success" ${permissions.includes('updateUser') ? 'checked' : ''}>
                          <label for="edit_updateUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewUser" name="permission[]" value="viewUser" data-switch="success" ${permissions.includes('viewUser') ? 'checked' : ''}>
                          <label for="edit_viewUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteUser" name="permission[]" value="deleteUser" data-switch="success" ${permissions.includes('deleteUser') ? 'checked' : ''}>
                          <label for="edit_deleteUser" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Groups</td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_createGroup" name="permission[]" value="createGroup" data-switch="success" ${permissions.includes('createGroup') ? 'checked' : ''}>
                          <label for="edit_createGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateGroup" name="permission[]" value="updateGroup" data-switch="success" ${permissions.includes('updateGroup') ? 'checked' : ''}>
                          <label for="edit_updateGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewGroup" name="permission[]" value="viewGroup" data-switch="success" ${permissions.includes('viewGroup') ? 'checked' : ''}>
                          <label for="edit_viewGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteGroup" name="permission[]" value="deleteGroup" data-switch="success" ${permissions.includes('deleteGroup') ? 'checked' : ''}>
                          <label for="edit_deleteGroup" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Brands</td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_createBrand" name="permission[]" value="createBrand" data-switch="success" ${permissions.includes('createBrand') ? 'checked' : ''}>
                          <label for="edit_createBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateBrand" name="permission[]" value="updateBrand" data-switch="success" ${permissions.includes('updateBrand') ? 'checked' : ''}>
                          <label for="edit_updateBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewBrand" name="permission[]" value="viewBrand" data-switch="success" ${permissions.includes('viewBrand') ? 'checked' : ''}>
                          <label for="edit_viewBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteBrand" name="permission[]" value="deleteBrand" data-switch="success" ${permissions.includes('deleteBrand') ? 'checked' : ''}>
                          <label for="edit_deleteBrand" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Categories</td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_createCategory" name="permission[]" value="createCategory" data-switch="success" ${permissions.includes('createCategory') ? 'checked' : ''}>
                          <label for="edit_createCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateCategory" name="permission[]" value="updateCategory" data-switch="success" ${permissions.includes('updateCategory') ? 'checked' : ''}>
                          <label for="edit_updateCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewCategory" name="permission[]" value="viewCategory" data-switch="success" ${permissions.includes('viewCategory') ? 'checked' : ''}>
                          <label for="edit_viewCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteCategory" name="permission[]" value="deleteCategory" data-switch="success" ${permissions.includes('deleteCategory') ? 'checked' : ''}>
                          <label for="edit_deleteCategory" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Products</td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_createProduct" name="permission[]" value="createProduct" data-switch="success" ${permissions.includes('createProduct') ? 'checked' : ''}>
                          <label for="edit_createProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateProduct" name="permission[]" value="updateProduct" data-switch="success" ${permissions.includes('updateProduct') ? 'checked' : ''}>
                          <label for="edit_updateProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewProduct" name="permission[]" value="viewProduct" data-switch="success" ${permissions.includes('viewProduct') ? 'checked' : ''}>
                          <label for="edit_viewProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteProduct" name="permission[]" value="deleteProduct" data-switch="success" ${permissions.includes('deleteProduct') ? 'checked' : ''}>
                          <label for="edit_deleteProduct" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Orders</td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_createOrder" name="permission[]" value="createOrder" data-switch="success" ${permissions.includes('createOrder') ? 'checked' : ''}>
                          <label for="edit_createOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_updateOrder" name="permission[]" value="updateOrder" data-switch="success" ${permissions.includes('updateOrder') ? 'checked' : ''}>
                          <label for="edit_updateOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_viewOrder" name="permission[]" value="viewOrder" data-switch="success" ${permissions.includes('viewOrder') ? 'checked' : ''}>
                          <label for="edit_viewOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <input type="checkbox" id="edit_deleteOrder" name="permission[]" value="deleteOrder" data-switch="success" ${permissions.includes('deleteOrder') ? 'checked' : ''}>
                          <label for="edit_deleteOrder" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
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
                          <input type="checkbox" id="edit_viewReports" name="permission[]" value="viewReports" data-switch="success" ${permissions.includes('viewReports') ? 'checked' : ''}>
                          <label for="edit_viewReports" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
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
                          <input type="checkbox" id="edit_viewProfile" name="permission[]" value="viewProfile" data-switch="success" ${permissions.includes('viewProfile') ? 'checked' : ''}>
                          <label for="edit_viewProfile" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
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
                          <input type="checkbox" id="edit_updateSetting" name="permission[]" value="updateSetting" data-switch="success" ${permissions.includes('updateSetting') ? 'checked' : ''}>
                          <label for="edit_updateSetting" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
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
              `;
              
              $('#edit_permission_container').html(permissionHtml);
              
              // Show the edit modal
              $('#editGroupModal').modal('show');
            } else {
              // Show error message
              $("#messages").html(createAlertHTML('Failed to load group data.'));
              initializeAutoDismissAlerts();
            }
          },
          error: function() {
            // Show error message
            $("#messages").html(createAlertHTML('Failed to load group data.'));
            initializeAutoDismissAlerts();
          }
        });
      }
    });
    
    // Handle delete action
    $(document).on('click', '.delete-item', function(e) {
      e.preventDefault();
      const checkedGroups = $(".group-checkbox:checked");
      
      if(checkedGroups.length > 0) {
        // Get all selected group IDs and names
        const groupIds = [];
        const groupNames = [];
        
        checkedGroups.each(function() {
          groupIds.push($(this).val());
          groupNames.push($(this).closest('tr').find('td:eq(1)').text());
        });
        
        // Update the hidden input
        $('#group_id').val(groupIds[0]); // For backward compatibility with single delete
        
        // Update modal title and message based on selection count
        if(groupIds.length === 1) {
          $("#deleteGroupTitle").html('<i class="ti ti-trash me-2"></i>Delete Group');
          $("#deleteGroupMessage").html(`Are you sure you want to delete group <strong>${groupNames[0]}</strong>? This action cannot be undone.`);
        } else {
          $("#deleteGroupTitle").html('<i class="ti ti-trash me-2"></i>Delete Multiple Groups');
          $("#deleteGroupMessage").html(`Are you sure you want to delete these <strong>${groupNames.length}</strong> groups? This action cannot be undone.`);
        }
        
        // Show delete modal
        $('#deleteGroupModal').modal('show');
      }
    });
    
    // Handle delete confirmation
    $('#confirmDeleteGroup').on('click', function() {
      const checkedGroups = $(".group-checkbox:checked");
      
      if (checkedGroups.length === 0) {
        return;
      }
      
      // Get all selected group IDs
      const groupIds = [];
      checkedGroups.each(function() {
        groupIds.push($(this).val());
      });
      
      if (groupIds.length === 1) {
        // Single group deletion
        const groupId = groupIds[0];
        const groupName = checkedGroups.closest('tr').find('td:eq(1)').text();
        
        $.ajax({
          url: base_url + 'groups/delete/' + groupId,
          type: 'POST',
          dataType: 'json',
          success: function(response) {
            // Close the modal
            $('#deleteGroupModal').modal('hide');
            
            // Clear existing messages
            clearAllMessages();
            
            if (response.success) {
              // Show success message with group name
              addMessage(`Group <strong>${response.group_name || groupName}</strong> was successfully deleted.`, 'success');
              
              // Reload the group table to reflect the changes
              if ($("#groupTableBody").children().length > 0) {
                loadGroupTable();
              }
            } else {
              // Show error message
              addMessage(response.message || 'Error deleting group');
            }
          },
          error: function(xhr, status, error) {
            // Close the modal
            $('#deleteGroupModal').modal('hide');
            
            // Clear existing messages
            clearAllMessages();
            
            // Show error message
            addMessage('An error occurred while deleting the group');
          }
        });
      } else {
        // Multiple groups deletion
        $.ajax({
          url: base_url + 'groups/delete_multiple',
          type: 'POST',
          data: { group_ids: groupIds },
          dataType: 'json',
          success: function(response) {
            // Close the modal
            $('#deleteGroupModal').modal('hide');
            
            // Clear existing messages
            clearAllMessages();
            
            if (response.success) {
              // Show success message with just the count for multiple deletions
              let message = '';
              if (response.deleted_count > 0) {
                // Use proper singular/plural form based on count
                const noun = response.deleted_count === 1 ? 'group was' : 'groups were';
                message = `${response.deleted_count} ${noun} successfully deleted.`;
              } else {
                message = response.message;
              }
              
              addMessage(message, 'success');
              
              // If there were errors, show them too
              if (response.error_count > 0) {
                addMessage(response.message, 'warning');
              }
              
              // Reload the group table to reflect the changes
              if ($("#groupTableBody").children().length > 0) {
                loadGroupTable();
              }
            } else {
              // Show error message
              addMessage(response.message || 'Error deleting groups');
            }
          },
          error: function(xhr, status, error) {
            // Close the modal
            $('#deleteGroupModal').modal('hide');
            
            // Clear existing messages
            clearAllMessages();
            
            // Show error message
            addMessage('An error occurred while deleting groups');
          }
        });
      }
    });
    
    // Handle view permissions button click
    $(document).on('click', '.view-permissions-btn', function(e) {
      e.preventDefault();
      let groupId = $(this).data('id');
      let groupName = $(this).closest('tr').find('td:eq(1)').text();
      
      // Show loading in the modal
      $('#permissionGroupName').text(groupName);
      $('#permissionsTableBody').html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
      $('#viewPermissionsModal').modal('show');
      
      // Fetch permissions via AJAX
      $.ajax({
        url: base_url + 'groups/get_group_permissions',
        type: 'POST',
        data: { group_id: groupId },
        dataType: 'json',
        success: function(response) {
          if(response.success) {
            // Ensure permissions is an array
            let permissions = Array.isArray(response.permissions) ? response.permissions : [];
            let html = '';
            
            // Organize permissions by module
            let modules = {
              'User': { create: false, update: false, view: false, delete: false },
              'Group': { create: false, update: false, view: false, delete: false },
              'Brand': { create: false, update: false, view: false, delete: false },
              'Category': { create: false, update: false, view: false, delete: false },
              'Product': { create: false, update: false, view: false, delete: false },
              'Order': { create: false, update: false, view: false, delete: false },
              'Report': { create: false, update: false, view: false, delete: false },
              'Profile': { create: false, update: false, view: false, delete: false },
              'Setting': { create: false, update: false, view: false, delete: false }
            };
            
            // Parse permissions
            permissions.forEach(function(perm) {
              if(perm.includes('User')) {
                if(perm === 'createUser') modules.User.create = true;
                if(perm === 'updateUser') modules.User.update = true;
                if(perm === 'viewUser') modules.User.view = true;
                if(perm === 'deleteUser') modules.User.delete = true;
              }
              else if(perm.includes('Group')) {
                if(perm === 'createGroup') modules.Group.create = true;
                if(perm === 'updateGroup') modules.Group.update = true;
                if(perm === 'viewGroup') modules.Group.view = true;
                if(perm === 'deleteGroup') modules.Group.delete = true;
              }
              else if(perm.includes('Brand')) {
                if(perm === 'createBrand') modules.Brand.create = true;
                if(perm === 'updateBrand') modules.Brand.update = true;
                if(perm === 'viewBrand') modules.Brand.view = true;
                if(perm === 'deleteBrand') modules.Brand.delete = true;
              }
              else if(perm.includes('Category')) {
                if(perm === 'createCategory') modules.Category.create = true;
                if(perm === 'updateCategory') modules.Category.update = true;
                if(perm === 'viewCategory') modules.Category.view = true;
                if(perm === 'deleteCategory') modules.Category.delete = true;
              }
              else if(perm.includes('Product')) {
                if(perm === 'createProduct') modules.Product.create = true;
                if(perm === 'updateProduct') modules.Product.update = true;
                if(perm === 'viewProduct') modules.Product.view = true;
                if(perm === 'deleteProduct') modules.Product.delete = true;
              }
              else if(perm.includes('Order')) {
                if(perm === 'createOrder') modules.Order.create = true;
                if(perm === 'updateOrder') modules.Order.update = true;
                if(perm === 'viewOrder') modules.Order.view = true;
                if(perm === 'deleteOrder') modules.Order.delete = true;
              }
              else if(perm.includes('Report')) {
                if(perm === 'viewReports') modules.Report.view = true;
              }
              else if(perm.includes('Profile')) {
                if(perm === 'viewProfile') modules.Profile.view = true;
              }
              else if(perm.includes('Setting')) {
                if(perm === 'updateSetting') modules.Setting.update = true;
              }
            });
            
            // Generate HTML
            for(let module in modules) {
              html += `
                <tr>
                  <td>${module === 'Category' ? 'Categories' : module + 's'}</td>
                  <td class="text-center">${module === 'Report' || module === 'Profile' || module === 'Setting' ? '-' : renderPermissionIcon(modules[module].create)}</td>
                  <td class="text-center">${module === 'Report' || module === 'Profile' ? '-' : (module === 'Setting' ? renderPermissionIcon(modules[module].update) : renderPermissionIcon(modules[module].update))}</td>
                  <td class="text-center">${module === 'Setting' ? '-' : renderPermissionIcon(modules[module].view)}</td>
                  <td class="text-center">${module === 'Report' || module === 'Profile' || module === 'Setting' ? '-' : renderPermissionIcon(modules[module].delete)}</td>
                </tr>
              `;
            }
            
            $('#permissionsTableBody').html(html);
          } else {
            $('#permissionsTableBody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load permissions</td></tr>');
          }
        },
        error: function() {
          $('#permissionsTableBody').html('<tr><td colspan="5" class="text-center text-danger">Error loading permissions</td></tr>');
        }
      });
    });
    
    // Helper function to render permission icons
    function renderPermissionIcon(hasPermission) {
      if(hasPermission) {
        return '<span class="badge rounded-pill bg-success-subtle text-success px-2 py-1 d-inline-flex align-items-center justify-content-center"><i class="ti ti-check me-1"></i><span>Yes</span></span>';
      } else {
        return '<span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1 d-inline-flex align-items-center justify-content-center"><i class="ti ti-x me-1"></i><span>No</span></span>';
      }
    }

    // Edit group form submission
    $("#editGroupForm").on('submit', function(e) {
      e.preventDefault();
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          // Close the modal
          $('#editGroupModal').modal('hide');
          
          // Clear existing messages
          clearAllMessages();
          
          if(response.success) {
            // Show success message with group name
            addMessage(`Group <strong>${response.group_name}</strong> was successfully updated.`, 'success');
            
            // Reload the group table to reflect the changes
            if($("#groupTableBody").children().length > 0) {
              loadGroupTable();
            }
          } else {
            // Show error message
            addMessage(response.message || 'Error updating group');
          }
        },
        error: function(xhr, status, error) {
          // Clear existing messages
          clearAllMessages();
          
          // Show error message
          addMessage('An error occurred while updating the group');
          
          // Close the modal
          $('#editGroupModal').modal('hide');
        }
      });
    });
    });
  </script>
