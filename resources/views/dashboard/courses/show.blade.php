@php
/**
* The VIEW for displaying a course's details in the Dashboard.
* @author Marty Zhang
* @version 0.9.201805081134
*/
@endphp
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
            <a href="{{ route('dashboard.students-courses-enrollment.index', ['cc' => $course->courseCode]) }}" class="btn btn-default">Students Enrollment List</a>
            <a href="{{ route('dashboard.courses.index') }}" class="btn btn-default">Courses List</a>
            <a href="javascript: window.history.back();" class="btn btn-primary">Go Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
