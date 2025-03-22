<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Log In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico">
    <script src="<?php echo base_url()?>assets/js/config.js"></script>
    <link href="<?php echo base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="<?php echo base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                <a href="index.html" class="auth-brand mb-3" style="padding-top: 1rem">
                        <img src="<?php echo base_url()?>assets/images/invengo.png" alt="dark logo" width="160" class="logo-dark">
                        <img src="<?php echo base_url()?>assets/images/invengo.png" alt="logo light" height="160" class="logo-light">
                 </a>

                <h4 class="fw-semibold mb-2">Login your account</h4>

                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                <?php if (validation_errors() || !empty($errors)): ?>
                    <?php
                    $error_messages = validation_errors();

                    if (!empty($errors) && is_array($errors)) {
                        foreach ($errors as $error) {
                            echo '<div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                                    <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                    <div class="lh-1">' . $error . '</div>
                                  </div>';
                        }
                    }
                    ?>
                <?php endif; ?>

                <form action="<?php echo base_url('auth/login') ?>" method="POST" class="text-start mb-3">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input id="email" name="email" class="form-control" placeholder="Enter your email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-info" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="<?php echo base_url()?>assets/js/vendor.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/app.js"></script>

</body>

</html>