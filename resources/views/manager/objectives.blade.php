@extends('layouts.mastermanager')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Job Titles Objectives</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Job Title Objectives</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Objectives</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ '/addObjective' }}">
              @csrf
              @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                 @endif
              @endforeach
                    
              <div class="form-group">
                <label for="description">Objective Description</label>
                  <input id="description" name= "description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="Objective Description" required>
                   @error('description')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>

              <div class="form-group">
                <label for="title"> Job Title</label>
                        
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
                <label for="target">Objective Target</label>
                <textarea class="form-control @error('target') is-invalid @enderror" name= "target" rows="2" placeholder="Describe Target" required></textarea>
                  @error('target')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>

              <div class="form-group">
                <label for="skill"> Competency</label>
                        
                  <select class="form-control" id="skill" name="skill" required>
                      @if(count($skills))
                     <option value="" selected disabled hidden>Choose here</option>
                        @foreach ($skills as $skill)
                          <option value="{{$skill->id}}">{{$skill->skill_name}}</option>
                        @endforeach
                      @else
                        <option value="">No Skills Available</option> 
                       @endif
                  </select>
              </div>

              <div class="form-group">
                <label for="priority"> Objective Priority</label>
                        
                  <select class="form-control" id="priority" name="priority" required>
                      @if(count($objectiveprioritys))
                     <option value="" selected disabled hidden>Choose here</option>
                        @foreach ($objectiveprioritys as $objectivepriority)
                          <option value="{{$objectivepriority->id}}">{{$objectivepriority->priority_type}}</option>
                        @endforeach
                      @else
                        <option value="">No Priorities Available</option> 
                       @endif
                  </select>
              </div>

              <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      Confirm
                    </button>
                  </div>
              </div>
          </form>
          <hr>
          <div class="card-header">
            <h3 class="card-title">View Added Objectives</h3>
          </div>
          <table id="objectiveList" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Objective Description</th>
                  <th>Objective Target</th>
                  <th>Job Title Responsible</th>
                  <th>Priority</th>
                  <th>Competency Measured</th>
                  <th>Date Added</th>
                  
                </tr>
              </thead>

              <tbody>
              @if(count($objectives) != 0)
                @foreach ($objectives as $objective)
      
                    <tr>
                      <td>{{$objective->description}}</td>
                      <td>{{$objective->target}}</td>

                      <td>
                      @if(array_search($objective->jobtitle, array_column($titleoptions, 'id')) !== false)
                        
                        @foreach($titleoptions as $titleoption)
                          
                          @if($objective->jobtitle == $titleoption->id)
                            
                            {{$titleoption->titlename}}
                            
                            @break
                          @endif
                        
                        @endforeach
                      
                      @endif
                      </td>

                      <td>
                      @if(array_search($objective->objective_priority, array_column($objectiveprioritys, 'id')) !== false)
                        
                        @foreach($objectiveprioritys as $priority)
                          
                          @if($objective->objective_priority == $priority->id)
                            
                            {{$priority->priority_type}}
                          @endif  
  
                        @endforeach
                      
                      @endif
                      </td>
                      
                      <td>
                        @if(array_search($objective->skill, array_column($skills, 'id')) !== false)
                        
                        @foreach($skills as $skill)
                          
                          @if($objective->skill == $skill->id)
                            
                            {{$skill->skill_name}}
                            
                            @break
                          @endif
                        
                        @endforeach
                      
                      @endif</td>
                      
                      <td>{{$objective->created_at}}</td>
                      
                      <td>
                        <form method="GET" action="{{'/manager/objectives/update/'.$objective->id }}">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </form>
                        
                      </td>

                      <td>
                        @if($objective->isActive == 1)
                        
                        <form method="GET" action="{{'/manager/objectives/deactivate/'.$objective->id }}">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate the objective?');">
                                Deactivate
                            </button>
                        </form>
                         
                        @else
                          <form method="GET" action="{{'/manager/objectives/activate/'.$objective->id }}">
                              <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to activate the objective?');">
                                  Activate
                              </button>
                          </form>
                        @endif                    
                      </td>

                    </tr>
                  
                @endforeach
              
              @else
              <td align="center" colspan="6">You have not added any Objectives</td>
              @endif    
              </tbody>
          </table>
        </div>
    <!-- /.card-body -->
    <!-- /.card-footer-->
    </div>
    <!-- /.card -->

    </section>
@endsection



