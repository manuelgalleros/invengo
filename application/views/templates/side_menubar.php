        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="<?php echo base_url("/dashboard")?>" class="logo">
                <span class="logo-light">
                    <span class="logo-lg text-center"> <img src="<?php echo base_url()?>assets/images/FullLogo_Transparent_NoBuffer.png" alt="logo" height="90" width="100"></span>
                    <span class="logo-sm text-center"><img src="<?php echo base_url()?>assets/images/FullLogo_Transparent_NoBuffer.png" alt="small logo"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="<?php echo base_url()?>assets/images/logo-dark.png" alt="dark logo"></span>
                    <span class="logo-sm text-center"><img src="<?php echo base_url()?>assets/images/logo-sm.png" alt="small logo"></span>
                </span>
            </a>
            <button class="button-sm-hover">
                <i class="ti ti-circle align-middle"></i>
            </button>

            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>

            <div data-simplebar>

                <!--- Sidenav Menu -->
                <ul class="side-nav">

                    <li class="side-nav-item" id="nav-item-dashboard">
                        <a href="<?php echo base_url("/dashboard")?>" class="side-nav-link" id="nav-link-dashboard">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Manage</li>

                    <?php if(in_array('createProduct', $user_permission) || in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProducts" aria-expanded="false" aria-controls="sidebarProducts" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-basket-filled"></i></span>
                            <span class="menu-text"> Products </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProducts">
                            <ul class="sub-menu">
                                <?php if(in_array('createProduct', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url('products/create') ?>" class="side-nav-link">
                                        <span class="menu-text">Add New Product</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(in_array('updateProduct', $user_permission) || in_array('viewProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url('products') ?>" class="side-nav-link">
                                        <span class="menu-text">Manage Products</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array('createOrder', $user_permission) || in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarOrders" aria-expanded="false" aria-controls="sidebarOrders" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="menu-text"> Orders</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarOrders">
                            <ul class="sub-menu">
                                <?php if(in_array('createOrder', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url('orders/create') ?>" class="side-nav-link">
                                        <span class="menu-text">Create New Order</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(in_array('updateOrder', $user_permission) || in_array('viewOrder', $user_permission) || in_array('deleteOrder', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url('orders') ?>" class="side-nav-link">
                                        <span class="menu-text">Manage Orders</span>
                                    </a>
                                </li>
                                 <?php endif; ?>
                                 <?php if(in_array('deleteOrder', $user_permission)): ?>
                                 <li class="side-nav-item">
                                    <a href="<?php echo base_url('orders/archive') ?>" class="side-nav-link">
                                        <span class="menu-text">Archived Orders</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array('createBrand', $user_permission) || in_array('updateBrand', $user_permission) || in_array('viewBrand', $user_permission) || in_array('deleteBrand', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url("/brands")?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-hexagons"></i></span>
                            <span class="menu-text"> Brands </span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array('createCategory', $user_permission) || in_array('updateCategory', $user_permission) || in_array('viewCategory', $user_permission) || in_array('deleteCategory', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url("/category")?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-category"></i></span>
                            <span class="menu-text"> Category </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php 
                    if (!empty($user_permission) && is_array($user_permission)): ?>
                        <?php if (array_intersect(['createUser', 'updateUser', 'viewUser', 'deleteUser'], $user_permission)): ?>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false" aria-controls="sidebarUsers" class="side-nav-link">
                                    <span class="menu-icon"><i class="ti ti-users"></i></span>
                                    <span class="menu-text"> Users </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarUsers">
                                    <ul class="sub-menu">
                                        <?php if (in_array('createUser', $user_permission)): ?>
                                            <li class="side-nav-item">
                                                <a href="<?php echo base_url("/users/create") ?>" class="side-nav-link">
                                                    <span class="menu-text">Create New User</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (array_intersect(['updateUser', 'viewUser', 'deleteUser'], $user_permission)): ?>
                                            <li class="side-nav-item">
                                                <a href="<?php echo base_url("/users") ?>" class="side-nav-link">
                                                    <span class="menu-text">Manage Users</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    <?php if(in_array('createGroup', $user_permission) || in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                      <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarGroups" aria-expanded="false" aria-controls="sidebarGroups" class="side-nav-link">
                            <span class="menu-icon"><i class="solar--users-group-two-rounded-outline"></i></span>
                            <span class="menu-text"> Groups </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarGroups">
                            <ul class="sub-menu">
                                 <?php if(in_array('createGroup', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url("/groups/create")?>" class="side-nav-link">
                                        <span class="menu-text">Create New Group</span>
                                    </a>
                                </li>
                                 <?php endif; ?>
                                <?php if(in_array('updateGroup', $user_permission) || in_array('viewGroup', $user_permission) || in_array('deleteGroup', $user_permission)): ?>
                                <li class="side-nav-item">
                                    <a href="<?php echo base_url("/groups")?>" class="side-nav-link">
                                        <span class="menu-text">Manage Groups</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>    
                    <?php endif; ?>
                    
                    <?php if(in_array('viewReports', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url('reports') ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-report"></i></span>
                            <span class="menu-text"> Reports </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    
                    <?php if(in_array('viewProfile', $user_permission) || in_array('updateSetting', $user_permission)): ?>
                    <li class="side-nav-title mt-2">My Account</li>
                    <?php endif; ?>

                    <?php if(in_array('viewProfile', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url('users/profile') ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-user-circle"></i></span>
                            <span class="menu-text"> View Profile </span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array('updateSetting', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url('users/setting') ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-settings"></i></span>
                            <span class="menu-text"> Account Settings </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="side-nav-title mt-2">System</li>
                    <?php if(in_array('viewLog', $user_permission)): ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url('logs') ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-logs"></i></span>
                            <span class="menu-text"> Activity Logs </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="side-nav-item">
                        <a href="<?php echo base_url('settings') ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-adjustments-alt"></i></span>
                            <span class="menu-text"> System Settings </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Sidenav Menu End -->