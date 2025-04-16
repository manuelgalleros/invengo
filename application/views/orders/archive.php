<!-- Content Wrapper. Contains page content -->
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Archived Orders</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Archived Orders</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="messages"></div>

                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php elseif($this->session->flashdata('error')): ?>
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px;">
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for an archived order" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewOrder', $user_permission)): ?>
                                    <a href="<?php echo base_url('orders') ?>" class="btn btn-soft-info">
                                        <i class="ti ti-arrow-left me-1"></i> Back to Orders
                                    </a>
                                    <button type="button" class="btn btn-light" id="showArchivedOrdersBtn">
                                        <i class="ti ti-eye align-middle me-1 fs-18"></i> Show Archived Orders
                                    </button>
                                <?php endif; ?>
                                <div class="dropdown order-actions" style="display: none !important;">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-settings me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if(in_array('viewOrder', $user_permission)): ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="viewReceipt(); return false;">
                                                    <i class="ti ti-printer me-2"></i> Print Receipt
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(in_array('updateOrder', $user_permission)): ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center text-success" href="#" onclick="restoreSelectedOrders(); return false;">
                                                    <i class="ti ti-arrow-back-up me-2"></i> Restore
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add migration error message container -->
                    <div id="migrationErrorContainer" class="p-4 text-center" style="display: none;">
                        <div class="alert alert-warning" role="alert">
                            <i class="ti ti-alert-triangle me-2 fs-18"></i>
                            <span id="migrationErrorMessage">Archive feature is not available. The required database columns are missing.</span>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('migration/add_archive_columns') ?>" class="btn btn-warning">
                                <i class="ti ti-database me-1"></i> Run Migration to Fix
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="manageTable" class="table table-hover text-nowrap mb-0">
                            <thead class="bg-dark-subtle" id="orderTableHead">
                                <tr>
                                    <th class="ps-3"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th>Order No</th>
                                    <th>Order Date & Time</th>
                                    <th>Total Products</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Processed By</th>
                                    <th>Status</th>
                                    <th>Archived Date</th>
                                    <th>Archived By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Archived Orders will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
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

<!-- Restore Order Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="restoreModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title text-success" id="restoreModalTitle">
                    <i class="ti ti-arrow-back-up me-2"></i>Restore Order
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" id="restoreForm">
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="ti ti-arrow-back-up fs-24"></i>
                            </div>
                        </div>
                    </div>
                    <div id="restoreModalMessageContainer" class="text-muted mb-4">
                        <p id="restoreModalMessage" class="fs-5 mb-0">Are you sure you want to restore this order?</p>
                        <p class="mt-2 mb-0">The order will be moved back to active orders.</p>
                    </div>
                    <input type="hidden" id="restoreOrderIds">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-arrow-back-up me-1"></i>Restore
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
var user_permission = <?php echo json_encode($user_permission); ?>;

// Function to auto-dismiss alert messages
function autoDismissMessages() {
    setTimeout(function() {
        $(".alert").fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000); // 5 seconds
}

$(document).ready(function() {
    $("#mainOrdersNav").addClass('active');
    $("#archivedOrdersNav").addClass('active');

    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Initially hide only the table body and footer
    $("#manageTable tbody").html('');
    $("#productFooter").hide();

    // Show orders button click handler
    $("#showArchivedOrdersBtn").click(function() {
        loadArchivedOrderTable();
        $(this).hide();
    });

    // Search functionality
    $('#searchBox').on('keyup', function() {
        var searchText = $(this).val();
        loadArchivedOrderTable(1, searchText);
    });

    // Check all functionality
    $("#checkAll").click(function() {
        $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        toggleOrderActions();
    });

    // Individual checkbox change
    $(document).on('change', '.order-check', function() {
        if ($('.order-check:checked').length === $('.order-check').length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
        toggleOrderActions();
    });
    
    // Handle pagination clicks
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page) {
            loadArchivedOrderTable(page, $('#searchBox').val());
        }
    });

    // Handle dropdown menu items
    $(document).on('click', '.dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        let action = $(this).text().trim();
        
        if(action === 'Print Receipt') {
            viewReceipt();
        } else if(action === 'Restore') {
            restoreSelectedOrders();
        }
    });
});

