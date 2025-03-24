<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Products</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
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
                            <div class="position-relative" id="searchBar" style="flex-grow: 1; max-width: 400px; display:none;">
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a product" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-1">
                                <?php if(in_array('viewProduct', $user_permission)): ?>
                                    <button type="button" class="btn btn-light" id="showProductsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Products</button>
                                <?php endif; ?>
                                <?php if(in_array('createProduct', $user_permission)): ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#addProductModal" class="btn btn-soft-info"><i class="ti ti-plus me-1"></i> Add Product</button>
                                <?php endif; ?>
                                <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="actionsBtn" style="display: none">Actions <span class="caret"></span></button>
                                  <div class="dropdown-menu" style="">
                                    <?php if(in_array('updateProduct', $user_permission)): ?>
                                     <a class="dropdown-item" href="#"><i class="ti ti-edit"></i> &nbsp;Modify</a>
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
                            <thead class="bg-dark-subtle" id="productTableHead" style="display: none;">
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
                                
                            </tbody>
                        </table>
                    </div>
                        <div class="card-footer" id="productFooter" style="display: none;">
                                <div class="d-flex justify-content-end">
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a href="#" class="page-link"><i class="ti ti-chevrons-left"></i></a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="#" class="page-link">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link"><i class="ti ti-chevrons-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container -->
    
    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addProductModalLabel">Add New Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title" style="margin-top: 10px">Product Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productName" class="form-label">Product Name</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="skuId" class="form-label">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter SKU" required="">
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
                                            <input type="text" class="form-control" id="productCode" name="barcode" placeholder="Enter barcode" required="">
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
                                                <option>Available</option>
                                                <option>Out of Stock</option>
                                                <option>Discontinued</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title" style="margin-top: 10px">Product Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="dropzone" id="dropzone">
                                        <div class="dz-message needsclick">
                                            <i class="h1 ti ti-cloud-upload mb-4"></i>
                                            <h4>Drop files here or click to upload.</h4>
                                            <span class="text-muted fs-13">(Upload product images, max 5MB.)</span>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="dropzone-previews mt-3" id="file-previews"></div>

                                    <!-- File preview template -->
                                    <div class="d-none" id="uploadPreviewTemplate">
                                        <div class="card mt-1 mb-0 shadow-none border">
                                            <div class="p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                                    </div>
                                                    <div class="col ps-0">
                                                        <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                                        <p class="mb-0" data-dz-size></p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="javascript:void(0);" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                                            <i class="ti ti-x"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                    <!-- end file preview template -->
                                </div>
                            </div>
                            <div class="card-footer border-top border-dashed text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Cancel</button>
                                    <button type="button" class="btn btn-info" id="addProductBtn">Add New Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    
    
    <!-- Delete Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Remove Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" id="removeForm">
                    <div class="modal-body">
                        <p>Do you really want to remove this product?</p>
                        <input type="hidden" id="removeProductId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>


  Dropzone.autoDiscover = false;

        let myDropzone = new Dropzone("#dropzone", {
        url: "#", // No AJAX, handled by form submission
        maxFiles: 5,
        maxFilesize: 2,
        acceptedFiles: "image/*",
        addRemoveLinks: false,
        autoProcessQueue: false,
        dictRemoveFile: "Remove",
        previewsContainer: "#file-previews",
        previewTemplate: document.querySelector("#uploadPreviewTemplate").innerHTML,
        init: function() {
        let dropzoneInstance = this;

        document.querySelector("form").addEventListener("submit", function(e) {
            if (dropzoneInstance.files.length > 0) {
                let fileInput = document.getElementById("fileInput");
                let file = dropzoneInstance.files[0];
                
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            }
         });
      }
   });
    
    // Fix the select2 component inside the modal
    $(document).ready(function () {
        // Initialize Select2
        $('#category, #brand, #availability').select2({
            dropdownParent: $('#addProductModal')
        });

        // Fix the issue where typing is not possible in the Select2 search box inside a modal
        $(document).on('select2:open', () => {
            setTimeout(() => {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        // Fix Bootstrap modal focus trapping
        $(document).on('select2:open', function (e) {
            let modal = $(e.target).closest('.modal');
            if (modal.length) {
                modal.on('shown.bs.modal', function () {
                    setTimeout(() => {
                        $('.select2-search__field').prop('focus', true);
                    }, 100);
                });
            }
        });
    });



    
// Fetch the products
$(document).ready(function() {
    $("#showProductsBtn").click(function() {
        $.ajax({
            url: "<?php echo base_url('products/get_products'); ?>", 
            type: "GET",
            dataType: "html",
            success: function(response) {
                $("#productBody").html(response); 
                $("#productTableHead, #productFooter, #searchBar").fadeIn();
                $("#showProductsBtn").hide();
            },
            error: function() {
                alert("Failed to load products. Please try again.");
            }
        });
    });
});
    
    
$(document).ready(function () {
    let actionsBtn = $("#actionsBtn");

    // Function to update the Actions button visibility
    function updateActionsButton() {
        let anyChecked = $("#productBody input[type='checkbox']:checked").length > 0;
        if (anyChecked) {
            actionsBtn.show(); // Show Actions button
        } else {
            actionsBtn.hide(); // Hide Actions button
        }
    }

    // Event delegation for checkboxes inside dynamically loaded table rows
    $(document).on("change", "#productBody input[type='checkbox']", updateActionsButton);

    // "Select All" functionality
    $("#selectAll").on("change", function () {
        let isChecked = $(this).prop("checked");
        $("#productBody input[type='checkbox']").prop("checked", isChecked);
        updateActionsButton();
    });
    
    

    // Add Product AJAX Request
    $("#addProductBtn").click(function() {
        // Create FormData object
        let formData = new FormData();
        
        // Add all form fields to FormData
        formData.append('product_name', $('#product_name').val());
        formData.append('sku', $('#sku').val());
        formData.append('description', $('#description').val());
        formData.append('barcode', $('#productCode').val());
        formData.append('category', $('#category').val());
        formData.append('brand', $('#brand').val());
        formData.append('price', $('#price').val());
        formData.append('quantity', $('#quantity').val());
        formData.append('availability', $('#availability').val());

        // Add image file if exists
        if (myDropzone.files.length > 0) {
            formData.append('product_image', myDropzone.files[0]);
        }

        // Send AJAX request
        $.ajax({
            url: "<?php echo base_url('products/create'); ?>",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $('#messages').html('<div class="alert alert-success">Product added successfully</div>');
                    
                    // Reset form and close modal
                    $('#addProductModal').modal('hide');
                    $('#product_name, #sku, #description, #productCode, #price, #quantity').val('');
                    $('#category, #brand, #availability').val('').trigger('change');
                    
                    // Clear dropzone
                    myDropzone.removeAllFiles();
                    
                    // Refresh product list if visible
                    if ($("#productTableHead").is(":visible")) {
                        $("#showProductsBtn").click();
                    }
                } else {
                    // Show error message
                    $('#messages').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                // Show error message
                $('#messages').html('<div class="alert alert-danger">Error adding product. Please try again.</div>');
                console.error('Error:', error);
            }
        });
    });
});

// Search functionality
$('#searchBox').on('keyup', function() {
    var searchText = $(this).val().toLowerCase();
    $('#productBody tr').each(function() {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(searchText) > -1);
    });
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

// Handle delete request
$('#removeForm').on('submit', function(e) {
    e.preventDefault();
    var productId = $('#removeProductId').val();
    
    $.ajax({
        url: "<?php echo base_url('products/remove'); ?>",
        type: 'POST',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#row_' + productId).remove();
                $('#removeModal').modal('hide');
                $('#messages').html('<div class="alert alert-success">Product removed successfully</div>');
            } else {
                $('#messages').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        }
    });
});
</script>
