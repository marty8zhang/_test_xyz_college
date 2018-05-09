@php
/**
* The VIEW for displaying a student's details in the Dashboard.
* @author Marty Zhang
* @createdAt 2:57 PM AEST, 3 May 2018
* @version 0.9.201805081133
*/
@endphp
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Student Details</div>
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
                  <td>{{ $student->id }}</td>
                </tr>
                <tr>
                  <th>Student Id</th>
                  <td>{{ $student->studentId }}</td>
                </tr>
                <tr>
                  <th>Full Name</th>
                  <td>{{ $student->lastName }}, {{ $student->firstName }} {{ $student->middleName }}</td>
                </tr>
                <tr>
                  <th>Birthday</th>
                  <td>{{ $student->birthday }}</td>
                </tr>
                <tr>
                  <th>Home Address</th>
                  <td>{{ $student->homeAddress }}</td>
                </tr>
                <tr>
                  <th>Contact Number</th>
                  <td>{{ $student->contactNumber }}</td>
                </tr>
                <tr>
                  <th>Student Email Address</th>
                  <td>{{ $student->studentEmailAddress }}</td>
                </tr>
                <tr>
                  <th>Personal Email Address</th>
                  <td>{{ $student->personalEmailAddress }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>{{ $student->getStatusText() }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="text-center buttons">
            <a href="{{ route('dashboard.students-courses-enrollment.index', ['si' => $student->studentId]) }}" class="btn btn-default">Courses Enrollment List</a>
            <a href="{{ route('dashboard.students.index') }}" class="btn btn-default">Students List</a>
            <a href="javascript: window.history.back();" class="btn btn-primary">Go Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
