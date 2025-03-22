<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/images/favicon.ico">

    <!-- Stylesheets -->
    <link href="<?php echo base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="<?php echo base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/vendor/quill/quill.core.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/vendor/quill/quill.snow.css" rel="stylesheet" type="text/css" />

    <!-- jQuery -->
    <script src="<?php echo base_url()?>assets/js/vendor.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Bootstrap 5 Integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom Config -->
    <script src="<?php echo base_url()?>assets/js/config.js"></script>


    <script>
      var myVar = setInterval(function() {
        myTimer();
      }, 1000);

      function myTimer() {
        var d = new Date();
        document.getElementById("time").innerHTML = d.toLocaleTimeString("en-US", {
          timeZone: "Asia/Manila",
        });
      }
    </script>
    <style>
        .solar--heart-bold {
          display: inline-block;
          width: 24px;
          height: 24px;
          background-repeat: no-repeat;
          background-size: 100% 100%;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23e94848' d='M2 9.137C2 14 6.02 16.591 8.962 18.911C10 19.729 11 20.5 12 20.5s2-.77 3.038-1.59C17.981 16.592 22 14 22 9.138S16.5.825 12 5.501C7.5.825 2 4.274 2 9.137'/%3E%3C/svg%3E");
          vertical-align: middle;
        }
        .solar--users-group-two-rounded-outline {
          display: inline-block;
          width: 22px;
          height: 22px;
          --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' fill-rule='evenodd' d='M12 1.25a4.75 4.75 0 1 0 0 9.5a4.75 4.75 0 0 0 0-9.5M8.75 6a3.25 3.25 0 1 1 6.5 0a3.25 3.25 0 0 1-6.5 0' clip-rule='evenodd'/%3E%3Cpath fill='%23000' d='M18 3.25a.75.75 0 0 0 0 1.5c1.377 0 2.25.906 2.25 1.75S19.377 8.25 18 8.25a.75.75 0 0 0 0 1.5c1.937 0 3.75-1.333 3.75-3.25S19.937 3.25 18 3.25M6.75 4A.75.75 0 0 0 6 3.25c-1.937 0-3.75 1.333-3.75 3.25S4.063 9.75 6 9.75a.75.75 0 0 0 0-1.5c-1.376 0-2.25-.906-2.25-1.75S4.624 4.75 6 4.75A.75.75 0 0 0 6.75 4'/%3E%3Cpath fill='%23000' fill-rule='evenodd' d='M12 12.25c-1.784 0-3.434.48-4.659 1.297c-1.22.814-2.091 2.02-2.091 3.453s.871 2.64 2.091 3.453c1.225.816 2.875 1.297 4.659 1.297s3.434-.48 4.659-1.297c1.22-.814 2.091-2.02 2.091-3.453s-.872-2.64-2.091-3.453c-1.225-.816-2.875-1.297-4.659-1.297M6.75 17c0-.776.472-1.57 1.423-2.204c.947-.631 2.298-1.046 3.827-1.046c1.53 0 2.88.415 3.827 1.046c.951.634 1.423 1.428 1.423 2.204s-.472 1.57-1.423 2.204c-.947.631-2.298 1.046-3.827 1.046c-1.53 0-2.88-.415-3.827-1.046C7.222 18.57 6.75 17.776 6.75 17' clip-rule='evenodd'/%3E%3Cpath fill='%23000' d='M19.267 13.84a.75.75 0 0 1 .894-.573c.961.211 1.828.592 2.472 1.119c.643.526 1.117 1.25 1.117 2.114c0 .865-.474 1.588-1.117 2.114c-.644.527-1.51.908-2.472 1.119a.75.75 0 0 1-.322-1.466c.793-.173 1.426-.472 1.844-.814s.567-.677.567-.953s-.149-.61-.567-.953s-1.051-.64-1.844-.814a.75.75 0 0 1-.572-.894M3.84 13.267a.75.75 0 1 1 .32 1.466c-.792.173-1.425.472-1.843.814s-.567.677-.567.953s.149.61.567.953s1.051.64 1.844.814a.75.75 0 0 1-.322 1.466c-.962-.211-1.828-.592-2.472-1.119C.724 18.088.25 17.364.25 16.5c0-.865.474-1.588 1.117-2.114c.644-.527 1.51-.908 2.472-1.119'/%3E%3C/svg%3E");
          background-color: currentColor;
          -webkit-mask-image: var(--svg);
          mask-image: var(--svg);
          -webkit-mask-repeat: no-repeat;
          mask-repeat: no-repeat;
          -webkit-mask-size: 100% 100%;
          mask-size: 100% 100%;
        }
    </style>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">


