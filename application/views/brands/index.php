<style>
.brand-actions {
    display: none;
}
.brand-actions.show {
    display: inline-block !important;
}
.pagination .page-link {
    cursor: pointer;
}
.pagination .active .page-link {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}
</style>

<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Brands</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Manage Brands</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="messages"></div>

                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px;">
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a brand" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewBrand', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showBrandsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Brands</button>
                                <?php endif; ?>
                                <?php if(in_array('createBrand', $user_permission)): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addBrandModal" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Brand</button>
                                <?php endif; ?>
                                <?php if(in_array('updateBrand', $user_permission) || in_array('deleteBrand', $user_permission)): ?>
                                    <div class="dropdown brand-actions d-none">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-settings me-1"></i> Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if(in_array('updateBrand', $user_permission)): ?>
                                                <li>
                                                    <a class="dropdown-item edit-item d-none" href="javascript:void(0);">
                                                        <i class="ti ti-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(in_array('deleteBrand', $user_permission)): ?>
                                                <li>
                                                    <a class="dropdown-item delete-item text-danger" href="javascript:void(0);">
                                                        <i class="ti ti-trash me-2"></i> Delete
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0" id="brandTable">
                            <thead class="bg-dark-subtle" id="brandTableHead">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>Brand Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="brandBody">
                                <!-- Brands will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted" id="pagination-status" style="display: none;">
                                Showing 0 to 0 of 0 brands
                            </div>
                            <ul class="pagination mb-0" style="display: none;">
                                <!-- Pagination will be inserted here -->
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<!-- Add Brand Modal -->
<div id="addBrandModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBrandForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name">
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Status</label>
                        <select class="form-select" id="active" name="active">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modal -->
<div id="editBrandModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBrandForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_brand_id" name="brand_id">
                    <div class="mb-3">
                        <label for="edit_brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="edit_brand_name" name="brand_name" placeholder="Enter brand name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_active" class="form-label">Status</label>
                        <select class="form-select" id="edit_active" name="active">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="removeBrandModal" class="modal fade" tabindex="-1" aria-labelledby="removeBrandModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title text-danger" id="deleteBrandTitle">
                    <i class="ti ti-trash me-2"></i>Delete Brand
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" id="removeBrandForm">
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                <i class="ti ti-trash fs-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted mb-4">
                        <p id="deleteBrandMessage" class="fs-5 mb-0">Are you sure you want to delete the selected brand(s)? This action cannot be undone.</p>
                    </div>
                    <input type="hidden" id="removeBrandIds">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
// Define base_url for JavaScript
var base_url = "<?php echo base_url(); ?>";

