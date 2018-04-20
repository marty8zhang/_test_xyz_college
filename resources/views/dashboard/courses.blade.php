@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Courses <span class="pull-right">Showing {{ $courses->perPage() * ($courses->currentPage() - 1) + 1 }}-{{ $courses->perPage() * $courses->currentPage() }} of {{ $courses->total() }}</span></div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <div class="row mb24">
            <div class="col-sm-12 text-center">
              <a class="btn btn-default" href="{{ route('dashboard.courses.create') }}">Add a New Course</a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
              <th>Id</th>
              <th>Course Code</th>
              <th>Course Name</th>
              <th>Points</th>
              <th>Status</th>
              </thead>
              <tbody>
                @foreach ($courses as $course)
                <tr>
                  <td>{{ $course->id }}</td>
                  <td><a href="{{ route('dashboard.courses.show', ['course' => $course->courseCode]) }}">{{ $course->courseCode }}</a></td>
                  <td>{{ $course->courseName }}</td>
                  <td>{{ $course->coursePoints }}</td>
                  <td>{{ $course->status }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-sm-12 text-center">
              <a class="btn btn-default" href="{{ route('dashboard.courses.create') }}">Add a New Course</a>
            </div>
          </div>
          {{ $courses->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
