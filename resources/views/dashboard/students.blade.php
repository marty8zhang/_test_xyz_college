@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Students <span class="pull-right">Showing {{ $students->perPage() * ($students->currentPage() - 1) + 1 }}-{{ $students->perPage() * $students->currentPage() }} of {{ $students->total() }}</span></div>

        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
              <th>Id</th>
              <th>Student Id</th>
              <th>Full Name</th>
              <th>Birthday</th>
              <th>Home Address</th>
              <th>Contact Number</th>
              <th>Student Email Address</th>
              <th>Personal Email Address</th>
              <th>Status</th>
              </thead>
              <tbody>
                @foreach ($students as $student)
                <tr>
                  <td>{{ $student->id }}</td>
                  <td><a href="{{ route('dashboard.students.show', ['student' => $student->studentId]) }}">{{ $student->studentId }}</a></td>
                  <td>{{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</td>
                  <td>{{ $student->birthday }}</td>
                  <td>{{ $student->homeAddress }}</td>
                  <td>{{ $student->contactNumber }}</td>
                  <td>{{ $student->studentEmailAddress }}</td>
                  <td>{{ $student->personalEmailAddress }}</td>
                  <td>{{ $student->status }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{ $students->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
