@extends('layouts.mastermanager')
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Job Titles</h1>
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

    <!-- Default box -->
    <div class="card" style="width: 40rem;">
        <div class="card-header">
            <h3 class="card-title">Title</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ '/addJobTitle' }}">
                    @csrf
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                      @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                      @endif
                    @endforeach
                    <div class="input-group mb-3">
                      <input id="titlename" name= "titlename" type="text" class="form-control @error('titlename') is-invalid @enderror" value="{{ old('titlename') }}" placeholder="Job Title Name" required>
                      
                      @error('titlename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                      @enderror
                    
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
          <table id="titleList" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Job Title Name</th>
                  <th>Date Added</th>
                </tr>
              </thead>

              <tbody>
              @if($jobtitles->isNotEmpty())
                @foreach ($jobtitles as $jobtitle)
      
                    <tr>
                      <td>{{$jobtitle->titlename}}</td>

                      <td>{{$jobtitle->created_at}}</td>

                      <td>
                        <form method="GET" action="{{'/manager/jobtitles/update/'.$jobtitle->id }}">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </form>
                        
                      </td>
                      
                    </tr>
                  
                @endforeach
              
              @else
              <td align="center" colspan="4">You have not registered any job title</td>
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



