@php
/**
* The VIEW for displaying the edit form of a course in the Dashboard.
* @author Marty Zhang
* @version 0.9.201806121627
*/
@endphp
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Edit the Course</div>
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
          <form action="{{ route('dashboard.courses.update', ['course' => $course]) }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
              <label for="courseCode" class="col-sm-2 control-label">Course Code *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="courseCode" id="courseCode" placeholder="Course Code *" value="{{ old('courseCode') === NULL ? $course->courseCode : old('courseCode') }}">
              </div>
            </div>
            <div class="form-group">
              <label for="courseName" class="col-sm-2 control-label">Course Name *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="courseName" id="courseName" placeholder="Course Name *" value="{{ old('courseName') === NULL ? $course->courseName : old('courseName') }}">
              </div>
            </div>
            <div class="form-group">
              <label for="courseDescription" class="col-sm-2 control-label">Course Description</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="courseDescription" id="courseDescription" placeholder="Course Description">{{ old('courseDescription') === NULL ? $course->courseDescription : old('courseDescription') }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="coursePoints" class="col-sm-2 control-label">Course Points *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="coursePoints" id="coursePoints" placeholder="Course Points *" value="{{ old('coursePoints') === NULL ? $course->coursePoints : old('coursePoints') }}">
              </div>
            </div>
            <div class="form-group">
              <label for="status" class="col-sm-2 control-label">Status</label>
              <div class="col-sm-10">
                <select class="form-control" name="status" id="status">
                  <option value="0"{{ old('status') !== NULL && old('status') == 0 || old('status') === NULL && $course->status == 0 ? ' selected' : '' }}>Inactive</option>
                  <option value="1"{{ old('status') == 1 || old('status') === NULL && $course->status == 1 ? ' selected' : '' }}>Active</option>
                  <option value="2"{{ old('status') == 2 || old('status') === NULL && $course->status == 2 ? ' selected' : '' }}>Discontinued</option>
                </select>
              </div>
            </div>
            <div class="form-group buttons">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('dashboard.courses.edit', ['course' => $course]) }}" class="btn btn-default">Reset</a>
                <a href="{{ route('dashboard.courses.show', ['course' => $course]) }}" class="btn btn-default">Cancel</a>
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