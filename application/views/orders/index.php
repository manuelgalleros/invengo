<!-- Content Wrapper. Contains page content -->
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Orders</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
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
                                    <a href="<?php echo base_url('orders/create') ?>" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Order</a>
                                <?php endif; ?>
                                <div class="dropdown order-actions" style="display: none !important;">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-settings me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if(in_array('viewOrder', $user_permission)): ?>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="viewSelectedOrders(); return false;">
                                                    <i class="ti ti-eye me-2"></i> View
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
                                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="removeSelectedOrders(); return false;">
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
                        <table id="manageTable" class="table table-hover text-nowrap mb-0">
                            <thead class="bg-dark-subtle" id="orderTableHead">
                                <tr>
                                    <th class="ps-3"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                                    <th>Order No</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone</th>
                                    <th>Order Date & Time</th>
                                    <th>Total Products</th>
                                    <th>Total Amount</th>
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
</div>

<?php if(in_array('deleteOrder', $user_permission)): ?>
<!-- remove order modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete Order</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
                <div class="modal-body">
                    <p>Are you sure you want to delete this order?</p>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form id="editOrderForm">
                    <input type="hidden" id="edit_order_id" name="order_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="edit_customer_name" name="customer_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_customer_phone" class="form-label">Customer Phone</label>
                                <input type="text" class="form-control" id="edit_customer_phone" name="customer_phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_paid_status" class="form-label">Payment Status</label>
                                <select class="form-select" id="edit_paid_status" name="paid_status">
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

<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
var user_permission = <?php echo json_encode($user_permission); ?>;

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
                            $('#edit_customer_name').val(order.customer_name);
                            $('#edit_customer_phone').val(order.customer_phone);
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
        } else if(action === 'Delete') {
            removeSelectedOrders();
        } else if(action === 'View') {
            viewSelectedOrders();
        }
    });
});

// Function to toggle order actions visibility
function toggleOrderActions() {
    var checkedCount = $('.order-check:checked').length;
    if (checkedCount > 0) {
        $('.order-actions').show();
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
function viewSelectedOrders() {
    var orderIds = getSelectedOrderIds();
    if (orderIds.length === 1) {
        window.location.href = base_url + 'orders/printDiv/' + orderIds[0];
    } else {
        alert('Please select only one order to view');
    }
}

// Function to remove selected orders
function removeSelectedOrders() {
    var orderIds = getSelectedOrderIds();
    if (orderIds.length > 0) {
        if(confirm('Are you sure you want to delete the selected orders?')) {
            $.ajax({
                url: base_url + 'orders/remove',
                type: 'post',
                data: { order_id: orderIds },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $("#messages").html(`
                            <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">${response.messages}</div>
                            </div>
                        `);
                        loadOrderTable();
                    } else {
                        $("#messages").html(`
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">${response.messages}</div>
                            </div>
                        `);
                    }
                }
            });
        }
    } else {
        alert('Please select at least one order to delete');
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
                        <td>${order.bill_no}</td>
                        <td>${order.customer_name}</td>
                        <td>${order.customer_phone}</td>
                        <td>${order.date_time}</td>
                        <td>${order.total_products}</td>
                        <td>₱${parseFloat(order.net_amount).toFixed(2)}</td>
                        <td>
                            ${order.paid_status == 1 ? 
                                '<span class="badge bg-success-subtle text-success">Paid</span>' : 
                                '<span class="badge bg-danger-subtle text-danger">Unpaid</span>'
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
                // Update pagination status
                const start = (page - 1) * 10 + 1;
                const end = Math.min(start + response.data.length - 1, response.data.length);
                const total = response.data.length;
                
                $(".text-muted").html(`
                    Showing ${start} to ${end} of ${total} entries
                `).fadeIn();

                // Generate pagination
                let totalPages = Math.ceil(total / 10);
                let paginationHtml = '';
                
                // Always show pagination container
                $(".pagination").show();
                
                // Previous button
                paginationHtml += `
                    <li class="page-item ${page <= 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadOrderTable(${page - 1}, '${search}')">
                            Previous
                        </a>
                    </li>
                `;
                
                // Page numbers
                for(let i = 1; i <= totalPages; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === parseInt(page) ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="loadOrderTable(${i}, '${search}')">${i}</a>
                        </li>
                    `;
                }
                
                // Next button
                paginationHtml += `
                    <li class="page-item ${page >= totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadOrderTable(${page + 1}, '${search}')">
                            Next
                        </a>
                    </li>
                `;
                
                $(".pagination").html(paginationHtml);
                $("#productFooter").show();
            } else {
                $("#manageTable tbody").html(`
                    <tr>
                        <td colspan="8" class="text-center">No orders found</td>
                    </tr>
                `);
                $(".text-muted").html('Showing 0 to 0 of 0 entries').fadeIn();
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

// Handle order update
$("#editOrderForm").submit(function(e) {
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        url: base_url + "orders/update",
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
                loadOrderTable();
            } else {
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${response.messages}</div>
                    </div>
                `);
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
        }
    });
});
</script>
