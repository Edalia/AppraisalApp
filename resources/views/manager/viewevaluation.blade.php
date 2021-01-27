@extends('layouts.mastermanager')
@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Evaluation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Evaluation</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
</section>

    <section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">View Evaluation</h3>
        </div>
        <div class="card-body">

        <div class="form-group column mb-0" >
            <div class="card card-primary " style="width: 50rem;" >
                    <div class="card-header" >
                        <h3 class="card-title">Evaluation Form </h3>
                    </div>
                @if(count($formdetails) != 0)
                    @foreach ($formdetails as $formdetail)
                    @if(array_search($formdetail->employee, array_column($employees, 'id')) !== false)
                        @foreach($employees as $employee)
                        @if($formdetail->employee == $employee->id)
                            
                        <div class="card-body" >
                            <p><b>Period : {{date("d F Y",strtotime($formdetail->start_period))}} - {{date("d F Y",strtotime($formdetail->end_period))}}</b></p>
                            <br>
                            <p><b>Employee Name : {{$employee->fname}} &nbsp{{$employee->lname}}</b></p>
                            <p><b>Employee Email : {{$employee->email}} </b></p>
                            
                        @endif

                            @break
                                    
                        @endforeach
                    @endif

                    @if(array_search($employee->jobtitle, array_column($jobtitles, 'id')) !== false)
                        @foreach($jobtitles as $jobtitle)
                        @if($employee->jobtitle == $jobtitle->id)    
                        
                            <p><b>Employee Jobtitle : {{$jobtitle->titlename}} </b></p>
                        @endif
                        
                            @break                      
                        @endforeach
                    @endif
                    
                    @if(count($evaluatated_objectives) != 0)
                        <form method="POST" action="{{ '/archive/'.$formdetail->id }}">  
                                @csrf
                        @foreach ($evaluatated_objectives as $evaluatated_objective)
                        @if(array_search($evaluatated_objective->objective, array_column($objectives, 'id')) !== false)
                            @foreach($objectives as $objective)  
                            @if($evaluatated_objective->objective == $objective->id)  
                            
                                <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title"><b>Objective : {{$objective->description}}</b></h2>
                                </div>
                                <div class="card-body" align = "left">
                                    Rating: {{$evaluatated_objective->rating}} / 5
                                    
                                </div>
                                <!-- /.card-body -->
                                </div>
                            
                            @endif
                            @endforeach
                        @endif
                        @endforeach
                                <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Evaluator's Comment </h3>
                                </div>
                                <div class="card-body">
                                    <p>
                                        {{$formdetail->comment}}
                                    </p>
                                </div>
                                <!-- /.card-body -->
                                </div>

                                
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Once archived the evaluation cannot be modified.Are you sure you want to archive the evaluation?');">
                                    Archive Evaluation
                                </button>

                            </form>
                    @else
                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">N/A</h3>
                        </div>
                        <div class="card-body">
                        N/A
                        </div>
                        <!-- /.card-body -->
                    </div>
                    @endif
                        
                        </div>
                    @endforeach
                    
                @else
                    <div class="card-body" >
                        Form does not exist
                    </div>
                @endif
                <!-- /.card-body -->
            </div>
        </div>

        </div>
    </div>
    </section>

@endsection



