@extends('layouts.mastermanager')
@section('content')
<section class="content">
<div class="card-body">

<div class="row" >
    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">Job Titles Added</span>
                  <span class="info-box-number">{{count($titleoptions)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-success">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Objectives Added</span>
                  <span class="info-box-number">{{count($objectives)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-primary">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Employees</span>
                  <span class="info-box-number">{{count($totalemployees)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Evaluations Archived</span>
                  <span class="info-box-number">{{count($archivedForms)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

</div>
<div class="col-md-12">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <canvas id="canvas" height="280" width="600"></canvas>
                </div>
        </div>
    </div>	
</div>

<div class="row" >
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var evaluators = "";
    var evaluations = "" ;
    var barChartData = {
        labels: evaluators,
        datasets: [{
            label: 'Evaluations',
            backgroundColor: "lightblue",
            data: evaluations
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
                    text: 'Submissions made'
                }
            }
        });
    };
</script>

    @csrf   
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
        @endif
    @endforeach
    <div class="row" >
            
        <h3>Submitted Evaluations</h3>
        <table id="objectiveList" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                            <th>Employee Name</th>
                            <th>Evaluator Name</th>
                            <th>Date Submitted</th>
                            <th>Average Rating (Out of 5)</th>
                            
                            </tr>
                        </thead>

                        <tbody>
                        @if(count($submittedForms) != 0)
                            @foreach ($submittedForms as $submittedForm)
                            
                                <tr class="table-tr">
                                
                                <td>
                                    
                                    @if(array_search($submittedForm->employee, array_column($employees, 'id')) !== false)
                                        
                                        @foreach($employees as $employee)
                                        
                                        @if($submittedForm->employee == $employee->id)
                                        
                                            {{$employee->fname}} &nbsp {{$employee->lname}}
                                        
                                            @break
                                        @endif
                                        
                                        @endforeach
                                    
                                    @endif
                                    
                                </td>

                                <td>
                                    @if(!empty($evaluators))
                                        @if(array_search($submittedForm->evaluator, array_column($evaluators, 'id')) !== false)
                                            
                                            @foreach($evaluators as $evaluator)
                                            
                                            @if($submittedForm->evaluator == $evaluator->id)
                                            
                                                {{$evaluator->fname}} &nbsp {{$evaluator->lname}}
                                            
                                                @break
                                            @endif
                                            
                                            @endforeach
                                        
                                        @endif
                                    @else
                                    N/Aa
                                    @endif
                                </td>

                                <td>        
                                    {{date("d F Y",strtotime($submittedForm->evaluation_date))}}
                                </td>
                                
                                <td>
                                    {{$submittedForm->final_rate}}
                                </td>
        
                                <td>
                                    @if($submittedForm->isArchived == 0)
                                    
                                    <form method="GET" action="{{'/manager/viewevaluation/'.$submittedForm->id }}">
                                        <button type="submit" class="btn btn-primary" >
                                            View Evaluation
                                        </button>
                                    </form>

                                    @endif                    
                                </td>
                                
                                </tr>
                            
                            @endforeach
                        @else
                        <td align="center" colspan="4">No Submitted Evaluations</td>
                        @endif    
                        </tbody>
        </table>

        <h3>Archived Evaluations</h3>
        <table id="objectiveList" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                            <th>Employee Name</th>
                            <th>Evaluator Name</th>
                            <th>Date Archived</th>
                            <th>Evaluation Rating (Out of 5)</th>
                            
                            </tr>
                        </thead>

                        <tbody>
                        @if(count($archivedForms) != 0)
                            @foreach ($archivedForms as $archivedForm)
                            
                                <tr class="table-tr">
                                
                                <td>
                                    
                                    @if(array_search($archivedForm->employee, array_column($employees, 'id')) !== false)
                                        
                                        @foreach($employees as $employee)
                                        
                                        @if($archivedForm->employee == $employee->id)
                                        
                                            {{$employee->fname}} &nbsp {{$employee->lname}}
                                        
                                            @break
                                        @endif
                                        
                                        @endforeach
                                    
                                    @endif
                                    
                                </td>

                                <td>
                                    
                                    @if(array_search($archivedForm->evaluator, array_column($evaluators, 'id')) !== false)
                                        
                                        @foreach($evaluators as $evaluator)
                                        
                                        @if($archivedForm->evaluator == $evaluator->id)
                                        
                                            {{$evaluator->fname}} &nbsp {{$evaluator->lname}}
                                        
                                            @break
                                        @endif
                                        
                                        @endforeach
                                    
                                    @endif
                                    
                                </td>

                                <td>        
                                    {{date("d F Y",strtotime($archivedForm->archived_date))}}
                                </td>
                                
                                <td>
                                    {{$archivedForm->final_rate}}
                                </td>
        
                                
                                </tr>
                            
                            @endforeach
                        @else
                        <td align="center" colspan="4">No Archived Evaluations</td>
                        @endif    
                        </tbody>
        </table>

    </div>
</div>
</section>
@endsection



