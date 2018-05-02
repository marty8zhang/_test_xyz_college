@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Course Deletion Confirmation</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <p>The below course will be marked as deleted from the system and cannot be seen from the course list anymore if you proceed. Only a system administrator can reverse this action.</p>
          <p><strong>Course Code:</strong> {{ $course->courseCode }}<br>
            <strong>Course Name:</strong> {{ $course->courseName }}</p>
          <p>Do you still want to continue?</p>
          <form action="{{ route('dashboard.courses.destroy', ['course' => $course->courseCode]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <div class="buttons">
              <input type="submit" name="submit" value="Yes" class="btn btn-danger">
              <a href="{{ route('dashboard.courses.show', ['course' => $course]) }}" class="btn btn-default">No</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
