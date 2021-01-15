@extends('layouts.mastermanager')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Employees</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Job Titles</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
    <div class="row">
      <div class="col-md-6">
      <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add Employee</h3>
            </div>
            <div class="card-body">
                    <form method="POST" action="{{ '/registerEmployee' }}">
                        @csrf
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                          @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                          @endif
                        @endforeach
                        
                        <div class="form-group">
                        <label for="fname">Employee's First Name</label>
                          <input id="fname" name= "fname" type="text" class="form-control @error('fname') is-invalid @enderror" value="{{ old('fname') }}" placeholder="Employee First Name" required>
                          
                          @error('fname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                          @enderror
                            
                        </div>

                        <div class="form-group">
                        <label for="lname">Employee's Surname</label>
                          <input id="lname" name= "lname" type="text" class="form-control @error('lname') is-invalid @enderror" value="{{ old('lname') }}" placeholder="Employee Surname" required>
                          
                          @error('lname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                          @enderror
                        
                        </div>

                        <div class="form-group">
                          <label for="title">Employee's Job Title</label>
                        
                            <select class="form-control" id="title" name="title" required>
                              @if(count($titleoptions))
                                    <option value="" selected disabled hidden>Choose here</option>
                                  @foreach ($titleoptions as $titleoption)
                                    <option value="{{$titleoption->id}}">{{$titleoption->titlename}}</option>
                                  @endforeach

                              @else
                                <option value="">No Job Titles Available</option> 
                              @endif
                            </select>
                        </div>

                        
                        <div class="form-group">
                          <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="evaluatorCheck" name="evaluatorCheck">
                            <b><label class="form-check-label" for="evaluatorCheck">Register as an Evaluator</label></b>
                            <br>
                          </div>
                        </div>

                        <div class="form-group">
                        <label for="phone">Employee's Phone</label>
                          <input id="phone" name= "phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Employee Phone Number" required>
                          
                          @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                          @enderror
                            
                        </div>

                        <div class="form-group">
                        <label for="email">Employee's Email</label>
                          <input id="email" name= "email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Employee Email" required>
                          
                          @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                          @enderror
                          
                        </div>

                        <div class="form-group row mb-0">
                          <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Register Employee
                            </button>
                          </div>
                        </div>
                    </form>  

            </div>
        <!-- /.card-body -->
        <!-- /.card-footer-->
        </div>
      </div>

      
      <div class="col-md-6">
        <div class="card">
         
          <div class="card-header">
                  <h2 class="card-title">Employee Accounts List</h2>
          </div>
          
          <div class="card-body">

            <table id="employeeList" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Employee Name</th>
                  <th>Job Position</th>
                  <th>Phone Number</th>
                  <th>Registered Date</th>
                </tr>
              </thead>

              <tbody>
              @if($employees->isNotEmpty())
                @foreach ($employees as $employee)
      
                    <tr>
                      <td>{{$employee->fname}}&nbsp&nbsp{{$employee->lname}}</td>

                      <td>
                      @if(array_search($employee->jobtitle, array_column($titleoptions, 'id')) !== false)
                        
                        @foreach($titleoptions as $titleoption)
                          
                          @if($employee->jobtitle == $titleoption->id)
                            
                            {{$titleoption->titlename}}
                            
                            @break
                          @endif
                        
                        @endforeach
                      
                      @endif
                      

                      <td>{{$employee->phone}}</td>
                      <td>{{$employee->created_at}}</td>
                        
                          @if($employee->isSuspended ==0)
                          <td>
                            <form method="GET" action="{{'/manager/employees/deactivate/'.$employee->id }}">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to suspend this account?');">
                                    Suspend Account
                                </button>
                            </form>
                            
                          </td>
                         
                          @else
                          <td>
                            <form method="GET" action="{{'/manager/employees/activate/'.$employee->id }}">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to make the account active?');">
                                    Activate Account
                                </button>
                            </form>
                            
                          </td>
                          @endif
                                            
                    </tr>
                  
                @endforeach
              
              @else
              <td align="center" colspan="5">You have not registered any employee</td>
              @endif    
              </tbody>
            </table>

          </div>

          
        
        </div>
        <div class="card">
         
          <div class="card-header">
                  <h2 class="card-title">Evaluator List</h2>
          </div>
          
          <div class="card-body">

            <table id="employeeList" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Evaluator Name</th>
                  <th>Job Position</th>
                  <th>Phone Number</th>
                  <th>Registered Date</th>
                  
                </tr>
              </thead>

              <tbody>
              @if($evaluators->isNotEmpty())
                @foreach ($evaluators as $evaluator)
      
                    <tr>
                      <td>{{$evaluator->fname}}&nbsp&nbsp{{$evaluator->lname}}</td>

                      <td>
                      @if(array_search($evaluator->jobtitle, array_column($titleoptions, 'id')) !== false)
                        
                        @foreach($titleoptions as $titleoption)
                          
                          @if($employee->jobtitle == $titleoption->id)
                            
                            {{$titleoption->titlename}}
                            
                            @break
                          @endif
                        
                        @endforeach
                      
                      @endif
                      </td>

                      <td>{{$evaluator->phone}}</td>
                      <td>{{$evaluator->created_at}}</td>

                          @if($evaluator->isSuspended ==0)
                          <td>
                            <form method="GET" action="{{'/manager/employees/deactivate/'.$evaluator->id }}">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to suspend this account?');">
                                    Suspend Account
                                </button>
                            </form>
                            
                          </td>
                         
                          @else
                          <td>
                            <form method="GET" action="{{'/manager/employees/activate/'.$evaluator->id }}">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to make the account active?');">
                                    Activate Account
                                </button>
                            </form>
                            
                          </td>
                          @endif
     
                    </tr>
                  
                @endforeach
              
              @else
              <td align="center" colspan="5">You have not registered any evaluator</td>
              @endif    
              </tbody>
            </table>

          </div>
      
        </div>
      </div>

    </div>
    <!-- /.row -->
    
    <!-- /.card -->
    </section>
@endsection