// Function to toggle order actions visibility
function toggleOrderActions() {
    var checkedCount = $('.order-check:checked').length;
    if (checkedCount > 0) {
        $('.order-actions').show();
        
        // Hide or show Print Receipt based on selection count
        if (checkedCount === 1) {
            // Show Print Receipt when exactly one order is selected
            $('.dropdown-menu a:contains("Print Receipt")').parent().show();
        } else {
            // Hide Print Receipt when multiple orders are selected
            $('.dropdown-menu a:contains("Print Receipt")').parent().hide();
        }
    } else {
        $('.order-actions').hide();
    }
}

// Function to get selected order IDs
function getSelectedOrderIds() {
    var orderIds = [];
    $('.order-check:checked').each(function() {
        orderIds.push($(this).val());
    });
    return orderIds;
}

// Function to view selected orders
function viewReceipt() {
    var orderIds = getSelectedOrderIds();
    if (orderIds.length === 1) {
        // Get the order number for the selected order
        var selectedRow = $('.order-check:checked').closest('tr');
        var orderNo = selectedRow.find('td:eq(1)').text(); // Get the order number from the second column
        window.location.href = base_url + 'orders/receipt/' + orderNo;
    } else {
        alert('Please select only one order to view');
    }
}

// Function to restore selected orders
function restoreSelectedOrders() {
    var orderIds = getSelectedOrderIds();
    if (orderIds.length > 0) {
        // Get order numbers for the selected orders
        let orderNumbers = [];
        $(".order-check:checked").each(function() {
            let orderNo = $(this).closest('tr').find('td:eq(1)').text().trim();
            orderNumbers.push(orderNo);
        });
        
        // Update modal title and message based on selection count
        if (orderIds.length === 1) {
            // Single order restoration
            $("#restoreModalTitle").html('<i class="ti ti-arrow-back-up me-2"></i>Restore Order');
            $("#restoreModalMessage").html(`Are you sure you want to restore order <strong>${orderNumbers[0]}</strong>?`);
        } else {
            // Multiple order restoration
            $("#restoreModalTitle").html('<i class="ti ti-arrow-back-up me-2"></i>Restore Multiple Orders');
            $("#restoreModalMessage").html(`Are you sure you want to restore <strong>${orderIds.length}</strong> selected orders?`);
        }
        
        // Set the order IDs to restore
        $("#restoreOrderIds").val(JSON.stringify(orderIds));
        
        // Show the modal
        $("#restoreModal").modal("show");
    } else {
        alert('Please select at least one order to restore');
    }
}

