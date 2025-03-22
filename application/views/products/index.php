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
                            <div class="position-relative">
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search for a product">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <?php if(in_array('createProduct', $user_permission)): ?>
                                <a href="<?php echo base_url('products/create'); ?>" class="btn btn-info">
                                    <i class="ti ti-plus me-1"></i> Add Product
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0" id="productTable">
                            <thead class="bg-dark-subtle">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>SKU</th>
                                    <th style="text-align: center">Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Availability</th>
                                    <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                        <th class="text-center" style="width: 120px;">Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="productBody">
                                <?php foreach ($products as $product): ?>
                                    <tr id="row_<?php echo $product['id']; ?>">
                                        <td class="ps-3"><input type="checkbox" class="form-check-input" value="<?php echo $product['id']; ?>"></td>
                                        <td><?php echo $product['sku']; ?></td>
                                        <td>
                                         <div class="d-flex justify-content-start align-items-center gap-3">
                                         <div class="avatar-md">
                                         <img src="<?php echo base_url(); echo $product['image'];?>" alt="<?php echo $product['name'];?>" class="img-fluid rounded-2">
                                         </div>
                                         <?php echo $product['name']; ?>
                                        </div>
                                        </td>
                                        <td><?php echo $product['description']; ?></td>
                                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $product['qty'] > 10 ? 'badge-soft-success' : 'badge-soft-danger'; ?>">
                                                <?php echo $product['qty']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $product['category_name']; ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $product['availability'] ? 'badge-soft-success' : 'badge-soft-danger'; ?>">
                                                <?php echo $product['availability'] ? 'Available' : 'Out of Stock'; ?>
                                            </span>
                                        </td>
                                        
                                        <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                        
                                            <td class="pe-3">
                                                <div class="hstack gap-1 justify-content-end">
                                                <?php if(in_array('updateProduct', $user_permission)): ?>
                                                    <a href="<?php echo base_url('products/edit/'.$product['id']); ?>" class="btn btn-soft-success btn-icon btn-sm rounded-circle"> <i class="ti ti-edit fs-16"></i></a>
                                                <?php endif; ?>
                                                <?php if(in_array('deleteProduct', $user_permission)): ?>
                                                    <a onclick="removeProduct(<?php echo $product['id']; ?>)" type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"> <i class="ti ti-trash"></i></a>
                                                <?php endif; ?>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                        <div class="card-footer">
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
</div>

<script>
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