// Function to auto-dismiss messages
function autoDismissMessages() {
    setTimeout(function() {
        $("#messages .alert").fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}

// Function to load brand table
function loadBrandTable(page = 1, search = '') {
    $.ajax({
        url: base_url + "brands/fetchBrandData",
        type: "GET",
        data: { 
            page: page,
            search: search
        },
        dataType: "json",
        success: function(response) {
            if(response.data && response.data.length > 0) {
                let tableHtml = '';
                response.data.forEach(function(row) {
                    tableHtml += `
                        <tr>
                            <td class="ps-3">
                                <input type="checkbox" class="form-check-input brand-check" value="${row.id}">
                            </td>
                            <td>${row.name}</td>
                            <td>${(row.active == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td>
                        </tr>
                    `;
                });
                $("#brandBody").html(tableHtml);
                $("#brandTableHead").fadeIn();
                $("#pagination-status, .pagination").fadeIn();
                
                // Generate pagination HTML
                let paginationHtml = '';
                let total = response.total_rows;
                let totalPages = Math.ceil(total / 10);
                
                if (totalPages > 1) {
                    // Previous button
                    paginationHtml += `
                        <li class="page-item ${page <= 1 ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="loadBrandTable(${page - 1}, '${search}')">
                                <i class="ti ti-chevron-left"></i>
                            </a>
                        </li>
                    `;
                    
                    // Page numbers
                    for(let i = 1; i <= totalPages; i++) {
                        paginationHtml += `
                            <li class="page-item ${i === parseInt(page) ? 'active' : ''}">
                                <a class="page-link" href="javascript:void(0);" onclick="loadBrandTable(${i}, '${search}')">${i}</a>
                            </li>
                        `;
                    }
                    
                    // Next button
                    paginationHtml += `
                        <li class="page-item ${page >= totalPages ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="loadBrandTable(${page + 1}, '${search}')">
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </li>
                    `;
                    
                    // Update pagination and status
                    $(".pagination").html(paginationHtml).fadeIn();
                } else {
                    $(".pagination").hide();
                }
                
                let start = ((page - 1) * 10) + 1;
                let end = Math.min(start + 10 - 1, total);
                $("#pagination-status").html(`Showing ${start} to ${end} of ${total} brands`).fadeIn();
            } else {
                $("#brandBody").html(`
                    <tr>
                        <td colspan="3" class="text-center">No brands found</td>
                    </tr>
                `);
                $(".pagination").hide();
                $("#pagination-status").html('Showing 0 to 0 of 0 brands').fadeIn();
            }
            
            $("#showBrandsBtn").hide();
            
            // Reset checkboxes and action button
            $('#selectAll').prop('checked', false);
            $('.brand-actions').removeClass('show').addClass('d-none');
            $('.edit-item').addClass('d-none');
        },
        error: function() {
            $("#brandBody").html(`
                <tr>
                    <td colspan="3" class="text-center text-danger">Failed to load brands</td>
                </tr>
            `);
            $("#pagination-status, .pagination").hide();
        }
    });
}

$(document).ready(function() {
    $("#mainBrandsNav").addClass('active');
    $("#manageBrandsNav").addClass('active');

    // Initially hide table body and footer
    $("#brandBody").html('');

    // Show brands button click handler
    $("#showBrandsBtn").click(function() {
        loadBrandTable();
        $(this).hide();
    });

    // Search functionality
    $('#searchBox').on('keyup', function() {
        var searchText = $(this).val();
        loadBrandTable(1, searchText);
    });

    // Check all functionality
    $("#selectAll").click(function() {
        var isChecked = $(this).prop('checked');
        $('.brand-check').prop('checked', isChecked);
        toggleBrandActions();
    });

    // Individual checkbox change
    $(document).on('change', '.brand-check', function() {
        var totalCheckboxes = $('.brand-check').length;
        var checkedCheckboxes = $('.brand-check:checked').length;
        
        // Update select all checkbox
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        
        // Toggle actions button
        toggleBrandActions();
    });

    // Handle dropdown menu items
    $(document).on('click', '.edit-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let selectedBrand = $(".brand-check:checked").val();
        console.log("Selected brand ID:", selectedBrand);
        
        if(selectedBrand) {
            // Fetch brand details and show edit modal
            $.ajax({
                url: base_url + "brands/fetchBrandDataById",
                type: 'POST',
                data: { brand_id: selectedBrand },
                dataType: 'json',
                success: function(response) {
                    $('#edit_brand_id').val(response.id);
                    $('#edit_brand_name').val(response.name);
                    $('#edit_active').val(response.active);
                    $('#editBrandModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching brand data:", xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let selectedBrands = [];
        let brandNames = [];
        
        $(".brand-check:checked").each(function() {
            selectedBrands.push($(this).val());
            // Get the brand name from the row
            let brandName = $(this).closest('tr').find('td:eq(1)').text();
            brandNames.push(brandName);
        });
        
        if(selectedBrands.length > 0) {
            $('#removeBrandIds').val(JSON.stringify(selectedBrands));
            
            // Update modal title and message based on selection count
            if(selectedBrands.length === 1) {
                // Single brand deletion
                $("#deleteBrandTitle").html('<i class="ti ti-trash me-2"></i>Delete Brand');
                $("#deleteBrandMessage").html(`Are you sure you want to delete brand <strong>${brandNames[0]}</strong>? This action cannot be undone.`);
            } else {
                // Multiple brand deletion
                $("#deleteBrandTitle").html('<i class="ti ti-trash me-2"></i>Delete Multiple Brands');
                $("#deleteBrandMessage").html(`Are you sure you want to delete <strong>${selectedBrands.length}</strong> selected brands? This action cannot be undone.`);
            }
            
            $('#removeBrandModal').modal('show');
        }
    });

    // Add brand form submission
    $("#addBrandForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + "brands/create",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#addBrandModal').modal('hide');
                    $('#addBrandForm')[0].reset();
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                    loadBrandTable();
                }
                autoDismissMessages();
            }
        });
    });

    // Edit brand form submission
    $("#editBrandForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + "brands/update",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#editBrandModal').modal('hide');
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                    loadBrandTable();
                }
                autoDismissMessages();
            }
        });
    });

    // Remove brand form submission
    $("#removeBrandForm").submit(function(e) {
        e.preventDefault();
        let brandIds = JSON.parse($('#removeBrandIds').val());
        $.ajax({
            url: base_url + "brands/remove",
            type: "POST",
            data: { brand_id: brandIds },
            dataType: "json",
            success: function(response) {
                // Close the modal regardless of success or failure
                $('#removeBrandModal').modal('hide');
                
                if(response.success) {
                    // Success message
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                } else {
                    // Error message
                    $('#messages').html(`
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:danger-triangle-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                }
                
                // Reload the table to reflect changes
                loadBrandTable();
                
                // Auto dismiss messages
                autoDismissMessages();
            },
            error: function(xhr, status, error) {
                // Handle any AJAX errors
                $('#removeBrandModal').modal('hide');
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        <iconify-icon icon="solar:danger-triangle-line-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">An error occurred while processing your request.</div>
                    </div>
                `);
                
                // Auto dismiss messages
                autoDismissMessages();
            }
        });
    });
});

// Function to toggle brand actions visibility
function toggleBrandActions() {
    var checkedCount = $('.brand-check:checked').length;
    if (checkedCount > 0) {
        $('.brand-actions').removeClass('d-none').addClass('show');
        
        // Show/hide edit option based on selection count
        if(checkedCount === 1) {
            $('.edit-item').removeClass('d-none');
        } else {
            $('.edit-item').addClass('d-none');
        }
    } else {
        $('.brand-actions').removeClass('show').addClass('d-none');
    }
}
</script> 