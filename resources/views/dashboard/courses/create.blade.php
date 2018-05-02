@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Add a New Course</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <form action="{{ route('dashboard.courses.store') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="courseCode" class="col-sm-2 control-label">Course Code *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="courseCode" id="courseCode" placeholder="Course Code *" value="{{ old('courseCode') }}">
              </div>
            </div>
            <div class="form-group">
              <label for="courseName" class="col-sm-2 control-label">Course Name *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="courseName" id="courseName" placeholder="Course Name *" value="{{ old('courseName') }}">
              </div>
            </div>
            <div class="form-group">
              <label for="courseDescription" class="col-sm-2 control-label">Course Description *</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="courseDescription" id="courseDescription" placeholder="Course Description *">{{ old('courseDescription') }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="coursePoints" class="col-sm-2 control-label">Course Points *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="coursePoints" id="coursePoints" placeholder="Course Points *" value="{{ old('coursePoints') }}">
              </div>
            </div>
            <div class="form-group buttons">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Add</button>
                <a href="{{ route('dashboard.courses.index') }}" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('footer_scripts')
<script src="//cdn.ckeditor.com/4.9.2/full-all/ckeditor.js"></script>
<script>
  CKEDITOR.replace('courseDescription');
</script>
@endpush