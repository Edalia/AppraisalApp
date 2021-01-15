@extends('layouts.masteremployee')
@section('content')

<section class="content" >
  <br>

  <div class="form-group column mb-0" align="center">
    <div class="card card-primary " style="width: 50rem;" >
              <div class="card-header" >
                <h3 class="card-title">Evaluation Form </h3>
              </div>
          @if(count($forms) != 0)
            @foreach ($forms as $form)
              @if(array_search($form->employee, array_column($employees, 'id')) !== false)
                @foreach($employees as $employee)
                  @if($form->employee == $employee->id)
                    
                  <div class="card-body" >
                    <p><b>Period : {{$form->start_period}} - {{$form->end_period}}</b></p>
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
                <form method="POST" action="{{ '/evaluate/'.$form->id }}">  
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
                            Rate {{$employee->fname}}'s performance on this objective:
                            
                            <br><br>
                            <!--Radio Button for ratings-->
                            
                            <fieldset id="{{$evaluatated_objective->objective}}">
                              <input type="radio" value="1" name="group{{$evaluatated_objective->objective}}[]" checked> 1
                              &nbsp&nbsp
                              <input type="radio" value="2" name="group{{$evaluatated_objective->objective}}[]" > 2
                              &nbsp&nbsp
                              <input type="radio" value="3" name="group{{$evaluatated_objective->objective}}[]" > 3
                              &nbsp&nbsp
                              <input type="radio" value="4" name="group{{$evaluatated_objective->objective}}[]" > 4
                              &nbsp&nbsp
                              <input type="radio" value="5" name="group{{$evaluatated_objective->objective}}[]" > 5
                            </fieldset>
                            
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
                            <textarea class="form-control @error('comment') is-invalid @enderror" name= "comment" rows="2" placeholder="Overall performance comment" ></textarea>
                              @error('comment')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                          <!-- /.card-body -->
                        </div>

                        
                          <button type="submit" class="btn btn-primary" onclick="return confirm('Once submitted the evaluation cannot be modified.Are you sure you want to submit the evaluation?');">
                            Confirm
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

  
</section>
@endsection



