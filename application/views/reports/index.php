<!-- Content Wrapper. Contains page content -->
<div class="page-content">
  <div class="page-container">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
      <div class="flex-grow-1">
        <h4 class="fs-18 text-uppercase fw-bold mb-0">View Reports</h4>
      </div>
      <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
          <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>">Home</a></li>
          <li class="breadcrumb-item active">Reports</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div id="messages">
          <?php
          // Check and display flash messages
          $success = $this->session->flashdata('success');
          $error = $this->session->flashdata('error');
          
          // Clear flash messages immediately after retrieving them
          $this->session->unset_userdata('success');
          $this->session->unset_userdata('error');
          
          if($success): ?>
            <div class="alert alert-success text-bg-success alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              <iconify-icon icon="solar:check-read-line-duotone" class="fs-20 me-1"></iconify-icon>
              <div class="lh-1"><?php echo $success; ?></div>
            </div>
          <?php endif; ?>

          <?php if($error): ?>
            <div class="alert alert-danger text-bg-danger alert-dismissible d-flex align-items-center auto-dismiss" role="alert">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              <iconify-icon icon="solar:danger-triangle-bold-duotone" class="fs-20 me-1"></iconify-icon>
              <div class="lh-1"><?php echo $error; ?></div>
            </div>
          <?php endif; ?>
        </div>

        <div class="card">
          <div class="card-header border-bottom">
            <div class="d-flex flex-wrap justify-content-between gap-2">
              <h5 class="card-title mb-0 d-flex align-items-center">Total Paid Orders - Graph</h5>
              <form class="d-flex align-items-center" action="<?php echo base_url('reports/') ?>" method="POST">
                <div class="d-flex align-items-center">
                  <label for="select_year" class="form-label mb-0 me-2">Year</label>
                  <select class="form-select form-select-sm me-2" style="width: 100px;" name="select_year" id="select_year">
                    <?php foreach ($report_years as $key => $value): ?>
                      <option value="<?php echo $value ?>" <?php if($value == $selected_year) { echo "selected"; } ?>><?php echo $value; ?></option>
                    <?php endforeach ?>
                  </select>
                  <button type="submit" class="btn btn-soft-info btn-sm">View</button>
                </div>
              </form>
            </div>
          </div>

          <div class="card-body">
            <div id="reportsChart" class="apex-charts" dir="ltr"></div>
          </div>
        </div>

        <div class="card mt-4">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Total Paid Orders - Report Data</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover text-nowrap mb-0">
                <thead class="bg-dark-subtle">
                  <tr>
                    <th>Month - Year</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($results as $k => $v): ?>
                    <tr>
                      <td><?php echo $k; ?></td>
                      <td><?php echo $company_currency . ' ' . number_format($v, 2); ?></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
                <tfoot class="bg-dark-subtle">
                  <tr>
                    <th>Total Amount</th>
                    <th><?php echo $company_currency . ' ' . number_format(array_sum($results), 2); ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function() {
  // Initialize auto-dismiss alerts
  function initializeAutoDismissAlerts() {
    $(".auto-dismiss").each(function() {
      const $alert = $(this);
      const timer = setTimeout(function() {
        $alert.fadeOut(500, function() {
          $(this).remove();
        });
      }, 5000);

      // Clear timeout if manually closed
      $alert.find('.btn-close').on('click', function() {
        clearTimeout(timer);
      });
    });
  }

  initializeAutoDismissAlerts();

  // Chart data
  var monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var reportData = <?php echo '[' . implode(',', $results) . ']'; ?>;

  // ApexCharts options
  var options = {
    series: [{
      name: 'Monthly Revenue',
      data: reportData
    }],
    chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        borderRadius: 4
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
    xaxis: {
      categories: monthLabels,
    },
    yaxis: {
      title: {
        text: 'Revenue'
      }
    },
    fill: {
      opacity: 1,
      colors: ['#0ea5e9']
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "<?php echo $company_currency; ?>" + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        }
      }
    },
    grid: {
      borderColor: '#f1f1f1'
    }
  };

  // Initialize ApexCharts
  var chart = new ApexCharts(document.querySelector("#reportsChart"), options);
  chart.render();
});
</script>
