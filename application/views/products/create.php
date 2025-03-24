        <div class="page-content">
            <div class="page-container">
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold mb-0">Add Products</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item active">Add Products</li>
                        </ol>
                    </div>
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
                                                            <label for="productName" class="form-label">Product Name</label>
                                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="skuId" class="form-label">SKU</label>
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
                                                            <label for="productPrice" class="form-label">Price</label>
                                                            <input class="form-control" id="price" placeholder="₱00.00" name="price">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basic-datepicker" class="form-label">Quantity</label>
                                                            <input type="text" id="quantity" class="form-control" placeholder="Enter quantity" name="quantity">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="productStatus" class="form-label">Availability</label>
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
    $('#category, #brand, #availability').select2();

    // Fix Select2 search issue
    $(document).on('select2:open', () => {
        setTimeout(() => {
            document.querySelector('.select2-search__field').focus();
        }, 100);
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
});
</script>
