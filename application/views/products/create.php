        <div class="page-content">

            <!-- Start Content-->
            <div class="page-container">

                
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold mb-0">Add Products</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                            
                            <li class="breadcrumb-item active">Add Products</li>
                        </ol>
                    </div>
                </div>
                

                

                <div class="row">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title" style="margin-top: 10px">Product Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productName" class="form-label">Product Name</label>
                                            <input type="text" class="form-control" id="productName" placeholder="Enter product name" required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="skuId" class="form-label">SKU</label>
                                            <input type="text" class="form-control" id="skuId" placeholder="Enter SKU" required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Product Description</label>
                                            <textarea class="form-control" id="description" rows="7" placeholder="Enter a short description about the product"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productCode" class="form-label">Product Barcode</label>
                                            <input type="text" class="form-control" id="productCode" placeholder="Enter barcode" required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productCategory" class="form-label">Product Category</label>
                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="productCategory">
                                                <option>Select Category</option>
                                                <option>Electronics</option>
                                                <option>Mobile Accessories</option>
                                                <option>Games</option>
                                                <option>Sports</option>
                                                <option>Watches</option>
                                                <option>Bags</option>
                                                <option>Toys</option>
                                                <option>Cloth's</option>
                                                <option>Shoes</option>
                                                <option>Fashion</option>
                                                <option>Furniture</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productBrand" class="form-label">Product Brand</label>
                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="productBrand">
                                                <option>Select Brand</option>
                                                <option>Sony</option>
                                                <option>Canon</option>
                                                <option>Snitch</option>
                                                <option>Titan</option>
                                                <option>JCB</option>
                                                <option>Wood</option>
                                                <option>Apple</option>
                                                <option>Nike</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productPrice" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="productPrice" placeholder="₱00.00" required="">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basic-datepicker" class="form-label">Quantity</label>
                                            <input type="text" id="basic-datepicker" class="form-control" placeholder="Enter quantity">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="productStatus" class="form-label">Availability</label>
                                            <select class="form-select my-1 my-md-0 me-sm-3" data-toggle="select2" id="productStatus">
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

                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title" style="margin-top: 10px">Product Gallery</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <form action="/" method="post" class="dropzone" id="myAwesomeDropzone"
    data-plugin="dropzone" 
    data-previews-container="#file-previews" 
    data-upload-preview-template="#uploadPreviewTemplate">
    
    <div class="dz-message needsclick">
        <i class="h1 ti ti-cloud-upload mb-4"></i>
        <h4>Drop files here or click to upload.</h4>
        <span class="text-muted fs-13">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
    </div>
</form>


                                    <!-- Preview -->
                                    <div class="dropzone-previews mt-3" id="file-previews"></div>

                                    <!-- file preview template -->
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
                                                        <!-- Button -->
                                                        <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
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
                                    <a href="#!" class="btn btn-info">Add New Product</a>
                                    <a href="<?php echo base_url("/products")?>" class="btn btn-danger">Go Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container -->
    
 

<script type="text/javascript">
    Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#myAwesomeDropzone", {
    paramName: "file", // The name that will be used for the file transfer
    maxFilesize: 5, // MB
    acceptedFiles: "image/*,application/pdf",
});

  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainProductNav").addClass('active');
    $("#addProductNav").addClass('active');
    
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
    $("#product_image").fileinput({
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-1',
        msgErrorClass: 'alert alert-block alert-danger',
        // defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar">',
        layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
        allowedFileExtensions: ["jpg", "png", "gif"]
    });

  });
</script>