<!-- Content Wrapper. Contains page content -->
<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Order</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
          <li class="breadcrumb-item active">Create New Order</li>
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
          
          if($success): ?>
            <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
              <div class="lh-1"><?php echo $success; ?></div>
            </div>
          <?php elseif($error): ?>
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
          <form role="form" action="<?php echo base_url('orders/create'); ?>" method="post" id="order-form">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="d-flex gap-3 mb-3">
                    <div class="border-end pe-3">
                      <div class="text-muted small">Date</div>
                      <div class="fw-medium" id="current-date"><?php 
                        date_default_timezone_set('Asia/Manila');
                        echo date('F j, Y'); 
                      ?></div>
                    </div>
                    <div>
                      <div class="text-muted small">Time (PHT)</div>
                      <div class="fw-medium" id="current-time"></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex flex-column flex-lg-row">
                <div class="flex-grow-1 pe-lg-3">
                  <!-- Payment Method Section -->
                  <div class="card border mb-3">
                    <div class="card-body">
                      <h5 class="card-title mb-3">Payment Method</h5>
                      <div class="row">
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="Cash" checked>
                            <label class="form-check-label" for="paymentCash">Cash</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentCard" value="Credit/Debit Card">
                            <label class="form-check-label" for="paymentCard">Credit/Debit Card</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentBank" value="Bank Transfer">
                            <label class="form-check-label" for="paymentBank">Bank Transfer</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentGcash" value="GCash">
                            <label class="form-check-label" for="paymentGcash">GCash</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymentMaya" value="Maya">
                            <label class="form-check-label" for="paymentMaya">Maya</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Products Table Section -->
                  <div class="table-responsive product-table-container mt-4">
                    <table class="table table-bordered table-hover" id="product_info_table">
                      <thead class="bg-dark-subtle">
                        <tr>
                          <th style="width:50%">Product</th>
                          <th style="width:20%">Quantity</th>
                          <th style="width:20%">Price</th>
                          <th style="width:10%">
                            <button type="button" id="add_row" class="btn btn-soft-info btn-sm">
                              <i class="ti ti-plus"></i>
                            </button>
                          </th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr id="row_1">
                          <td>
                            <select class="form-select select_group product" data-row-id="row_1" id="product_1" name="product[]" style="width:100%;" onchange="getProductData(1)" required>
                              <option value=""></option>
                              <?php foreach ($products as $k => $v): ?>
                                <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                              <?php endforeach ?>
                            </select>
                          </td>
                          <td>
                            <input type="text" name="qty[]" id="qty_1" class="form-control qty" required onkeyup="getTotal(1)">
                          </td>
                          <td>
                            <input type="text" name="rate[]" id="rate_1" class="form-control" disabled autocomplete="off">
                            <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
                            <input type="hidden" name="amount[]" id="amount_1" class="form-control" autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
                            <input type="hidden" name="product_image[]" id="product_image_1" class="form-control" autocomplete="off">
                          </td>
                          <td>
                            <button type="button" class="btn btn-soft-danger btn-sm" onclick="removeRow('1')">
                              <i class="ti ti-x"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                
                <!-- Cart Section (Spans Both Rows) -->
                <div class="cart-container mb-3 mb-lg-0">
                  <div class="card border sticky-top" style="top: 1rem; z-index: 100;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">
                        <i class="ti ti-shopping-cart me-1"></i> Cart Items
                      </h5>
                      <button type="button" id="clear-cart" class="btn btn-soft-danger btn-sm">
                        <i class="ti ti-trash me-1"></i> Clear All
                      </button>
                    </div>
                    <div class="card-body p-0">
                      <div id="cart-items-container" class="cart-container" style="max-height: 300px; overflow-y: auto;">
                        <div class="text-center p-4 text-muted" id="empty-cart-message">
                          <iconify-icon icon="solar:cart-large-minimalistic-outline" style="font-size: 48px;"></iconify-icon>
                          <p class="mt-2">No items in cart yet</p>
                        </div>
                        <div id="cart-items" class="list-group list-group-flush"></div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Gross Amount:</span>
                        <span class="fw-medium" id="cart-gross-amount">₱ 0.00</span>
                      </div>
                      <?php if($is_service_enabled == true): ?>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Service Charge (<?php echo $company_data['service_charge_value'] ?>%):</span>
                        <span class="fw-medium" id="cart-service-charge">₱ 0.00</span>
                      </div>
                      <?php endif; ?>
                      <?php if($is_vat_enabled == true): ?>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">VAT (<?php echo $company_data['vat_charge_value'] ?>%):</span>
                        <span class="fw-medium" id="cart-vat-charge">₱ 0.00</span>
                      </div>
                      <?php endif; ?>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Discount:</span>
                        <div class="input-group" style="width: 150px;">
                          <span class="input-group-text">₱</span>
                          <input type="text" class="form-control form-control-sm" id="discount" name="discount" placeholder="Amount" onkeyup="subAmount()" autocomplete="off">
                        </div>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Net Amount:</span>
                        <span class="fw-bold text-primary" id="cart-net-amount">₱ 0.00</span>
                      </div>
                      <input type="hidden" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge" name="service_charge" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                      <input type="hidden" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer border-top">
              <div class="text-end">
                <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                <button type="button" id="process_order_btn" class="btn btn-soft-info ms-2">Process Order</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      <!-- col-12 -->
    </div>
    <!-- /.row -->
  </div>
<!-- /.page-content -->

<!-- Cash Payment Modal -->
<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-labelledby="cashPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="cashPaymentModalLabel">Cash Payment</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="total_amount" class="form-label">Total Amount</label>
          <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="text" class="form-control" id="total_amount" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label for="cash_given" class="form-label">Cash Received</label>
          <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="number" class="form-control" id="cash_given" placeholder="Enter amount received">
          </div>
        </div>
        <div class="mb-3">
          <label for="change_amount" class="form-label">Change</label>
          <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="text" class="form-control" id="change_amount" readonly>
          </div>
        </div>
        <div id="cash-error" class="alert alert-danger text-bg-danger d-none" role="alert">
          <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
          <span>Cash amount is insufficient. Please provide enough cash.</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info" id="confirm_cash_payment">Confirm Payment</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    // Initialize Select2 with better modal handling
    function initializeSelect2() {
      $(".select_group").select2({
        width: 'resolve',
        dropdownAutoWidth: false
      });
      
      // Ensure search field gets focus when dropdown opens
      $(document).on('select2:open', function() {
        setTimeout(function() {
          $('.select2-search__field').first().focus();
        }, 100);
      });
    }
    
    // Initialize on page load
    initializeSelect2();
    
    // Create a MutationObserver to detect when new rows are added to the table
    const productTableObserver = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
          // Check if the added node is a TR element within the table
          for (let i = 0; i < mutation.addedNodes.length; i++) {
            const node = mutation.addedNodes[i];
            if (node.nodeName === 'TR' && $(node).find('.select_group').length > 0) {
              // Short delay to ensure the DOM is updated
              setTimeout(function() {
                initializeSelect2();
              }, 100);
              break;
            }
          }
        }
      });
    });
    
    // Start observing the target node for configured mutations
    productTableObserver.observe(document.querySelector('#product_info_table tbody'), {
      childList: true, // observe direct children
      subtree: false   // don't observe descendants
    });
    
    // Fix Select2 in Bootstrap modal issues
    $(document).on('shown.bs.modal', '.modal', function() {
      // Re-initialize Select2 when any modal is shown
      setTimeout(function() {
        initializeSelect2();
      }, 200);
    });
    
    // Reset z-index issues when modal is closed
    $(document).on('hidden.bs.modal', '.modal', function() {
      $('.select2-dropdown').remove();
    });
    
    // Philippine Time clock with seconds
    function updatePhilippineTime() {
      const now = new Date();
      // Convert to Philippine time (UTC+8)
      now.setTime(now.getTime() + (8 * 60 * 60 * 1000));
      
      let hours = now.getUTCHours();
      let minutes = now.getUTCMinutes();
      let seconds = now.getUTCSeconds();
      let ampm = hours >= 12 ? 'PM' : 'AM';
      
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0' + minutes : minutes;
      seconds = seconds < 10 ? '0' + seconds : seconds;
      
      const timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
      $("#current-time").text(timeString);
    }
    
    // Update the time immediately and then every second
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);
    
    // Format the date in a more readable format
    function formatDate() {
      const date = new Date();
      const options = { 
        timeZone: 'Asia/Manila',
        month: 'long', 
        day: 'numeric', 
        year: 'numeric' 
      };
      
      document.getElementById('current-date').textContent = date.toLocaleDateString('en-US', options);
    }
    
    formatDate();
    
    // Clear cart button
    $('#clear-cart').on('click', function() {
      // Remove all rows except the first one
      $("#product_info_table tbody tr:not(:first)").remove();
      
      // Clear the first row
      $("#product_1").val('').trigger('change.select2');
      $("#rate_1").val('');
      $("#rate_value_1").val('');
      $("#qty_1").val('');
      $("#amount_1").val('');
      $("#amount_value_1").val('');
      $("#product_image_1").val('');
      
      // Update totals
      subAmount();
      
      // Focus on the product select
      setTimeout(function() {
        $("#product_1").select2('open');
      }, 100);
    });
    
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
  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'">'+
                   '<td>'+ 
                    '<select class="form-select select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.id+'">'+value.name+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control qty" onkeyup="getTotal('+row_id+')"></td>'+
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control" disabled><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"><input type="hidden" name="amount[]" id="amount_'+row_id+'" class="form-control" autocomplete="off"><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control" autocomplete="off"><input type="hidden" name="product_image[]" id="product_image_'+row_id+'" class="form-control" autocomplete="off"></td>'+
                    '<td><button type="button" class="btn btn-soft-danger btn-sm" onclick="removeRow(\''+row_id+'\')"><i class="ti ti-x"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              // Initialize Select2 for the new row with proper handling
              $("#product_"+row_id).select2({
                width: 'resolve'
              });

              // Focus on the newly added select after a short delay
              setTimeout(function() {
                $("#product_"+row_id).select2('focus');
              }, 300);

          }
        });

      return false;
    });

    // Handle Enter key press on quantity field
    $(document).on('keydown', '.qty', function(e) {
      if(e.keyCode === 13) { // Enter key
        e.preventDefault();
        
        var row_id = $(this).closest('tr').attr('id');
        if(row_id) {
          row_id = row_id.substring(4);
          var product_id = $("#product_"+row_id).val();
          
          // Only proceed if product is selected
          if(product_id) {
            // Get current row count
            var count_table_tbody_tr = $("#product_info_table tbody tr").length;
            
            // Automatically add new row if this is the last row
            if(parseInt(row_id) === count_table_tbody_tr) {
              $("#add_row").trigger('click');
            }
            
            // Focus on the product select in the next row
            setTimeout(function() {
              var next_row_id = parseInt(row_id) + 1;
              $("#product_"+next_row_id).select2('open');
            }, 500);
          }
        }
      }
    });

    // Reset messages on page load
    function resetResponseMessages() {
      // Check if there's a stored message
      let storedMessage = sessionStorage.getItem('orderMessage');
      let storedMessageType = sessionStorage.getItem('orderMessageType');
      
      if (storedMessage && storedMessageType) {
        // Display the message
        let alertClass = storedMessageType === 'success' ? 'alert-success text-bg-success' : 'alert-danger text-bg-danger';
        let iconClass = storedMessageType === 'success' ? 'solar:check-read-line-duotone' : 'solar:danger-triangle-bold-duotone';
        
        $('#messages').html(`
          <div class="alert ${alertClass} alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            <iconify-icon icon="${iconClass}" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">${storedMessage}</div>
          </div>
        `);
        
        // Clear the stored message
        sessionStorage.removeItem('orderMessage');
        sessionStorage.removeItem('orderMessageType');
        
        // Initialize auto-dismiss
        initializeAutoDismissAlerts();
      }
    }
    
    // Call on page load
    resetResponseMessages();
    
    // Prevent default form submission
    $('#order-form').on('submit', function(e) {
      e.preventDefault();
      // Form will be submitted via our AJAX function
    });
    
    // Process Order Button Click
    $('#process_order_btn').on('click', function(e) {
      e.preventDefault();
      
      // Check if form is valid
      if (!validateOrderForm()) {
        return false;
      }
      
      // If payment method is cash, show cash payment modal
      if ($('input[name="payment_method"]:checked').val() === 'Cash') {
        // Set the total amount in the modal
        $('#total_amount').val($('#net_amount').val());
        $('#cash_given').val('');
        $('#change_amount').val('0.00');
        $('#cash-error').addClass('d-none');
        $('#confirm_cash_payment').prop('disabled', true);
        
        // Show the modal
        $('#cashPaymentModal').modal('show');
        
        // Focus on the cash input
        setTimeout(function() {
          $('#cash_given').focus();
        }, 500);
      } else {
        // For other payment methods, submit directly
        submitOrderForm();
      }
    });
    
    // Real-time change calculation
    $('#cash_given').on('input', function() {
      calculateChange();
    });
    
    // Handle Enter key on cash_given input
    $('#cash_given').on('keydown', function(e) {
      if(e.key === 'Enter' || e.keyCode === 13) {
        e.preventDefault();
        if(!$('#confirm_cash_payment').prop('disabled')) {
          $('#confirm_cash_payment').click();
        }
      }
    });
    
    // When cash payment modal is shown, focus on cash_given input
    $('#cashPaymentModal').on('shown.bs.modal', function() {
      $('#cash_given').focus();
      calculateChange(); // Initialize calculation
    });
    
    // Confirm cash payment
    $('#confirm_cash_payment').on('click', function() {
      if (!$(this).prop('disabled')) {
        // Add additional field to mark the order as paid
        const cashReceived = $('#cash_given').val();
        const changeAmount = $('#change_amount').val();
        
        // Close the modal
        $('#cashPaymentModal').modal('hide');
        
        // Submit with paid status
        submitOrderForm(true, cashReceived, changeAmount);
      }
    });

    // Validate the order form
    function validateOrderForm() {
      // Check if at least one product is selected
      let valid = false;
      $('.product').each(function() {
        if ($(this).val() !== '') {
          valid = true;
          return false; // Break the loop
        }
      });
      
      if (!valid) {
        // Show error
        $('#messages').html(`
          <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
            <div class="lh-1">Please select at least one product</div>
          </div>
        `);
        return false;
      }
      
      return true;
    }
    
    // Calculate change
    function calculateChange() {
      let totalAmount = parseFloat($('#total_amount').val()) || 0;
      let cashGiven = parseFloat($('#cash_given').val()) || 0;
      let change = cashGiven - totalAmount;
      
      // Display change if positive, else zero
      $('#change_amount').val(change > 0 ? change.toFixed(2) : '0.00');
      
      // Show/hide error message and manage confirm button state
      if (cashGiven < totalAmount) {
        $('#cash-error').removeClass('d-none');
        $('#confirm_cash_payment').prop('disabled', true);
      } else {
        $('#cash-error').addClass('d-none');
        $('#confirm_cash_payment').prop('disabled', false);
      }
    }
    
    // Validate cash payment (for backward compatibility)
    function validateCashPayment() {
      let totalAmount = parseFloat($('#total_amount').val()) || 0;
      let cashGiven = parseFloat($('#cash_given').val()) || 0;
      
      return cashGiven >= totalAmount;
    }
    
    // Submit order form via AJAX
    function submitOrderForm(isPaid = false, cashReceived = null, changeAmount = null) {
      // Get the form element
      const $form = $('#order-form');
      
      // Create a copy of the form data
      const formData = $form.serialize();
      
      // Add paid_status if cash payment is confirmed
      let submitData = formData;
      if (isPaid) {
        submitData += '&paid_status=1';
        
        // Add cash payment details if available
        if (cashReceived !== null) {
          submitData += '&cash_received=' + cashReceived;
        }
        
        if (changeAmount !== null) {
          submitData += '&change_amount=' + changeAmount;
        }
      } else {
        // For non-cash methods, also mark as paid
        if ($('input[name="payment_method"]:checked').val() !== 'Cash') {
          submitData += '&paid_status=1';
        } else {
          // Cash payment without confirmation - mark as unpaid
          submitData += '&paid_status=0';
        }
      }
      
      $.ajax({
        url: base_url + 'orders/create',
        type: 'POST',
        data: submitData,
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Display success message
            $('#messages').html(`
              <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1">
                  ${response.message}
                  ${response.order_no ? `<br>Order #: <strong>${response.order_no}</strong>` : ''}
                  ${response.paid_status === 1 ? '<br><span class="badge bg-success ms-1">Paid</span>' : ''}
                </div>
              </div>
            `);
            
            // Ask if user wants to print receipt
            if (response.success && response.order_no) {

              Swal.fire({
              title: "Order Processed Successfully!",
              text: "Would you like to print the receipt?",
              icon: "success",
              showCancelButton: true,
              customClass: {
                confirmButton: "btn btn-primary me-2 mt-2",
                cancelButton: "btn btn-danger mt-2",
              },
              confirmButtonText: "Yes, Print",
              cancelButtonText: 'No, Thanks',
              buttonsStyling: !1,
              showCloseButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                  window.open(base_url + 'orders/receipt/' + response.order_no, '_blank');
                }
                resetOrderForm();
              });

            } else {
              // Reset the form if no order number
              resetOrderForm();
            }
            
            // Initialize auto-dismiss for the alert
            initializeAutoDismissAlerts();
          } else {
            // Display error message
            $('#messages').html(`
              <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1">${response.message || 'Error occurred while processing order'}</div>
              </div>
            `);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', status, error);
          console.log('Response:', xhr.responseText);
          
          // Display error message
          $('#messages').html(`
            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
              <div class="lh-1">Error occurred while processing order</div>
            </div>
          `);
        }
      });
    }
    
    // Function to reset the order form
    function resetOrderForm() {
      // Reset payment method to cash
      $('#paymentCash').prop('checked', true);
      
      // Clear the product table except the first row
      $("#product_info_table tbody tr:not(:first)").remove();
      
      // Reset the first row
      $("#product_1").val('').trigger('change.select2');
      $("#rate_1").val('');
      $("#rate_value_1").val('');
      $("#qty_1").val('');
      $("#amount_1").val('');
      $("#amount_value_1").val('');
      $("#product_image_1").val('');
      
      // Clear discount
      $("#discount").val('');
      
      // Reset totals
      subAmount();
      
      // Scroll to top
      window.scrollTo(0, 0);
    }
  });

  // Product functions - moved outside document ready to be globally accessible
  function getProductData(row_id) {
    var product_id = $("#product_" + row_id).val();    
    if (product_id == "") {
      $("#rate_" + row_id).val("");
      $("#rate_value_" + row_id).val("");
      $("#qty_" + row_id).val("");
      $("#amount_" + row_id).val("");
      $("#amount_value_" + row_id).val("");
      $("#product_image_" + row_id).val("");
      
      return false;
    }

    $.ajax({
      url: base_url + 'orders/getProductValueById',
      type: 'post',
      data: {product_id : product_id},
      dataType: 'json',
      success:function(response) {
        // Setting the rate value
        $("#rate_" + row_id).val(response.price);
        $("#rate_value_" + row_id).val(response.price);
        $("#product_image_" + row_id).val(response.image);
        
        // Set default quantity to 1
        $("#qty_" + row_id).val(1);
        
        // Calculate the amount
        var qty = 1;
        var amount = Number(response.price) * qty;
        $("#amount_" + row_id).val(amount.toFixed(2));
        $("#amount_value_" + row_id).val(amount.toFixed(2));
        
        // Auto-focus on the quantity field for immediate editing
        $("#qty_" + row_id).focus();
        $("#qty_" + row_id).select();
        
        subAmount();
        updateCartDisplay();
      }
    });
  }

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
      
      subAmount();
    } else {
      alert('no row !! please refresh the page');
    }
  }

  function subAmount() {
    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
    }

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);
    $("#cart-gross-amount").text("₱ " + totalSubAmount);

    // vat
    var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);
    $("#cart-vat-charge").text("₱ " + vat);

    // service
    var service = (Number($("#gross_amount").val())/100) * service_charge;
    service = service.toFixed(2);
    $("#service_charge").val(service);
    $("#service_charge_value").val(service);
    $("#cart-service-charge").text("₱ " + service);
    
    // total amount
    var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    totalAmount = totalAmount.toFixed(2);

    var discount = $("#discount").val();
    if(discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
      $("#cart-net-amount").text("₱ " + grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
      $("#cart-net-amount").text("₱ " + totalAmount);
    }
    
    // Update the cart display
    updateCartDisplay();
  }

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }
  
  function updateCartDisplay() {
    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalItems = 0;
    
    // Clear the cart items container
    $("#cart-items").empty();
    
    // Loop through product table rows
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);
      
      var productId = $("#product_"+count).val();
      var productName = $("#product_"+count+" option:selected").text();
      var productQty = $("#qty_"+count).val();
      var productRate = $("#rate_"+count).val();
      var productAmount = $("#amount_"+count).val();
      var productImage = $("#product_image_"+count).val() || "assets/images/product-default.jpg";
      
      // Only add to cart if product is selected
      if(productId) {
        totalItems++;
        
        // Create cart item element
        var cartItemHtml = `
          <div class="list-group-item py-3 px-4">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <img src="${base_url}${productImage}" alt="${productName}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
              </div>
              <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">${productName}</h6>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">${productQty} × ₱ ${productRate}</small>
                  <span class="fw-medium">₱ ${productAmount}</span>
                </div>
              </div>
            </div>
          </div>
        `;
        
        $("#cart-items").append(cartItemHtml);
      }
    }
    
    // Show/hide empty cart message
    if(totalItems > 0) {
      $("#empty-cart-message").hide();
    } else {
      $("#empty-cart-message").show();
    }
  }
</script>

<style>
  @media (min-width: 992px) {
    .cart-container {
      width: 520px;
    }
  }
  
  @media (max-width: 991.98px) {
    .cart-container {
      width: 100%;
    }
    
    .sticky-top {
      position: relative !important;
      top: 0 !important;
    }
  }
  
  /* Products table scrollable after 4 rows */
  .table-responsive.product-table-container {
    max-height: 285px; /* Height to show approximately 4 rows */
    overflow-y: auto;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    padding: 0;
  }
  
  /* Fix for white space below table */
  .product-table-container .table {
    margin-bottom: 0;
    border-bottom: 0;
  }
  
  /* Remove last row bottom border to fix double border issue */
  .product-table-container tbody tr:last-child td {
    border-bottom: 0;
  }
  
  /* Sticky header styling */
  #product_info_table thead {
    position: sticky;
    top: 0;
    z-index: 5;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }
  
  /* Hide the horizontal scrollbar if not needed */
  .table-responsive::-webkit-scrollbar-horizontal {
    display: none;
  }
  
  /* Zebra striping for better row visibility */
  #product_info_table tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,0.025);
  }
  
  /* Select2 Simplified Fixes */
  .select2-container {
    min-width: 100%;
    width: auto !important;
  }
  
  /* Make dropdown width match the container width */
  .select2-dropdown {
    min-width: fit-content;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    max-width: 500px; /* Prevent excessively wide dropdowns */
  }
  
  .select2-container--default .select2-results>.select2-results__options {
    max-height: 250px;
  }
  
  /* Ensure the select2 stays within its cell */
  #product_info_table td {
    position: relative;
  }
  
  #product_info_table td .select2-container {
    width: 100% !important;
  }
  
  /* Hide the horizontal scrollbar if not needed */
</style>