@php
/**
* The VIEW for displaying the student list in the Dashboard.
* @author Marty Zhang
* @version 0.9.201806091101
*/
@endphp
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
          <div class="text-center pagination-wrapper">
            {{ $students->links() }}
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Student Id</th>
                  <th>Full Name</th>
                  <th>Birthday</th>
                  <th>Home Address</th>
                  <th>Contact Number</th>
                  <th>Student Email Address</th>
                  <th>Personal Email Address</th>
                  <th>Status</th>
                </tr>
              </thead>
              @if (count($students) >= 20)
              <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Student Id</th>
                  <th>Full Name</th>
                  <th>Birthday</th>
                  <th>Home Address</th>
                  <th>Contact Number</th>
                  <th>Student Email Address</th>
                  <th>Personal Email Address</th>
                  <th>Status</th>
                </tr>
              </tfoot>
              @endif
              <tbody>
                @if (count($students))
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
                  <td>{{ $student->getStatusText() }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="9" class="text-center text-warning"><em>No students have been found.</em></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="text-center pagination-wrapper">
            {{ $students->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
