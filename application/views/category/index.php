<style>
.category-actions {
    display: none;
}
.category-actions.show {
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
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Categories</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Manage Categories</li>
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
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a category" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewCategory', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showCategoriesBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Categories</button>
                                <?php endif; ?>
                                <?php if(in_array('createCategory', $user_permission)): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Category</button>
                                <?php endif; ?>
                                <?php if(in_array('updateCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
                                    <div class="dropdown category-actions d-none">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-settings me-1"></i> Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if(in_array('updateCategory', $user_permission)): ?>
                                                <li>
                                                    <a class="dropdown-item edit-item d-none" href="javascript:void(0);">
                                                        <i class="ti ti-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(in_array('deleteCategory', $user_permission)): ?>
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
                        <table class="table table-hover text-nowrap mb-0" id="categoryTable">
                            <thead class="bg-dark-subtle" id="categoryTableHead">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="categoryBody">
                                <!-- Categories will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted" id="pagination-status" style="display: none;">
                                Showing 0 to 0 of 0 categories
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

<?php if(in_array('createCategory', $user_permission)): ?>
<!-- Add Category Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter category name">
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
                    <button type="submit" class="btn btn-info">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(in_array('updateCategory', $user_permission)): ?>
<!-- Edit Category Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_category_id" name="category_id">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_category_name" name="category_name" placeholder="Enter category name">
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
                    <button type="submit" class="btn btn-info">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(in_array('deleteCategory', $user_permission)): ?>
<!-- Delete Modal -->
<div id="removeModal" class="modal fade" tabindex="-1" aria-labelledby="removeModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title text-danger" id="deleteCategoryTitle">
                    <i class="ti ti-trash me-2"></i>Delete Category
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" id="removeForm">
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                <i class="ti ti-trash fs-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted mb-4">
                        <p id="deleteCategoryMessage" class="fs-5 mb-0">Are you sure you want to delete the selected category(s)? This action cannot be undone.</p>
                    </div>
                    <input type="hidden" id="removeCategoryIds">
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

<?php endif; ?>

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

// Function to load category table
function loadCategoryTable(page = 1, search = '') {
    $.ajax({
        url: base_url + "category/fetchCategoryData",
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
                    // Use object notation instead of array notation
                    tableHtml += `
                        <tr>
                            <td class="ps-3">
                                <input type="checkbox" class="form-check-input category-check" value="${row.id}">
                            </td>
                            <td>${row.name}</td>
                            <td>${row.status}</td>
                        </tr>
                    `;
                });
                $("#categoryBody").html(tableHtml);
                $("#categoryTableHead").fadeIn();
                
                // Update pagination status
                const start = (page - 1) * response.pagination.limit + 1;
                const end = Math.min(start + response.data.length - 1, response.pagination.total_records);
                
                $("#pagination-status").html(`
                    Showing ${start} to ${end} of ${response.pagination.total_records} categories
                `).fadeIn();

                // Generate pagination
                let paginationHtml = '';
                
                // Always show pagination
                paginationHtml += `
                    <li class="page-item ${page <= 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadCategoryTable(${page - 1}, '${search}')">
                            Previous
                        </a>
                    </li>
                `;
                
                // Page numbers
                for(let i = 1; i <= response.pagination.total_pages; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === parseInt(page) ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="loadCategoryTable(${i}, '${search}')">${i}</a>
                        </li>
                    `;
                }
                
                // Next button
                paginationHtml += `
                    <li class="page-item ${page >= response.pagination.total_pages ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadCategoryTable(${page + 1}, '${search}')">
                            Next
                        </a>
                    </li>
                `;
                
                $(".pagination").html(paginationHtml).fadeIn();
            } else {
                $("#categoryBody").html(`
                    <tr>
                        <td colspan="3" class="text-center">No categories found</td>
                    </tr>
                `);
                $("#pagination-status").html('Showing 0 to 0 of 0 categories').fadeIn();
                $(".pagination").html('').fadeIn();
            }
            
            $("#showCategoriesBtn").hide();
            
            // Reset checkboxes and action button
            $('#selectAll').prop('checked', false);
            $('.category-actions').removeClass('show').addClass('d-none');
            $('.edit-item').addClass('d-none');
        },
        error: function() {
            $("#categoryBody").html(`
                <tr>
                    <td colspan="3" class="text-center text-danger">Failed to load categories</td>
                </tr>
            `);
            $("#pagination-status").html('Showing 0 to 0 of 0 categories').fadeIn();
            $(".pagination").html('').fadeIn();
        }
    });
}

$(document).ready(function() {
    $("#categoryNav").addClass('active');

    // Initially hide table body
    $("#categoryBody").html('');

    // Show categories button click handler
    $("#showCategoriesBtn").click(function() {
        loadCategoryTable();
        $(this).hide();
    });

    // Search functionality
    $('#searchBox').on('keyup', function() {
        var searchText = $(this).val();
        loadCategoryTable(1, searchText);
    });

    // Check all functionality
    $("#selectAll").click(function() {
        var isChecked = $(this).prop('checked');
        $('.category-check').prop('checked', isChecked);
        toggleCategoryActions();
    });

    // Individual checkbox change
    $(document).on('change', '.category-check', function() {
        var totalCheckboxes = $('.category-check').length;
        var checkedCheckboxes = $('.category-check:checked').length;
        
        // Update select all checkbox
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        
        // Toggle actions button
        toggleCategoryActions();
    });

    // Handle dropdown menu items
    $(document).on('click', '.edit-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let selectedCategory = $(".category-check:checked").val();
        if(selectedCategory) {
            // Fetch category details and show edit modal
            $.ajax({
                url: base_url + "category/fetchCategoryDataById",
                type: 'POST',
                data: { category_id: selectedCategory },
                dataType: 'json',
                success: function(response) {
                    $('#edit_category_id').val(response.id);
                    $('#edit_category_name').val(response.name);
                    $('#edit_active').val(response.active);
                    $('#editModal').modal('show');
                }
            });
        }
    });

    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let selectedCategories = [];
        let categoryNames = [];
        
        $(".category-check:checked").each(function() {
            selectedCategories.push($(this).val());
            // Get the category name from the row
            let categoryName = $(this).closest('tr').find('td:eq(1)').text();
            categoryNames.push(categoryName);
        });
        
        if(selectedCategories.length > 0) {
            $('#removeCategoryIds').val(JSON.stringify(selectedCategories));
            
            // Update modal title and message based on selection count
            if(selectedCategories.length === 1) {
                // Single category deletion
                $("#deleteCategoryTitle").html('<i class="ti ti-trash me-2"></i>Delete Category');
                $("#deleteCategoryMessage").html(`Are you sure you want to delete category <strong>${categoryNames[0]}</strong>? This action cannot be undone.`);
            } else {
                // Multiple category deletion
                $("#deleteCategoryTitle").html('<i class="ti ti-trash me-2"></i>Delete Multiple Categories');
                $("#deleteCategoryMessage").html(`Are you sure you want to delete <strong>${selectedCategories.length}</strong> selected categories? This action cannot be undone.`);
            }
            
            $('#removeModal').modal('show');
        }
    });

    // Add category form submission
    $("#createForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + "category/create",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#addModal').modal('hide');
                    $('#createForm')[0].reset();
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                    loadCategoryTable();
                }
                autoDismissMessages();
            }
        });
    });

    // Edit category form submission
    $("#updateForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: base_url + "category/update",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#editModal').modal('hide');
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                    loadCategoryTable();
                }
                autoDismissMessages();
            }
        });
    });

    // Remove category form submission
    $("#removeForm").submit(function(e) {
        e.preventDefault();
        let categoryIds = JSON.parse($('#removeCategoryIds').val());
        $.ajax({
            url: base_url + "category/remove",
            type: "POST",
            data: { category_id: categoryIds },
            dataType: "json",
            success: function(response) {
                // Close the modal regardless of success or failure
                $('#removeModal').modal('hide');
                
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
                loadCategoryTable();
                
                // Auto dismiss messages
                autoDismissMessages();
            },
            error: function(xhr, status, error) {
                // Handle any AJAX errors
                $('#removeModal').modal('hide');
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

    // Handle pagination clicks
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if(page > 0) {
            loadCategoryTable(page, $('#searchBox').val());
        }
    });
});

// Function to toggle category actions visibility
function toggleCategoryActions() {
    var checkedCount = $('.category-check:checked').length;
    if (checkedCount > 0) {
        $('.category-actions').removeClass('d-none').addClass('show');
        
        // Show/hide edit option based on selection count
        if(checkedCount === 1) {
            $('.edit-item').removeClass('d-none');
        } else {
            $('.edit-item').addClass('d-none');
        }
    } else {
        $('.category-actions').removeClass('show').addClass('d-none');
    }
}
</script>