        <div class="page-content">
            <div class="page-container">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-18 text-uppercase fw-bold m-0">Dashboard</h4>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col">
                        <div class="row row-cols-xl-4 row-cols-md-2 row-cols-1 text-center">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total No. of Products</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:cart-3-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_products ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted">
                                           <i class="ti ti-info-circle"></i> <a href="#" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total Paid Orders</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_paid_orders ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted">
                                             <i class="ti ti-info-circle"></i> <a href="#" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Today's Earning</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold">₱5340.30 <small class="text-muted">PHP</small></h3>
                                        </div>
                                        <p class="mb-0 text-muted">
                                             <i class="ti ti-info-circle"></i> <a href="#" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Number of Users</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_users; ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted">
                                             <i class="ti ti-info-circle"></i> <a href="#" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->

<!--
                        <div class="row">
                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="d-flex card-header justify-content-between align-items-center">
                                        <h4 class="header-title">Brands Listing</h4>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-info">Add Brand <i class="ti ti-plus ms-1"></i></a>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="bg-success bg-opacity-10 py-1 text-center">
                                            <p class="m-0"><b>69</b> Active brands out of <span class="fw-medium">102</span></p>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-md flex-shrink-0 me-2">
                                                                    <span class="avatar-title bg-primary-subtle rounded-circle">
                                                                        <img src="assets/images/products/logo/logo-1.svg" alt="" height="22">
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-muted fs-12">Clothing</span> <br />
                                                                    <h5 class="fs-14 mt-1">Zaroan - Brazil</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Established</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">Since 2020</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Stores</span> <br />
                                                            <h5 class="fs-14 mt-1 fw-normal">1.5k</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Products</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">8,950</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Status</span>
                                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                        </td>
                                                        <td style="width: 30px;">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-md flex-shrink-0 me-2">
                                                                    <span class="avatar-title bg-info-subtle rounded-circle">
                                                                        <img src="assets/images/products/logo/logo-4.svg" alt="" height="22">
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-muted fs-12">Clothing</span> <br />
                                                                    <h5 class="fs-14 mt-1">Jocky-Johns - USA</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Established</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">Since 1985</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Stores</span> <br />
                                                            <h5 class="fs-14 mt-1 fw-normal">205</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Products</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">1,258</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Status</span>
                                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                        </td>
                                                        <td style="width: 30px;">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-md flex-shrink-0 me-2">
                                                                    <span class="avatar-title bg-secondary-subtle rounded-circle">
                                                                        <img src="assets/images/products/logo/logo-5.svg" alt="" height="22">
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-muted fs-12">Lifestyle</span> <br />
                                                                    <h5 class="fs-14 mt-1">Ginne - India</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Established</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">Since 2001</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Stores</span> <br />
                                                            <h5 class="fs-14 mt-1 fw-normal">89</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Products</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">338</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Status</span>
                                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                        </td>
                                                        <td style="width: 30px;">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-md flex-shrink-0 me-2">
                                                                    <span class="avatar-title bg-danger-subtle rounded-circle">
                                                                        <img src="assets/images/products/logo/logo-6.svg" alt="" height="22">
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-muted fs-12">Fashion</span> <br />
                                                                    <h5 class="fs-14 mt-1">DDoen - Brazil</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Established</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">Since 1995</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Stores</span> <br />
                                                            <h5 class="fs-14 mt-1 fw-normal">650</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Products</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">6,842</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Status</span>
                                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                        </td>
                                                        <td style="width: 30px;">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-md flex-shrink-0 me-2">
                                                                    <span class="avatar-title bg-primary-subtle rounded-circle">
                                                                        <img src="assets/images/products/logo/logo-8.svg" alt="" height="22">
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="text-muted fs-12">Manufacturing</span> <br />
                                                                    <h5 class="fs-14 mt-1">Zoddiak - Canada</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Established</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">Since 1963</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Stores</span> <br />
                                                            <h5 class="fs-14 mt-1 fw-normal">109</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Products</span>
                                                            <h5 class="fs-14 mt-1 fw-normal">952</h5>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fs-12">Status</span>
                                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i> Active</h5>
                                                        </td>
                                                        <td style="width: 30px;">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 
                                </div> 

                            </div>

                        </div>  
-->

                    </div> <!-- container -->

         <script type="text/javascript">
          $(document).ready(function() {
            $("#nav-item-dashboard").addClass('active');
            $("#nav-link-dashboard").addClass('active');
          }); 
         </script>

 