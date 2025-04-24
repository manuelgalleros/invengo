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
                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Products">Total No. of Products</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1 flex-grow-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:cart-3-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_products ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted mt-auto">
                                           <i class="ti ti-info-circle"></i> <a href="<?php echo base_url('products/') ?>" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Total Paid Orders</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1 flex-grow-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_paid_orders ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted mt-auto">
                                             <i class="ti ti-info-circle"></i> <a href="<?php echo base_url('orders/') ?>" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Today's Earnings">Today's Earning</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1 flex-grow-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold">₱<?php echo number_format($todays_earnings, 2); ?> <small class="text-muted">PHP</small></h3>
                                        </div>
                                        <p class="mb-0 text-muted mt-auto">
                                             <i class="ti ti-info-circle"></i> <a href="<?php echo base_url('orders/') ?>" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="text-muted fs-13 text-uppercase" title="Number of Users">Number of Users</h5>
                                        <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1 flex-grow-1">
                                            <div class="user-img fs-42 flex-shrink-0">
                                                <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 fw-bold"><?php echo $total_users; ?></h3>
                                        </div>
                                        <p class="mb-0 text-muted mt-auto">
                                             <i class="ti ti-info-circle"></i> <a href="<?php echo base_url('users/') ?>" class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">View more</a>
                                        </p>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->

                        <!-- Monthly Earnings Chart -->
                        <div class="row">
                            <div class="col-xxl-8 col-xl-7 col-lg-7">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Monthly Earnings</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="monthly-earnings-chart" class="apex-charts" dir="ltr" style="height: 380px;"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Categories Chart -->
                            <div class="col-xxl-4 col-xl-5 col-lg-5">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Product Categories</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="product-categories-chart" class="apex-charts" dir="ltr" style="height: 380px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Recent Activity</h4>
                                        <a href="<?php echo base_url('logs/') ?>" class="btn btn-sm btn-info">View All</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Action</th>
                                                        <th>Description</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($recent_activities)): ?>
                                                        <?php foreach($recent_activities as $activity): ?>
                                                            <tr>
                                                                <td><?php echo $activity['user']; ?></td>
                                                                <td>
                                                                    <span class="badge <?php
                                                                        if($activity['action'] == 'Create') echo 'bg-success';
                                                                        elseif($activity['action'] == 'Update') echo 'bg-warning';
                                                                        elseif($activity['action'] == 'Delete') echo 'bg-danger';
                                                                        elseif($activity['action'] == 'Archive') echo 'bg-info';
                                                                        elseif($activity['action'] == 'Restore') echo 'bg-primary';
                                                                        else echo 'bg-secondary';
                                                                    ?>">
                                                                        <?php echo $activity['action']; ?>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo $activity['description']; ?></td>
                                                                <td><?php echo $activity['date']; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No recent activities found</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- container -->

         <script type="text/javascript">
          $(document).ready(function() {
            $("#nav-item-dashboard").addClass('active');
            $("#nav-link-dashboard").addClass('active');
            
            // Initialize Monthly Earnings Chart
            var monthlyOptions = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '45%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Monthly Earnings',
                    data: <?php echo json_encode($monthly_earnings['earnings']); ?>
                }],
                xaxis: {
                    categories: <?php echo json_encode($monthly_earnings['months']); ?>,
                },
                yaxis: {
                    title: {
                        text: 'Revenue'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "₱ " + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        }
                    }
                },
                colors: ['rgb(14, 165, 233)']
            }
            
            var monthlychart = new ApexCharts(
                document.querySelector("#monthly-earnings-chart"),
                monthlyOptions
            );
            monthlychart.render();
            
            // Initialize Product Categories Chart
            var categoryOptions = {
                chart: {
                    height: 420,
                    type: 'pie',
                },
                labels: <?php echo json_encode($product_categories['categories']); ?>,
                series: <?php echo json_encode($product_categories['counts']); ?>,
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                colors: ['#60adde', '#313a46', '#ebb751', '#ed6060', '#70bb63']
            }
            
            var categorychart = new ApexCharts(
                document.querySelector("#product-categories-chart"),
                categoryOptions
            );
            categorychart.render();
          }); 
         </script>

 