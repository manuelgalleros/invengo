<!-- Content Wrapper. Contains page content -->
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Orders</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Orders</li>
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
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for an order" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewOrder', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showOrdersBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Orders</button>
                                <?php endif; ?>
                                <?php if(in_array('createOrder', $user_permission)): ?>
                                    <a href="<?php echo base_url('orders/create') ?>" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Create New Order</a>
                                <?php endif; ?>
                                <?php if(in_array('viewArchivedOrder', $user_permission)): ?>
                                    <a href="<?php echo base_url('orders/archive') ?>" class="btn btn-soft-warning"><i class="ti ti-archive me-1"></i> View Archives</a>
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
                                                <a class="dropdown-item modify-item d-flex align-items-center" href="#"><i class="ti ti-edit me-2"></i> Edit</a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(in_array('deleteOrder', $user_permission)): ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center text-warning" href="#" onclick="archiveSelectedOrders(); return false;">
                                                    <i class="ti ti-archive me-2"></i> Archive
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
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
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Orders will be loaded here dynamically -->
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

<?php if(in_array('deleteOrder', $user_permission)): ?>
<!-- remove order modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title text-danger" id="removeModalTitle">
                    <i class="ti ti-trash me-2"></i>Delete Order
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
                    <div id="deleteModalMessageContainer" class="text-muted mb-4">
                        <p id="removeModalMessage" class="fs-5 mb-0">Are you sure you want to delete this order?</p>
                        <p class="mt-2 mb-0">This action cannot be undone.</p>
                    </div>
                    <input type="hidden" id="removeOrderIds">
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

