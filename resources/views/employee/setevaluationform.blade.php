@extends('layouts.masteremployee')
@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Evaluation Form</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="card" >
        <div class="card-header">
            <h3 class="card-title">Add Objectives</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ '/addEvaluationForm' }}">
                @csrf
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                    @endif
                @endforeach
                <p><b>Select an Employee</b></p>
                <div class="form-group">
                    <select class="form-control" id="employee" name="employee" required>
                        @if(count($employees))
                        <option value="" selected disabled hidden>Select an Employee</option>
                            @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->fname}}&nbsp&nbsp{{$employee->lname}}</option>
                            @endforeach
                        @else
                            <option value="">No Employees Registered</option> 
                        @endif
                    </select>
                </div>

                    
                </div>
                <hr>
                <br>
                <p><b>Select Objectives to assess</b></p>
                    <div class="row">
                    @if(count($objectives)> 0)
                        @foreach ($objectives as $objective)
                        <div class="col-md-3">
                            <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{$objective->description}}</h3>

                                <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                </div>
                                <!-- /.card-tools -->
                        </div>
                            <!-- /.card-header -->
                        <div class="card-body" style="display: block;">
                                {{$objective->target}}    
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="objectiveCheck" name="objectiveCheck[]" value="{{$objective->id}}">
                                <b><label class="form-check-label" for="objectiveCheck">Add Objective</label></b>             
                            </div>
                        </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    
                        @endforeach
                    @else
                        <div class="card-body" style="display: block;">
                            No Objectives Present 
                        </div>
                    @endif    
                    </div>
                    
                <br>
                <hr>
                <div class="form-group">
                    <label for="start_period">Choose Start Period</label>
                    <input id="start_period" name= "start_period" type="date" class="form-control @error('start_period') is-invalid @enderror" value="{{ old('start_period') }}" required min="<?php echo date('Y-m-d'); ?>">
                    @error('start_period')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_period">Choose End Period</label>
                    <input id="end_period" name= "end_period" type="date" class="form-control @error('end_period') is-invalid @enderror" value="{{ old('end_period') }}" required min="<?php echo Date('Y-m-d', strtotime('+21 days')); ?>">
                    @error('end_period')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group row mb-0" >
                          <div class="col-md-6 offset-md-4" >
                            <button type="submit" class="btn btn-primary" >
                                Create Form
                            </button>
                          </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection



