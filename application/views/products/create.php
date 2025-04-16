        <div class="page-content">
            <div class="page-container">
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold mb-0">Add Products</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Add Products</li>
                        </ol>
                    </div>
                </div>
                
              <!-- Required fields alert -->
              <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
                <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
              </div>
                
                <div class="row">
                    <div class="col-12">
                        <div id="messages"></div>
                        
                        <div class="card">
                            <div class="card-body">
                                <form id="addProductForm">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header border-bottom border-dashed">
                                                <h4 class="card-title" style="margin-top: 10px">Product Image</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <!-- Image Preview -->
                                                <img id="imagePreview" src="assets/images/product_images/no-image.jpg" class="img-fluid rounded mb-3" style="max-width: 200px; height: auto; object-fit: cover; display: none;" alt="Product Preview">

                                                <!-- File Input -->
                                                <input type="file" id="product_image" name="product_image" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header border-bottom border-dashed">
                                                <h4 class="card-title" style="margin-top: 10px">Product Information</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="skuId" class="form-label">SKU <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter SKU">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Product Description</label>
                                                            <textarea class="form-control" id="description" rows="7" name="description" placeholder="Enter a short description about the product"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productCode" class="form-label">Product Barcode</label>
                                                            <input type="text" class="form-control" id="productCode" name="barcode" placeholder="Enter barcode">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productCategory" class="form-label">Product Category</label>
                                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="category" name="category">
                                                                <?php foreach ($category as $k => $v): ?>
                                                                <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productBrand" class="form-label">Product Brand</label>
                                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="brand" name="brand">
                                                                <?php foreach ($brands as $k => $v): ?>
                                                                <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productPrice" class="form-label">Price <span class="text-danger">*</span></label>
                                                            <input class="form-control" id="price" placeholder="₱00.00" name="price">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basic-datepicker" class="form-label">Quantity <span class="text-danger">*</span></label>
                                                            <input type="text" id="quantity" class="form-control" placeholder="Enter quantity" name="quantity">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productStatus" class="form-label">Availability </label>
                                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="availability" name="availability">
                                                                <option value="1">Available</option>
                                                                <option value="0">Out of Stock</option>
                                                                <option value="2">Discontinued</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?php echo base_url('products')?>" class="btn btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-info" id="addProductBtn">Add New Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script>
// Function to auto-dismiss messages
function autoDismissMessages() {
    setTimeout(function() {
        $("#messages .alert").fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000); // 5 seconds
}
    
// Product image preview
$(document).ready(function () {
    // Initialize Select2
    $('#category, #brand, #availability').select2({
        width: '100%'
    });

    // Fix Select2 search issue
    $(document).on('select2:open', () => {
        setTimeout(() => {
            document.querySelector('.select2-search__field').focus();
        }, 100);
    });

    // Check for duplicate product details
    function checkDuplicateField(field, value, productId = null) {
        return new Promise((resolve, reject) => {
            if (!value.trim()) {
                resolve(false); // Not a duplicate if empty
                return;
            }
            
            $.ajax({
                url: "<?php echo base_url('products/check_duplicate'); ?>",
                type: "POST",
                data: { 
                    field: field, 
                    value: value,
                    product_id: productId // Pass product ID for edit mode to exclude current product
                },
                dataType: "json",
                success: function(response) {
                    resolve(response.duplicate);
                },
                error: function() {
                    reject("Failed to check duplicate");
                }
            });
        });
    }

    // Add debounce functionality to prevent too many requests
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Add event listeners for duplicate checking
    const debouncedCheckDuplicate = debounce(async function(element, field) {
        try {
            const value = $(element).val();
            const isDuplicate = await checkDuplicateField(field, value);
            
            if (isDuplicate) {
                $(element).addClass('is-invalid');
                // Remove any existing error message for this field
                $(element).next('.error-message').remove();
                
                // Provide more descriptive error messages for each field
                let errorMessage = '';
                switch(field) {
                    case 'name':
                        errorMessage = 'A product with this name already exists';
                        break;
                    case 'sku':
                        errorMessage = 'This SKU is already in use';
                        break;
                    case 'barcode':
                        errorMessage = 'This barcode is already registered';
                        break;
                    default:
                        errorMessage = `This ${field} already exists`;
                }
                
                $(element).after(`<div class="error-message text-danger small mt-1">${errorMessage}</div>`);
            } else {
                // Only remove the duplicate error message if it exists
                const errorMessage = $(element).next('.error-message');
                if (errorMessage.length && errorMessage.text().includes('already')) {
                    $(element).removeClass('is-invalid');
                    errorMessage.remove();
                }
            }
        } catch (error) {
            console.error("Error checking duplicate:", error);
        }
    }, 500);

    // Add blur event listeners to check for duplicates
    $('#product_name').on('blur', function() {
        debouncedCheckDuplicate(this, 'name');
    });

    $('#sku').on('blur', function() {
        debouncedCheckDuplicate(this, 'sku');
    });

    $('#productCode').on('blur', function() {
        debouncedCheckDuplicate(this, 'barcode');
    });

    $("#product_image").change(function (event) {
        let input = event.target;
        let reader = new FileReader();

        reader.onload = function () {
            $("#imagePreview").attr("src", reader.result).show(); // Show the image
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]); // Read the selected image
        } else {
            $("#imagePreview").hide(); // Hide the image if no file is selected
        }
    });

    // Handle product addition
    $("#addProductForm").submit(function (e) {
        e.preventDefault();
        
        // Clear previous error messages
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        let isValid = true;
        
        // Product Name validation
        if (!$('#product_name').val().trim()) {
            $('#product_name').addClass('is-invalid');
            $('#product_name').after('<div class="error-message text-danger small mt-1">Product name is required</div>');
            isValid = false;
        }
        
        // SKU validation
        if (!$('#sku').val().trim()) {
            $('#sku').addClass('is-invalid');
            $('#sku').after('<div class="error-message text-danger small mt-1">SKU is required</div>');
            isValid = false;
        }
        
        // Price validation
        const price = $('#price').val().trim();
        if (!price) {
            $('#price').addClass('is-invalid');
            $('#price').after('<div class="error-message text-danger small mt-1">Price is required</div>');
            isValid = false;
        } else if (isNaN(parseFloat(price)) || parseFloat(price) < 0) {
            $('#price').addClass('is-invalid');
            $('#price').after('<div class="error-message text-danger small mt-1">Price must be a valid number</div>');
            isValid = false;
        }
        
        // Quantity validation
        const quantity = $('#quantity').val().trim();
        if (!quantity) {
            $('#quantity').addClass('is-invalid');
            $('#quantity').after('<div class="error-message text-danger small mt-1">Quantity is required</div>');
            isValid = false;
        } else if (isNaN(parseInt(quantity)) || parseInt(quantity) < 0 || quantity.indexOf('.') !== -1) {
            $('#quantity').addClass('is-invalid');
            $('#quantity').after('<div class="error-message text-danger small mt-1">Quantity must be a valid number</div>');
            isValid = false;
        }
        
        // Check if there are any duplicate validation errors
        if (hasDuplicateErrors()) {
            isValid = false;
        }
        
        // If validation fails, stop form submission
        if (!isValid) {
            return false;
        }

        let formData = new FormData(this);

        $.ajax({
            url: "<?php echo base_url('products/create'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                console.log("Response:", response);

                // Clear previous messages
                $("#messages").html("");

                if (response.success) {
                    $("#messages").append(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.message}</div>
                        </div>
                    `);
                    
                    // Start auto-dismiss timer
                    autoDismissMessages();
                    
                    // Reset form
                    resetForm();
                    
                    // Redirect to products page after successful addition
                    setTimeout(function() {
                        window.location.href = "<?php echo base_url('products'); ?>";
                    }, 2000);
                } else {
                    // If multiple errors exist, show each in a separate div
                    if (response.messages && typeof response.messages === 'object') {
                        $.each(response.messages, function (key, errorMsg) {
                            $("#messages").append(`
                                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">${errorMsg}</div>
                                </div>
                            `);
                        });
                    } else {
                        // If only a single error message exists
                        $("#messages").append(`
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">${response.message || "Unexpected error occurred"}</div>
                            </div>
                        `);
                    }
                    // Start auto-dismiss timer for error messages
                    autoDismissMessages();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $("#messages").append(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">Error adding product. Please try again.</div>
                    </div>
                `);
                // Start auto-dismiss timer for error message
                autoDismissMessages();
            },
        });
    });

    // Add input event listeners for real-time validation
    $('#product_name, #sku, #price, #quantity').on('input', function() {
        // Remove error message and invalid class when user starts typing
        $(this).removeClass('is-invalid');
        $(this).next('.error-message').remove();
    });
    
    // Handle paste events for price
    $('#price').on('paste', function(e) {
        e.preventDefault();
        var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        // Replace any non-numeric or non-decimal characters
        pastedData = pastedData.replace(/[^0-9.]/g, '');
        // Ensure only one decimal point
        var parts = pastedData.split('.');
        pastedData = parts[0] + (parts.length > 1 ? '.' + parts[1] : '');
        $(this).val(pastedData);
    });

    // Handle paste events for quantity
    $('#quantity').on('paste', function(e) {
        e.preventDefault();
        var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        // Replace any non-numeric characters
        pastedData = pastedData.replace(/[^0-9]/g, '');
        $(this).val(pastedData);
    });

    // Function to reset the form
    function resetForm() {
        // Reset the form
        $("#addProductForm")[0].reset();
        
        // Clear image preview
        $("#imagePreview").hide();
        $("#product_image").val('');
        
        // Reset Select2 dropdowns
        $('#category').val(null).trigger('change');
        $('#brand').val(null).trigger('change');
        $('#availability').val('1').trigger('change'); // Reset to default value (Available)
    }

    // Check if there are any duplicate validation errors
    function hasDuplicateErrors() {
        return $('.error-message').filter(function() {
            return $(this).text().includes('already exists') || 
                    $(this).text().includes('already in use') || 
                    $(this).text().includes('already registered');
        }).length > 0;
    }
});
</script>