<!-- Edit Order Modal -->
<div id="editOrderModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form id="editOrderForm">
                    <input type="hidden" id="edit_order_id" name="order_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_payment_method" id="edit_paymentCash" value="Cash">
                                            <label class="form-check-label" for="edit_paymentCash">Cash</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_payment_method" id="edit_paymentCard" value="Credit/Debit Card">
                                            <label class="form-check-label" for="edit_paymentCard">Credit/Debit Card</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_payment_method" id="edit_paymentBank" value="Bank Transfer">
                                            <label class="form-check-label" for="edit_paymentBank">Bank Transfer</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_payment_method" id="edit_paymentGcash" value="GCash">
                                            <label class="form-check-label" for="edit_paymentGcash">GCash</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edit_payment_method" id="edit_paymentMaya" value="Maya">
                                            <label class="form-check-label" for="edit_paymentMaya">Maya</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_paid_status" class="form-label">Payment Status</label>
                                <select class="form-select" id="edit_paid_status" name="edit_paid_status">
                                    <option value="1">Paid</option>
                                    <option value="0">Unpaid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change the remove modal to archive modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="archiveModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title text-warning" id="archiveModalTitle">
                    <i class="ti ti-archive me-2"></i>Archive Order
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" id="archiveForm">
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="ti ti-archive fs-24"></i>
                            </div>
                        </div>
                    </div>
                    <div id="archiveModalMessageContainer" class="text-muted mb-4">
                        <p id="archiveModalMessage" class="fs-5 mb-0">Are you sure you want to archive this order?</p>
                        <p class="mt-2 mb-0">Archived orders can be viewed in the Archives section.</p>
                    </div>
                    <input type="hidden" id="archiveOrderIds">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-archive me-1"></i>Archive
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
    $("#manageOrdersNav").addClass('active');

    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Initially hide only the table body and footer
    $("#manageTable tbody").html('');
    $("#productFooter").hide();

    // Show orders button click handler
    $("#showOrdersBtn").click(function() {
        loadOrderTable();
        $(this).hide();
    });

    // Search functionality
    $('#searchBox').on('keyup', function() {
        var searchText = $(this).val();
        loadOrderTable(1, searchText);
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
            loadOrderTable(page, $('#searchBox').val());
        }
    });

    // Handle dropdown menu items
    $(document).on('click', '.dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        let action = $(this).text().trim();
        
        if(action === 'Edit') {
            let selectedOrder = $(".order-check:checked").val();
            if(selectedOrder) {
                // Fetch order details
                $.ajax({
                    url: base_url + "orders/get_order",
                    type: 'POST',
                    data: { order_id: selectedOrder },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            let order = response.data;
                            // Populate the edit form with order data
                            $('#edit_order_id').val(order.id);
                            
                            // Reset all radio buttons
                            $('input[name="edit_payment_method"]').prop('checked', false);
                            
                            // Check the appropriate payment method radio
                            if(order.payment_method) {
                                $(`#edit_payment${order.payment_method}`).prop('checked', true);
                            }
                            
                            $('#edit_paid_status').val(order.paid_status).trigger('change');
                            
                            // Show the edit modal
                            $('#editOrderModal').modal('show');
                        } else {
                            $('#messages').html(`
                                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">Failed to load order details</div>
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#messages').html(`
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">Error loading order details</div>
                            </div>
                        `);
                    }
                });
            }
        } else if(action === 'Archive') {
            archiveSelectedOrders();
        } else if(action === 'Print Receipt') {
            viewReceipt();
        }
    });
});

// Function to toggle order actions visibility
function toggleOrderActions() {
    var checkedCount = $('.order-check:checked').length;
    if (checkedCount > 0) {
        $('.order-actions').show();
        
        // Hide or show Print Receipt and Edit based on selection count
        if (checkedCount === 1) {
            // Show Print Receipt and Edit options when exactly one order is selected
            $('.dropdown-menu a:contains("Print Receipt")').parent().show();
            $('.dropdown-menu a:contains("Edit")').parent().show();
        } else {
            // Hide Print Receipt and Edit options when multiple orders are selected
            $('.dropdown-menu a:contains("Print Receipt")').parent().hide();
            $('.dropdown-menu a:contains("Edit")').parent().hide();
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

// Function to archive selected orders
function archiveSelectedOrders() {
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
            // Single order archiving
            $("#archiveModalTitle").html('<i class="ti ti-archive me-2"></i>Archive Order');
            $("#archiveModalMessage").html(`Are you sure you want to archive order <strong>${orderNumbers[0]}</strong>?`);
        } else {
            // Multiple order archiving
            $("#archiveModalTitle").html('<i class="ti ti-archive me-2"></i>Archive Multiple Orders');
            $("#archiveModalMessage").html(`Are you sure you want to archive <strong>${orderIds.length}</strong> selected orders?`);
        }
        
        // Set the order IDs to archive
        $("#archiveOrderIds").val(JSON.stringify(orderIds));
        
        // Show the modal
        $("#archiveModal").modal("show");
    } else {
        alert('Please select at least one order to archive');
    }
}

// Function to load the order table
function loadOrderTable(page = 1, search = '') {
    $.ajax({
        url: base_url + "orders/fetchOrdersData",
        type: "GET",
        data: { 
            page: page,
            search: search
        },
        dataType: "json",
        success: function (response) {
            let html = '';
            if (response && response.data && response.data.length > 0) {
                response.data.forEach(function(order) {
                    html += `<tr>
                        <td class="ps-3"><input type="checkbox" class="form-check-input order-check" value="${order.id}"></td>
                        <td>${order.order_no}</td>
                        <td>${order.date_time}</td>
                        <td>${order.total_products}</td>
                        <td>₱${parseFloat(order.net_amount).toFixed(2)}</td>
                        <td>${order.payment_method ? 
                          order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1) : 
                          'N/A'}</td>
                        <td>${order.user_name ? order.user_name : 'N/A'}</td>
                        <td>
                            ${order.paid_status == 1 ? 
                                '<span class="badge badge-outline-success">Completed</span>' : 
                                '<span class="badge badge-outline-warning">Pending</span>'
                            }
                        </td>
                    </tr>`;
                });
            } else {
                html = `<tr><td colspan="8" class="text-center">No orders found</td></tr>`;
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
                        <td colspan="8" class="text-center">No orders found</td>
                    </tr>
                `);
                $(".card-footer .text-muted").html('Showing 0 to 0 of 0 orders').fadeIn();
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
                    <td colspan="8" class="text-center text-danger">
                        <div class="d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            Failed to load orders
                        </div>
                    </td>
                </tr>
            `);
        }
    });
}

// Handle delete request
$('#removeForm').on('submit', function(e) {
    e.preventDefault();
    var orderIds = JSON.parse($('#removeOrderIds').val());
    
    $.ajax({
        url: base_url + "orders/remove",
        type: 'POST',
        data: { order_id: orderIds },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#removeModal').modal('hide');
                
                // Create success message based on response data
                let successMessage = '';
                if (response.order_no) {
                    // Single order deletion
                    successMessage = `Order <strong>${response.order_no}</strong> was successfully deleted`;
                } else if (response.order_count) {
                    // Multiple order deletion
                    successMessage = `<strong>${response.order_count}</strong> orders were successfully deleted`;
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
                loadOrderTable();
            } else {
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${response.messages}</div>
                    </div>
                `);
                autoDismissMessages();
            }
        },
        error: function() {
            $('#messages').html(`
                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Error deleting order(s). Please try again.</div>
                </div>
            `);
            autoDismissMessages();
        }
    });
});

