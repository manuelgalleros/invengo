<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Activity Logs</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Activity Logs</li>
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
                                <input type="text" id="searchBox" class="form-control ps-4" placeholder="Search activity logs" style="width: 100%;">
                                <i class="ti ti-search position-absolute top-50 translate-middle-y ms-2"></i>
                            </div>
                            <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light" id="showLogsBtn"><i class="ti ti-eye align-middle me-1 fs-18"></i> Show Logs</button>
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <input type="text" class="form-control flatpickr-input" id="dateRangePicker" data-provider="flatpickr" data-date-format="d M" data-range-date="true" placeholder="Select date range" readonly="readonly">
                                        <span class="input-group-text bg-primary border-primary text-white">
                                            <i class="ti ti-calendar fs-15"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0" id="logsTable">
                            <thead class="bg-dark-subtle" id="logsTableHead">
                                <tr>
                                    <th class="text-center" style="width: 60px;">ID</th>
                                    <th style="width: 15%;">User</th>
                                    <th style="width: 10%;">Action</th>
                                    <th>Description</th>
                                    <th style="width: 20%;">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody id="logsBody">
                                <!-- Logs will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer" id="logsFooter">
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

<script>
$(document).ready(function() {
    // Only hide the table body and footer initially
    
    // Show logs button click handler
    $("#showLogsBtn").on('click', function() {
        // Load logs data (this will show the table)
        loadLogs();
        // Hide the Show Logs button after showing the table
        $(this).hide();
    });
    
    // Initialize flatpickr date picker
    flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "d M",
        defaultDate: [new Date().setDate(1), new Date()],
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                loadLogs();
            }
        }
    });
    
    // Search functionality
    let searchTimer;
    $('#searchBox').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            loadLogs(1);
        }, 500);
    });
    
    // Function to load logs with pagination
    function loadLogs(page = 1) {
        var searchQuery = $('#searchBox').val();
        var dateRange = $('#dateRangePicker').val();
        
        $.ajax({
            url: '<?php echo base_url('logs/fetchLogs') ?>',
            type: 'get',
            data: {
                page: page,
                search: searchQuery,
                date_range: dateRange
            },
            dataType: 'json',
            success: function(response) {
                if (!response.success) {
                    $('#messages').html(`
                        <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
                            <div class="lh-1">${response.message}</div>
                        </div>
                    `);
                    return;
                }
                
                var logs = response.logs;
                var totalPages = response.totalPages;
                var currentPage = response.currentPage;
                
                // Show the table body and footer
                $("#logsBody tr, #logsFooter").fadeIn();
                
                // Update logs table
                var html = '';
                if (logs.length > 0) {
                    for (var i = 0; i < logs.length; i++) {
                        html += '<tr>';
                        html += '<td class="text-center">' + logs[i].id + '</td>';
                        html += '<td>' + logs[i].user + '</td>';
                        html += '<td><span class="badge bg-' + getActionBadgeClass(logs[i].action) + '">' + logs[i].action + '</span></td>';
                        html += '<td class="text-wrap">' + logs[i].description + '</td>';
                        html += '<td>' + logs[i].date + '</td>';
                        html += '</tr>';
                    }
                } else {
                    html = '<tr><td colspan="6" class="text-center">No logs found</td></tr>';
                }
                $('#logsBody').html(html);
                
                // Update pagination
                updatePagination(currentPage, totalPages);
                
                // Update range info
                var start = (currentPage - 1) * 10 + 1;
                var end = Math.min(start + 9, response.totalRecords);
                var rangeHtml = '<div>Showing ' + start + ' to ' + end + ' of ' + response.totalRecords + ' logs</div>';
                $('#logsFooter .text-muted').html(rangeHtml);
            },
            error: function() {
                $('#logsBody').html('<tr><td colspan="6" class="text-center text-danger">Error loading logs</td></tr>');
            }
        });
    }
    
    // Function to get badge class based on action type
    function getActionBadgeClass(action) {
        switch(action.toLowerCase()) {
            case 'create':
                return 'success';
            case 'update':
                return 'info';
            case 'delete':
                return 'danger';
            case 'login':
                return 'primary';
            case 'logout':
                return 'secondary';
            case 'clear':
                return 'warning';
            case 'archive':
                return 'warning';
            case 'restore':
                return 'secondary';
            default:
                return 'dark';
        }
    }
    
    // Function to update pagination
    function updatePagination(currentPage, totalPages) {
        var html = '';
        
        // First page button
        html += '<li class="page-item ' + (currentPage === 1 ? "disabled" : "") + '">';
        html += '<a href="#" class="page-link" data-page="1"><i class="ti ti-chevrons-left"></i></a>';
        html += '</li>';
        
        // Previous button
        html += '<li class="page-item ' + (currentPage === 1 ? "disabled" : "") + '">';
        html += '<a href="#" class="page-link" data-page="' + (currentPage - 1) + '">Previous</a>';
        html += '</li>';
        
        // Page numbers
        var startPage = Math.max(1, currentPage - 2);
        var endPage = Math.min(totalPages, currentPage + 2);
        
        if (currentPage <= 3) {
            endPage = Math.min(5, totalPages);
        }
        if (currentPage > totalPages - 2) {
            startPage = Math.max(totalPages - 4, 1);
        }
        
        for (var i = startPage; i <= endPage; i++) {
            html += '<li class="page-item ' + (i === currentPage ? "active" : "") + '">';
            html += '<a href="#" class="page-link" data-page="' + i + '">' + i + '</a>';
            html += '</li>';
        }
        
        // Next button
        html += '<li class="page-item ' + (currentPage === totalPages || totalPages === 0 ? "disabled" : "") + '">';
        html += '<a href="#" class="page-link" data-page="' + (currentPage + 1) + '">Next</a>';
        html += '</li>';
        
        // Last page button
        html += '<li class="page-item ' + (currentPage === totalPages || totalPages === 0 ? "disabled" : "") + '">';
        html += '<a href="#" class="page-link" data-page="' + totalPages + '"><i class="ti ti-chevrons-right"></i></a>';
        html += '</li>';
        
        $('#logsFooter .pagination').html(html);
        
        // Attach click event to pagination links
        $('#logsFooter .pagination a').on('click', function(e) {
            e.preventDefault();
            if (!$(this).parent().hasClass('disabled')) {
                var page = $(this).data('page');
                loadLogs(page);
            }
        });
    }
});
</script>
