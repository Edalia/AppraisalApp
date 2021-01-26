@extends('layouts.mastermanager')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manager Home Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Manager Home Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
          <div class="container">
          <div class="row">
              <div class="col-md-10 offset-md-1">
                  <div class="panel panel-default">
                      <div class="panel-heading">Dashboard</div>
                      <div class="panel-body">
                          <canvas id="canvas" height="280" width="600"></canvas>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
      <script>
          var year = <?php echo $evaluators; ?>;
          var user = <?php echo $evaluationForms; ?>;
          var barChartData = {
              labels: year,
              datasets: [{
                  label: 'User',
                  backgroundColor: "pink",
                  data: user
              }]
          };

          window.onload = function() {
              var ctx = document.getElementById("canvas").getContext("2d");
              window.myBar = new Chart(ctx, {
                  type: 'bar',
                  data: barChartData,
                  options: {
                      elements: {
                          rectangle: {
                              borderWidth: 2,
                              borderColor: '#c1c1c1',
                              borderSkipped: 'bottom'
                          }
                      },
                      responsive: true,
                      title: {
                          display: true,
                          text: 'Yearly User Joined'
                      }
                  }
              });
          };
      </script>

    </section>
@endsection



