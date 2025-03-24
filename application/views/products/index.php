<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Products</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Manage Products</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="messages"></div>

                <!-- Success/Error Messages -->
<!--
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php elseif($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
-->

                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="d-flex flex-wrap justify-content-between gap-2">
                            <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px;">
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a product" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewProduct', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showProductsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Products</button>
                                <?php endif; ?>
                                <?php if(in_array('createProduct', $user_permission)): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addProductModal" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Product</button>
                                <button type="button" class="btn btn-soft-primary btn-icon"><i class="ti ti-upload fs-20"></i> </button>
                                <?php endif; ?>
                                <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="actionsBtn" style="display: none"><i class="ti ti-settings me-1"></i> Actions <span class="caret"></span></button>
                                  <div class="dropdown-menu" style="">
                                    <?php if(in_array('updateProduct', $user_permission)): ?>
                                     <a class="dropdown-item edit-item" href="#"><i class="ti ti-edit"></i> &nbsp;Edit</a>
                                    <?php endif; ?>
                                    <?php if(in_array('deleteProduct', $user_permission)): ?>
                                    <a class="dropdown-item" href="#"><i class="ti ti-trash"></i> &nbsp;Delete</a>
                                    <?php endif; ?>
                                 </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap mb-0" id="productTable">
                                    <thead class="bg-dark-subtle" id="productTableHead">
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </th>
                                            <th>SKU</th>
                                            <th style="text-align: center">Product Name</th>
                                            <th>Brand</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Category</th>
                                            <th>Availability</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productBody">
                                        <!-- Products will be loaded here dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer" id="productFooter">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <!-- Range info will be inserted here -->
                                    </div>
                                    <ul class="pagination mb-0">
                                        <!-- Pagination will be inserted here -->
                                    </ul>
                                </div>
                            </div>

                        </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container -->
    
    <!-- Add Product Modal -->
<div id="addProductModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
                <div class="modal-body">
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
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Cancel</button>
                                    <button type="submit" class="btn btn-info" id="addProductBtn">Add New Product</button>
                                </div>
                    </form>
                </div>
        </div>
    </div>
</div>

    <!-- /.modal -->
    
    
    <!-- Delete Modal -->
    <div id="removeModal" class="modal fade" tabindex="-1" aria-labelledby="removeModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-filled bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title" id="removeModalLabel">Delete Product(s)</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form role="form" id="removeForm">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the selected product(s)? This action cannot be undone.</p>
                        <p>All associated data, including product images and inventory records, will be permanently removed.</p>
                        <input type="hidden" id="removeProductIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark">Delete</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
  
    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom border-dashed">
                                    <h4 class="card-title" style="margin-top: 10px">Product Image</h4>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Image Preview -->
                                    <img id="edit_imagePreview" src="assets/images/product_images/no-image.jpg" class="img-fluid rounded mb-3" style="max-width: 200px; height: auto; object-fit: cover;" alt="Product Preview">

                                    <!-- File Input -->
                                    <input type="file" id="edit_product_image" name="product_image" class="form-control">
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
                                                <label for="edit_product_name" class="form-label">Product Name</label>
                                                <input type="text" class="form-control" id="edit_product_name" name="product_name" placeholder="Enter product name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_sku" class="form-label">SKU</label>
                                                <input type="text" class="form-control" id="edit_sku" name="sku" placeholder="Enter SKU">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="edit_description" class="form-label">Product Description</label>
                                                <textarea class="form-control" id="edit_description" rows="7" name="description" placeholder="Enter a short description about the product"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_productCode" class="form-label">Product Barcode</label>
                                                <input type="text" class="form-control" id="edit_productCode" name="barcode" placeholder="Enter barcode">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_category" class="form-label">Product Category</label>
                                                <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="edit_category" name="category">
                                                    <?php foreach ($category as $k => $v): ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_brand" class="form-label">Product Brand</label>
                                                <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="edit_brand" name="brand">
                                                    <?php foreach ($brands as $k => $v): ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_price" class="form-label">Price</label>
                                                <input class="form-control" id="edit_price" placeholder="₱00.00" name="price">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_quantity" class="form-label">Quantity</label>
                                                <input type="text" id="edit_quantity" class="form-control" placeholder="Enter quantity" name="quantity">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="edit_availability" class="form-label">Availability</label>
                                                <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="edit_availability" name="availability">
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
                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-info" id="updateProductBtn">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
  
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
});

