@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Course Details</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
              </thead>
              <tbody>
                <tr>
                  <th>Id</th>
                  <td>{{ $course->id }}</td>
                </tr>
                <tr>
                  <th>Course Code</th>
                  <td>{{ $course->courseCode }}</td>
                </tr>
                <tr>
                  <th>Course Name</th>
                  <td>{{ $course->courseName }}</td>
                </tr>
                <tr>
                  <th>Course Description</th>
                  <td>{!! $course->courseDescription !!}</td>
                </tr>
                <tr>
                  <th>Course Points</th>
                  <td>{{ $course->coursePoints }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>{{ $course->getStatusText() }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="text-center buttons">
            <a href="{{ route('dashboard.courses.edit', ['course' => $course->courseCode]) }}" class="btn btn-default">Edit</a>
            <a href="{{ route('dashboard.courses.deletion-confirmation', ['course' => $course->courseCode]) }}" class="btn btn-danger">Delete</a>
            <a href="{{ route('dashboard.courses.index') }}" class="btn btn-primary">Back to the List</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
