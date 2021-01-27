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
</div>
<div class="col-md-12">
    <div class="col-md-8 col-md-offset-2">
    	<div class="col-xl-6">
    		<div class="card">
    			<div class="card-body">
    				<div class="chart-container">
    					<canvas class="chart has-fixed-height" id="bars_basic"></canvas>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>	
</div>

<div class="row" >
    <script type="text/javascript">
    var bars_basic_element = document.getElementById('bars_basic');
    if (bars_basic_element) {
        var bars_basic = echarts.init(bars_basic_element);
        bars_basic.setOption({
            color: ['#3398DB'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {            
                    type: 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: ['Fruit', 'Vegitable','Grains'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: 'Total Products',
                    type: 'bar',
                    barWidth: '20%',
                    data: [
                        {{count($submittedForms)}},
                        {{count($submittedForms)}}, 
                        {{count($submittedForms)}}
                    ]
                }
            ]
        });
    }
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
                            <th>Average Rating</th>
                            
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
                            <th>Evaluation Rating</th>
                            
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



