<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Products</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Manage Products</li>
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
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a product" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewProduct', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showProductsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Products</button>
                                <?php endif; ?>
                                <?php if(in_array('createProduct', $user_permission)): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addProductModal" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Product</button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-soft-dark btn-icon"><i class="ti ti-upload fs-20"></i></button>
                                <?php endif; ?>
                                <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="actionsBtn" style="display: none"><i class="ti ti-settings me-1"></i> Actions <span class="caret"></span></button>
                                  <div class="dropdown-menu" style="">
                                    <?php if(in_array('updateProduct', $user_permission)): ?>
                                     <a class="dropdown-item edit-item" href="#"><i class="ti ti-edit"></i> &nbsp;Edit</a>
                                    <?php endif; ?>
                                    <?php if(in_array('deleteProduct', $user_permission)): ?>
                                    <a class="dropdown-item text-danger" href="#"><i class="ti ti-trash"></i> &nbsp;Delete</a>
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
                
                      <!-- Required fields alert -->
                      <div class="alert alert-info text-bg-light d-flex align-items-center mb-3" role="alert">
                <iconify-icon icon="solar:info-circle-line-duotone" class="fs-20 me-1"></iconify-icon>
                <div class="lh-1">Fields marked with <span class="text-danger fw-bold">*</span> are required.</div>
              </div>
                <form id="addProductForm" enctype="multipart/form-data">
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

                    </form>
                    
                </div>
                                <div class="modal-footer">
                     <div class="d-flex justify-content-end gap-2">
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Cancel</button>
                                    <button type="submit" class="btn btn-info" form="addProductForm">Add New Product</button>
                                </div>
                                </div>
        </div>
    </div>
</div>

    <!-- /.modal -->
    
    
    <!-- Delete Modal -->
    <div id="removeModal" class="modal fade" tabindex="-1" aria-labelledby="removeModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title text-danger" id="deleteProductTitle">
                        <i class="ti ti-trash me-2"></i>Delete Product
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
                            <p id="deleteProductMessage" class="fs-5 mb-0">Are you sure you want to delete the selected product(s)? This action cannot be undone.</p>
                            <p class="mt-2 mb-0">All associated data, including product images and inventory records, will be permanently removed.</p>
                        </div>
                        <input type="hidden" id="removeProductIds">
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
  
    <!-- Import Products Modal -->
    <div id="importModal" class="modal fade" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="importModalLabel">Import Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info text-bg-light d-flex align-items-center text-wrap mb-4" role="alert">
                        <div>
                            Upload Excel file (.xlsx) containing product details.<br>
                            Required columns:<br>
                            Product Name, SKU, Description, Barcode, Category, Brand, Price, Quantity, Availability
                            <hr>
                            <small><strong>Note:</strong> If a category or brand doesn't exist, it will be automatically created.</small>
                        </div>
                    </div>

                    
                    <form id="importForm" action="<?php echo base_url('products/import'); ?>" method="POST" class="dropzone dz-clickable" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                    <div class="dz-message needsclick">
                                            <i class="h1 ti ti-cloud-upload mb-4"></i>
                                            <h4>Drop files here or click to upload.</h4>
                                            <span class="text-muted fs-13">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                        </div>
                    </form>
                    
                    <!-- Preview section for uploaded files -->
                    <div class="mt-3">
                        <div id="file-previews" class="dropzone-previews mt-2"></div>
                    </div>
                    
                    <!-- Template for file previews (hidden) -->
                    <div class="d-none" id="uploadPreviewTemplate">
                        <div class="card mt-1 mb-0 shadow-none border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Excel icon instead of image thumbnail -->
                                        <div class="avatar-sm rounded bg-light d-flex align-items-center justify-content-center">
                                            <i class="ti ti-file-spreadsheet text-primary fs-20"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0">
                                        <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                        <p class="mb-0" data-dz-size></p>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Button -->
                                        <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                            <i class="ti ti-x"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitImport" class="btn btn-info" disabled>Import Products</button>
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

// Function to get all product IDs
function getAllProductIds(search = '') {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "<?php echo base_url('products/get_all_product_ids'); ?>",
            type: "GET",
            data: {
                search: search
            },
            dataType: "json",
            success: function(response) {
                resolve(response.product_ids);
            },
            error: function() {
                reject("Failed to get product IDs");
            }
        });
    });
}

// Track selected product IDs for cross-page selection
let selectedProductIds = [];
let allProductIds = [];
let selectAllChecked = false;

// Disable Dropzone auto discovery globally
Dropzone.autoDiscover = false;