// Handle order update
$("#editOrderForm").submit(function(e) {
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        url: base_url + "orders/update_ajax",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if(response.success) {
                $('#editOrderModal').modal('hide');
                $('#messages').html(`
                    <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${response.messages}</div>
                    </div>
                `);
                autoDismissMessages();
                loadOrderTable();
            } else {
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${response.messages}</div>
                    </div>
                `);
                autoDismissMessages();
            }
        },
        error: function() {
            $('#messages').html(`
                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Error updating order. Please try again.</div>
                </div>
            `);
            autoDismissMessages();
        }
    });
});

// Add archive form submit handler
$('#archiveForm').on('submit', function(e) {
    e.preventDefault();
    var orderIds = JSON.parse($('#archiveOrderIds').val());
    
    $.ajax({
        url: base_url + "orders/archive",
        type: 'POST',
        data: { order_id: orderIds },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#archiveModal').modal('hide');
                
                // Create success message based on response data
                let successMessage = '';
                if (response.order_no) {
                    // Single order archiving
                    successMessage = `Order <strong>${response.order_no}</strong> was successfully archived`;
                } else if (response.order_count) {
                    // Multiple order archiving
                    successMessage = `<strong>${response.order_count}</strong> orders were successfully archived`;
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
                loadOrderTable();
            } else {
                // Check if the message indicates missing columns
                if (response.messages && response.messages.includes('not available')) {
                    $('#archiveModal').modal('hide');
                    $('#messages').html(`
                        <div class="alert alert-warning text-bg-warning alert-dismissible d-flex align-items-center mb-3" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.messages}</div>
                        </div>
                        <div class="alert alert-info text-bg-info d-flex align-items-center" role="alert">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="flex-grow-1">Run the database migration to enable archiving.</div>
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
        error: function(xhr, status, error) {
            console.error('Archive AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            
            try {
                // Try to parse response as JSON
                var errorResponse = JSON.parse(xhr.responseText);
                var errorMessage = errorResponse.messages || 'Error archiving order(s). Please try again.';
                
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${errorMessage}</div>
                    </div>
                `);
            } catch (e) {
                // If not valid JSON, use the default error message
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">Error archiving order(s). Please try again.</div>
                    </div>
                `);
            }
            
            // Close the modal even on error
            $('#archiveModal').modal('hide');
            autoDismissMessages();
        }
    });
});
</script>