// Function to reload the product table - moved to global scope
function loadProductTable(page = 1, search = '') {
    $.ajax({
        url: "<?php echo base_url('products/get_products'); ?>",
        type: "GET",
        data: { 
            page: page,
            search: search
        },
        dataType: "html",
        success: function (response) {
            $("#productBody").html(response);
            $("#productTableHead, #productFooter, #searchBar").fadeIn();
            $("#showProductsBtn").hide(); // Hide the button after showing the table
        },
        error: function () {
            $("#productBody").html("<tr><td colspan='9' class='text-center text-danger'>Failed to load products</td></tr>");
        }
    });
}

$(document).ready(function () {
    // Initialize Select2
    $('#category, #brand, #availability').select2({
        dropdownParent: $('#addProductModal')
    });

    // Initialize Select2 for edit modal
    $('#edit_category, #edit_brand, #edit_availability').select2({
        dropdownParent: $('#editProductModal .modal-content')
    });

    // Reinitialize Select2 when edit modal is shown
    $('#editProductModal').on('shown.bs.modal', function () {
        $('#edit_category, #edit_brand, #edit_availability').select2({
            dropdownParent: $('#editProductModal .modal-content')
        });
    });

    // Fix Select2 search issue inside a modal
    $(document).on('select2:open', () => {
        setTimeout(() => {
            document.querySelector('.select2-search__field').focus();
        }, 100);
    });

    // Fetch the products when "Show Products" button is clicked
    $("#showProductsBtn").click(function () {
        loadProductTable(); // Now only loads when the button is clicked
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

                // Refresh the table only if it's already visible
                if ($("#productTableHead").is(":visible")) {
                    loadProductTable();
                }
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
                $("#addProductModal").modal("hide");
                resetForm();
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
                $("#addProductModal").modal("hide");
                resetForm();
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

    // Actions Button Visibility Handling
    let actionsBtn = $("#actionsBtn");

    function updateActionsButton() {
        let checkedCount = $("#productBody input[type='checkbox']:checked").length;
        let anyChecked = checkedCount > 0;
        
        // Show/hide the actions button
        actionsBtn.toggle(anyChecked);
        
        // Show/hide the edit option based on number of selections
        $('.edit-item').toggle(checkedCount === 1);
    }

    $(document).on("change", "#productBody input[type='checkbox']", updateActionsButton);

    $("#selectAll").on("change", function () {
        let isChecked = $(this).prop("checked");
        $("#productBody input[type='checkbox']").prop("checked", isChecked);
        updateActionsButton();
    });

    // Handle delete action from dropdown
    $('.dropdown-menu .dropdown-item').click(function(e) {
        e.preventDefault();
        let action = $(this).text().trim();
        
        if(action === 'Delete') {
            let selectedProducts = [];
            $("#productBody input[type='checkbox']:checked").each(function() {
                selectedProducts.push($(this).val());
            });
            
            if(selectedProducts.length > 0) {
                $('#removeProductIds').val(JSON.stringify(selectedProducts));
                $('#removeModal').modal('show');
            } else {
                $('#messages').html(`
                    <div class="alert alert-warning text-bg-warning alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">Please select at least one product to delete</div>
                    </div>
                `);
                autoDismissMessages();
            }
        }
    });

    // Handle delete request
    $('#removeForm').on('submit', function(e) {
        e.preventDefault();
        var productIds = JSON.parse($('#removeProductIds').val());
        
        $.ajax({
            url: "<?php echo base_url('products/remove'); ?>",
            type: 'POST',
            data: { product_ids: productIds },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#removeModal').modal('hide');
                    
                    // Create success message based on number of products
                    let message = '';
                    if (response.count === 1) {
                        message = `${response.products[0].name} was successfully deleted`;
                    } else {
                        let productNames = response.products.map(p => p.name).join(', ');
                        message = `${productNames} were successfully deleted`;
                    }
                    
                    $('#messages').html(`
                        <div class="alert alert-info text-bg-info alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${message}</div>
                        </div>
                    `);
                    // Start auto-dismiss timer
                    autoDismissMessages();
                    
                    // Update actions button visibility
                    updateActionsButton();
                    
                    // Refresh the table to show updated data
                    loadProductTable();
                } else {
                    $('#messages').html(`
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.message}</div>
                        </div>
                    `);
                    // Start auto-dismiss timer
                    autoDismissMessages();
                }
            }
        });
    });

    // Handle edit product image preview
    $("#edit_product_image").change(function (event) {
        let input = event.target;
        let reader = new FileReader();

        reader.onload = function () {
            $("#edit_imagePreview").attr("src", reader.result);
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Handle modify action from dropdown
    $('.dropdown-menu .dropdown-item').click(function(e) {
        e.preventDefault();
        let action = $(this).text().trim();
        
        if(action === 'Edit') {
            let selectedProduct = $("#productBody input[type='checkbox']:checked").val();
            if(selectedProduct) {
                // Fetch product details
                $.ajax({
                    url: "<?php echo base_url('products/get_product'); ?>",
                    type: 'POST',
                    data: { product_id: selectedProduct },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            let product = response.data;
                            
                            // Populate the edit form with product data
                            $('#edit_product_id').val(product.id);
                            $('#edit_product_name').val(product.name);
                            $('#edit_sku').val(product.sku);
                            $('#edit_description').val(product.description);
                            $('#edit_productCode').val(product.barcode);
                            
                            // Update Select2 values properly
                            $('#edit_category').val(product.category_id).trigger('change');
                            $('#edit_brand').val(product.brand_id).trigger('change');
                            $('#edit_availability').val(product.availability).trigger('change');
                            
                            $('#edit_price').val(product.price);
                            $('#edit_quantity').val(product.quantity);
                            
                            // Set the current image
                            $('#edit_imagePreview').attr('src', product.image);
                            
                            // Show the edit modal
                            $('#editProductModal').modal('show');
                        } else {
                            $('#messages').html(`
                                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">Failed to load product details</div>
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
                                <div class="lh-1">Error loading product details</div>
                            </div>
                        `);
                        autoDismissMessages();
                    }
                });
            }
        }
    });

    // Handle product update
    $("#editProductForm").submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "<?php echo base_url('products/update'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#editProductModal').modal('hide');
                    
                    $('#messages').html(`
                        <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.message}</div>
                        </div>
                    `);
                    
                    autoDismissMessages();
                    loadProductTable();
                } else {
                    if (response.messages && typeof response.messages === 'object') {
                        let errorHtml = '';
                        $.each(response.messages, function (key, errorMsg) {
                            errorHtml += `
                                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">${errorMsg}</div>
                                </div>
                            `;
                        });
                        $('#messages').html(errorHtml);
                    } else {
                        $('#messages').html(`
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">${response.message || "Unexpected error occurred"}</div>
                            </div>
                        `);
                    }
                    autoDismissMessages();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $('#messages').html(`
                    <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">Error updating product. Please try again.</div>
                    </div>
                `);
                autoDismissMessages();
            }
        });
    });
});

// Search functionality
$('#searchBox').on('keyup', function() {
    var searchText = $(this).val();
    loadProductTable(1, searchText); // Reset to page 1 when searching
});

// Select all checkboxes
$('#selectAll').click(function() {
    $('input[type="checkbox"]').prop('checked', this.checked);
});

// Remove product function
function removeProduct(productId) {
    $('#removeProductId').val(productId);
    $('#removeModal').modal('show');
}
</script>