$(document).ready(function () {
    // Initialize Select2
    $('#category, #brand, #availability').select2({
        dropdownParent: $('#addProductModal .modal-content'),
        width: '100%'
    });

    // Initialize Select2 for edit modal
    $('#edit_category, #edit_brand, #edit_availability').select2({
        dropdownParent: $('#editProductModal .modal-content'),
        width: '100%'
    });

    // Initialize Dropzone only if the form exists
    if ($("#importForm").length) {
        // Remove any existing dropzone instances
        if (typeof importDropzone !== 'undefined') {
            importDropzone.destroy();
        }
        
        // Create new dropzone instance
        var importDropzone = new Dropzone("#importForm", {
            url: "<?php echo base_url('products/import'); ?>",
            acceptedFiles: ".xlsx",
            maxFiles: 1,
            autoProcessQueue: false, // Prevent auto upload
            dictDefaultMessage: "Drop Excel file here or click to upload",
            dictFileTooBig: "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.",
            dictInvalidFileType: "You can't upload files of this type.",
            dictResponseError: "Server responded with {{statusCode}} code.",
            dictCancelUpload: "Upload cancelled.",
            dictUploadCanceled: "Upload cancelled.",
            dictMaxFilesExceeded: "You can not upload any more files.",
            previewsContainer: "#file-previews",
            previewTemplate: document.querySelector('#uploadPreviewTemplate').innerHTML,
            init: function() {
                var myDropzone = this;
                
                // Handle file add event
                this.on("addedfile", function(file) {
                    // Enable the import button when a file is added
                    $("#submitImport").prop("disabled", false);
                });
                
                // Handle file remove event
                this.on("removedfile", function(file) {
                    // Disable the import button when no files are present
                    if (this.files.length === 0) {
                        $("#submitImport").prop("disabled", true);
                    }
                });
                
                // Handle max files exceeded
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                
                // Set up submit button
                $("#submitImport").click(function() {
                    if (myDropzone.files.length > 0) {
                        // Process the queue when the button is clicked
                        myDropzone.processQueue();
                    }
                });
                
                // Handle success
                this.on("success", function(file, response) {
                    // Check if response is a valid JSON string
                    try {
                        var data = typeof response === 'string' ? JSON.parse(response) : response;

                        if (data.success) {
                            var message = "Products imported successfully.";
                            if (data.errors && data.errors.length > 0) {
                                message = data.message + "<br><small>Some items encountered errors. Check console for details.</small>";
                                // Log errors to console for reference
                                console.log("Import errors:", data.errors);
                            }
                        
                            $('#messages').html(`
                                <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">${message}</div>
                                </div>
                            `);
                            
                            $('#importModal').modal('hide');
                            loadProductTable(); // Reload the product table
                            
                            // Clear the dropzone for next import
                            myDropzone.removeAllFiles();
                        } else {
                            // For complete failures
                            var errorMsg = data.message || 'Import failed';
                            var errorHtml = `
                                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">${errorMsg}</div>
                                </div>
                            `;
                            
                            // If there are detailed errors, add them to the message
                            if (data.errors && data.errors.length > 0) {
                                var errorDetails = '<div class="mt-2"><ul class="mb-0">';
                                // Show up to 3 errors in the message
                                var displayErrors = data.errors.slice(0, 3);
                                displayErrors.forEach(function(error) {
                                    errorDetails += '<li>' + error + '</li>';
                                });
                                
                                // If there are more errors, add a note
                                if (data.errors.length > 3) {
                                    errorDetails += '<li>And ' + (data.errors.length - 3) + ' more errors...</li>';
                                    // Log all errors to console for reference
                                    console.log("Import errors:", data.errors);
                                }
                                
                                errorDetails += '</ul></div>';
                                
                                errorHtml = `
                                    <div class="alert alert-danger text-bg-danger alert-dismissible" role="alert">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <div class="d-flex align-items-center">
                                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                            <div class="lh-1">${errorMsg}</div>
                                        </div>
                                        ${errorDetails}
                                    </div>
                                `;
                            }
                            
                            $('#messages').html(errorHtml);
                            $('#importModal').modal('hide');
                        }
                        
                        autoDismissMessages();
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        $('#messages').html(`
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">Unexpected response format.</div>
                            </div>
                        `);
                        autoDismissMessages();
                    }
                });
                
                this.on("error", function(file, errorMessage) {
                    $('#messages').html(`
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">Error uploading file: ${errorMessage}</div>
                        </div>
                    `);
                    autoDismissMessages();
                });
            }
        });
    }

    // Reinitialize Select2 when edit modal is shown
    $('#editProductModal').on('shown.bs.modal', function () {
        $('#edit_category, #edit_brand, #edit_availability').select2({
            dropdownParent: $('#editProductModal .modal-content'),
            width: '100%'
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
        loadProductTable();
        initializeAllProductIds();
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

    // Disable submit button and show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

    $.ajax({
        url: "<?php echo base_url('products/create'); ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            // Clear previous messages
            $("#messages").empty();

            if (response.success) {
                $("#messages").html(`
                    <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
                        <div class="lh-1">${response.message}</div>
                    </div>
                `);
                
                // Reset form and close modal
                resetForm();
                $("#addProductModal").modal("hide");
                
                // Refresh the table only if it's already visible
                if ($("#productTableHead").is(":visible")) {
                    loadProductTable();
                }
            } else {
                let errorHtml = '';
                
                // Handle multiple error messages
                if (response.messages && typeof response.messages === 'object') {
                    Object.values(response.messages).forEach(function(errorMsg) {
                        errorHtml += `
                            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                <div class="lh-1">${errorMsg}</div>
                            </div>
                        `;
                    });
                } else {
                    // Single error message
                    errorHtml = `
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.message || "An unexpected error occurred"}</div>
                        </div>
                    `;
                }
                
                $("#messages").html(errorHtml);
                $("#addProductModal").modal("hide");
            }
            
            // Initialize auto-dismiss for new alerts
            autoDismissMessages();
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            $("#messages").html(`
                <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                    <div class="lh-1">Error adding product. Please try again.</div>
                </div>
            `);
            $("#addProductModal").modal("hide");
            autoDismissMessages();
        },
        complete: function() {
            // Re-enable submit button and restore text
            submitBtn.prop('disabled', false).html('Add New Product');
        }
    });
});

// Add input event listeners for real-time validation
$('#product_name, #sku, #price, #quantity').on('input', function() {
    // Remove error message and invalid class when user starts typing
    $(this).removeClass('is-invalid');
    $(this).next('.error-message').remove();
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

// Add event listeners for duplicate checking on add form
const debouncedCheckDuplicate = debounce(async function(element, field, productId = null) {
    try {
        const value = $(element).val();
        const isDuplicate = await checkDuplicateField(field, value, productId);
        
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

// Also add the same validation to the edit form fields
$('#edit_price').on('keypress', function(e) {
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    if (charCode == 46 && $(this).val().indexOf('.') != -1) {
        return false;
    }
    return true;
}).on('paste', function(e) {
    e.preventDefault();
    var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
    pastedData = pastedData.replace(/[^0-9.]/g, '');
    var parts = pastedData.split('.');
    pastedData = parts[0] + (parts.length > 1 ? '.' + parts[1] : '');
    $(this).val(pastedData);
});

$('#edit_quantity').on('keypress', function(e) {
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}).on('paste', function(e) {
    e.preventDefault();
    var pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
    pastedData = pastedData.replace(/[^0-9]/g, '');
    $(this).val(pastedData);
});

// Test the edit form fields for duplicates before populating the modal
$('#editProductModal').on('show.bs.modal', function() {
    // Clear any previous error messages
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
});

// Add input event listeners for real-time validation in edit form
$('#edit_product_name, #edit_sku, #edit_productCode').on('input', function() {
    // Remove error message and invalid class when user starts typing
    $(this).removeClass('is-invalid');
    $(this).next('.error-message').remove();
});

// Function to reset the form
function resetForm() {
    // Reset the form
    $("#addProductForm")[0].reset();
    
    // Clear image preview
    $("#imagePreview").attr("src", "assets/images/product_images/no-image.jpg").hide();
    $("#product_image").val('');
    
    // Reset Select2 dropdowns
    $('#category').val($('#category option:first').val()).trigger('change');
    $('#brand').val($('#brand option:first').val()).trigger('change');
    $('#availability').val('1').trigger('change');
}

// Actions Button Visibility Handling
let actionsBtn = $("#actionsBtn");

function updateActionsButton() {
    let checkedCount = selectedProductIds.length;
    let anyChecked = checkedCount > 0;
    
    // Show/hide the actions button
    actionsBtn.toggle(anyChecked);
    
    // Show/hide the edit option based on number of selections
    $('.edit-item').toggle(checkedCount === 1);
}

// Handle checkbox changes for individual products
$(document).on("change", "#productBody input[type='checkbox']", function() {
    const productId = $(this).val();
    const isChecked = $(this).prop("checked");
    
    if (isChecked && !selectedProductIds.includes(productId)) {
        selectedProductIds.push(productId);
    } else if (!isChecked && selectedProductIds.includes(productId)) {
        selectedProductIds = selectedProductIds.filter(id => id !== productId);
    }
    
    // Update the select all checkbox
    if (selectedProductIds.length === allProductIds.length && allProductIds.length > 0) {
        $("#selectAll").prop("checked", true);
        selectAllChecked = true;
    } else {
        $("#selectAll").prop("checked", false);
        selectAllChecked = false;
    }
    
    updateActionsButton();
});

// Initialize the page with all product IDs
function initializeAllProductIds() {
    const search = $("#searchBox").val();
    getAllProductIds(search).then(ids => {
        allProductIds = ids;
    }).catch(error => {
        console.error(error);
    });
}
    
// Update when search changes
$('#searchBox').on('keyup', function() {
    var searchText = $(this).val();
    loadProductTable(1, searchText);
    
    // Reset selections on search change
    selectedProductIds = [];
    selectAllChecked = false;
    $("#selectAll").prop("checked", false);
    
    // Update all product IDs for the new search
    initializeAllProductIds();
});

// Select all checkboxes across all pages
$("#selectAll").on("change", function () {
    selectAllChecked = $(this).prop("checked");
    
    if (selectAllChecked) {
        selectedProductIds = [...allProductIds]; // Select all products across pages
        
        // Check all checkboxes on current page
        $("#productBody input[type='checkbox']").prop("checked", true);
    } else {
        selectedProductIds = []; // Deselect all products
        
        // Uncheck all checkboxes on current page
        $("#productBody input[type='checkbox']").prop("checked", false);
    }
    
    updateActionsButton();
});

// When switching pages, update the checkboxes based on selectedProductIds
$(document).on("click", "#productFooter .page-link", function() {
    setTimeout(function() {
        // Check/uncheck boxes on the new page based on selectedProductIds
        $("#productBody input[type='checkbox']").each(function() {
            const productId = $(this).val();
            $(this).prop("checked", selectedProductIds.includes(productId));
        });
        
        // Update select all checkbox state
        $("#selectAll").prop("checked", selectAllChecked);
    }, 500); // Wait for page content to load
});

// Handle delete action from dropdown
$('.dropdown-menu .dropdown-item').click(function(e) {
    e.preventDefault();
    let action = $(this).text().trim();
    
    if(action === 'Delete') {
        if(selectedProductIds.length > 0) {
            $('#removeProductIds').val(JSON.stringify(selectedProductIds));
            
            // Get names for selected products (only for those visible on current page)
            let productNames = [];
            $("#productBody input[type='checkbox']:checked").each(function() {
                let productName = $(this).closest('tr').find('td:eq(2)').text().trim();
                productNames.push(productName);
            });
            
            // Update modal title and message based on selection count
            if(selectedProductIds.length === 1 && productNames.length === 1) {
                // Single product deletion
                $("#deleteProductTitle").html('<i class="ti ti-trash me-2"></i>Delete Product');
                $("#deleteProductMessage").html(`Are you sure you want to delete product <strong>${productNames[0]}</strong>? This action cannot be undone.`);
            } else {
                // Multiple product deletion
                $("#deleteProductTitle").html('<i class="ti ti-trash me-2"></i>Delete Multiple Products');
                $("#deleteProductMessage").html(`Are you sure you want to delete <strong>${selectedProductIds.length}</strong> selected products? This action cannot be undone.`);
            }
            
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

// Add event listeners for duplicate checking on edit form
$('#edit_product_name').on('blur', function() {
    const productId = $('#edit_product_id').val();
    debouncedCheckDuplicate(this, 'name', productId);
});

$('#edit_sku').on('blur', function() {
    const productId = $('#edit_product_id').val();
    debouncedCheckDuplicate(this, 'sku', productId);
});

$('#edit_productCode').on('blur', function() {
    const productId = $('#edit_product_id').val();
    debouncedCheckDuplicate(this, 'barcode', productId);
});

// Handle edit action from dropdown
$('.dropdown-menu .dropdown-item').click(function(e) {
    e.preventDefault();
    let action = $(this).text().trim();
    
    if(action === 'Edit') {
        // Get the productId from our selectedProductIds array (first one if multiple selected)
        if(selectedProductIds.length === 1) {
            let selectedProduct = selectedProductIds[0];
            
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
    
    // Check if there are any duplicate validation errors
    if (hasDuplicateErrors()) {
        return false;
    }
    
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
                
                // Reset selected IDs
                selectedProductIds = [];
                updateActionsButton();
                
                // Refresh the table to show updated data
                loadProductTable();
                initializeAllProductIds();
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

// Check if there are any duplicate validation errors
function hasDuplicateErrors() {
    return $('.error-message').filter(function() {
        return $(this).text().includes('already exists') || 
               $(this).text().includes('already in use') || 
               $(this).text().includes('already registered');
    }).length > 0;
}
</script>


