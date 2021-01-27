@extends('layouts.masteremployee')
@section('content')
<section class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
</section>
<section class="content" style="width:100%">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(Session::has('alert-' . $msg))
  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
  @endif
  @endforeach
  <div class="row" >
    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Pending Evaluations</span>
                  <span class="info-box-number">{{count($pendingForms)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-success">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Submitted Evaluations</span>
                  <span class="info-box-number">{{count($submittedForms)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box bg-primary">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Evaluated Employees</span>
                  <span class="info-box-number">{{count($submittedForms)}}</span>


                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
    </div>
  </div>
  
  <div class="row" >
    <div class="col-md-6 col-sm-6 col-12" >
      <h3>Pending Evaluations</h3>
      <table id="objectiveList" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Employee Name (Select to Evaluate)</th>
                      <th>Start Period</th>
                      <th>End Period</th>
                    </tr>
                  </thead>

                  <tbody>
                  @if(count($pendingForms) != 0)
                    @foreach ($pendingForms as $pendingForm)
                      
                        <tr class="table-tr">
                        
                          <td>
                            
                              @if(array_search($pendingForm->employee, array_column($employees, 'id')) !== false)
                                
                                @foreach($employees as $employee)
                                  
                                  @if($pendingForm->employee == $employee->id)
                                  <a href="{{url('/evaluateemployee/'.$employee->id)}}">
                                    {{$employee->fname}} &nbsp {{$employee->lname}}
                                  </a>  
                                    @break
                                  @endif
                                
                                @endforeach
                              
                              @endif
                            
                          </td>

                          <td>        
                            {{date("d F Y",strtotime($pendingForm->start_period))}}
                          </td>
                          
                          <td>
                            {{date("d F Y",strtotime($pendingForm->end_period))}}
                          </td>

                        </tr>
                    
                    @endforeach
                  @else
                  <td align="center" colspan="4">No Pending Evaluations</td>
                  @endif    
                  </tbody>
      </table>
    </div>

    <div class="col-md-6 col-sm-6 col-12" style="width:50%">
    <h3>Submitted Evaluations</h3>
      <table id="objectiveList" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Employee Name</th>
                      <th>Date Evaluated</th>
                      <th>Average Rating (out of 5)</th>
                      
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
                            {{date("d F Y",strtotime($submittedForm->evaluation_date))}}
                          </td>
                          
                          <td>
                            {{$submittedForm->final_rate}}
                          </td>
                                
                        </tr>
                    
                    @endforeach
                  @else
                  <td align="center" colspan="4">No Submitted Evaluations</td>
                  @endif    
                  </tbody>
      </table>
    </div>
  </div>

</section>
@endsection