// Function to load the archived order table
function loadArchivedOrderTable(page = 1, search = '') {
    $.ajax({
        url: base_url + "orders/fetchArchivedOrdersData",
        type: "GET",
        data: { 
            page: page,
            search: search
        },
        dataType: "json",
        success: function (response) {
            // Check if there's an error message (migration required)
            if (response.error) {
                // Hide the table and show the error message
                $("#manageTable").hide();
                $("#migrationErrorContainer").show();
                $("#migrationErrorMessage").text(response.message);
                $(".card-footer").hide();
                return;
            }
            
            // No error, proceed normally
            $("#migrationErrorContainer").hide();
            $("#manageTable").show();
            $(".card-footer").show();
            
            let html = '';
            if (response && response.data && response.data.length > 0) {
                response.data.forEach(function(order) {
                    html += `<tr>
                        <td class="ps-3"><input type="checkbox" class="form-check-input order-check" value="${order.id}"></td>
                        <td>${order.order_no}</td>
                        <td>${order.date_time}</td>
                        <td>${order.total_products}</td>
                        <td>₱${parseFloat(order.net_amount).toFixed(2)}</td>
                        <td>${order.payment_method ? order.payment_method : 'N/A'}</td>
                        <td>${order.user_name ? order.user_name : 'N/A'}</td>
                        <td>
                            ${order.paid_status == 1 ? 
                                '<span class="badge badge-outline-success">Completed</span>' : 
                                '<span class="badge badge-outline-warning">Pending</span>'
                            }
                        </td>
                        <td>${order.archived_at}</td>
                        <td>${order.archived_by}</td>
                    </tr>`;
                });
            } else {
                html = `<tr><td colspan="10" class="text-center">No archived orders found</td></tr>`;
            }
            
            $("#manageTable tbody").html(html);
            
            // Update pagination if provided
            if (response && response.data && response.data.length > 0) {
                // Update pagination status using the range_info from server
                if (response.range_info) {
                    $(".card-footer .text-muted").html(response.range_info).fadeIn();
                }
                
                // Use server-provided pagination if available
                if (response.pagination) {
                    $(".pagination").html(response.pagination);
                    $(".pagination").show();
                }
                
                $("#productFooter").show();
            } else {
                $("#manageTable tbody").html(`
                    <tr>
                        <td colspan="10" class="text-center">No archived orders found</td>
                    </tr>
                `);
                $(".card-footer .text-muted").html('Showing 0 to 0 of 0 archived orders').fadeIn();
                $(".pagination").html('');
                $("#productFooter").show();
            }
            
            // Reset checkboxes and actions
            $('#checkAll').prop('checked', false);
            $('.order-actions').hide();
        },
        error: function (xhr, status, error) {
            $("#manageTable tbody").html(`
                <tr>
                    <td colspan="10" class="text-center text-danger">
                        <div class="d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            Failed to load archived orders
                        </div>
                    </td>
                </tr>
            `);
        }
    });
}

// Handle restore request
$('#restoreForm').on('submit', function(e) {
    e.preventDefault();
    var orderIds = JSON.parse($('#restoreOrderIds').val());
    
    $.ajax({
        url: base_url + "orders/restore",
        type: 'POST',
        data: { order_id: orderIds },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#restoreModal').modal('hide');
                
                // Create success message based on response data
                let successMessage = '';
                if (response.order_no) {
                    // Single order restoration
                    successMessage = `Order <strong>${response.order_no}</strong> was successfully restored`;
                } else if (response.order_count) {
                    // Multiple order restoration
                    successMessage = `<strong>${response.order_count}</strong> orders were successfully restored`;
                } else {
                    // Fallback if response format changes
                    successMessage = response.messages;
                }
                
                $('#messages').html(`
                    <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${successMessage}</div>
                    </div>
                `);
                autoDismissMessages();
                
                // Refresh the table to show updated data
                loadArchivedOrderTable();
            } else {
                // Check if the message indicates missing columns
                if (response.messages && response.messages.includes('not available')) {
                    $('#restoreModal').modal('hide');
                    $('#messages').html(`
                        <div class="alert alert-warning text-bg-warning alert-dismissible d-flex align-items-center mb-3" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                        <div class="alert alert-info text-bg-info d-flex align-items-center" role="alert">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="flex-grow-1">Run the database migration to enable the restore feature.</div>
                            <a href="${base_url}migration/add_archive_columns" target="_blank" class="btn btn-sm btn-light ms-3">
                                <i class="ti ti-database me-1"></i> Run Migration
                            </a>
                        </div>
                    `);
                } else {
                    $('#messages').html(`
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                    `);
                }
                autoDismissMessages();
            }
        },
        error: function() {
            $('#messages').html(`
                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Error restoring order(s). Please try again.</div>
                </div>
            `);
            autoDismissMessages();
        }
    });
});
</script> 