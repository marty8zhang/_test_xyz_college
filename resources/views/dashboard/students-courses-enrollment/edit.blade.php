@php
/**
* The VIEW for displaying the edit form of a student & course enrollment entry in the Dashboard.
* @author Marty Zhang
* @createdAt 11:07 AM AEST, 7 May 2018
* @version 0.9.201805081519
*/
@endphp
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Edit the Student & Course Enrollment Entry</div>
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
          <form action="{{ route('dashboard.students-courses-enrollment.update', ['enrollmentEntry' => $enrollmentEntry]) }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="form-group">
              <label class="col-sm-2 control-label">Student</label>
              <div class="col-sm-10">
                <input type='text' value="{{ $student->studentId . ' - ' . $student->lastName . ', ' . $student->firstName . ' ' . $student->middleName }}" class="form-control" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Course</label>
              <div class="col-sm-10">
                <input type='text' value="{{ $course->courseCode . ' - ' . $course->courseName }}" class="form-control" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Semester</label>
              <div class="col-sm-10">
                <input type='text' value="{{ $enrollmentEntry->semester }}" class="form-control" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="status" class="col-sm-2 control-label">Status</label>
              <div class="col-sm-10">
                <select class="form-control" name="status" id="status">
                  <option value="0"{{ old('status') == 0 ? ' selected' : '' }}>Cancelled</option>
                  <option value="1"{{ old('status') == 1 || old('status') === NULL && $enrollmentEntry->status == 1 ? ' selected' : '' }}>Enrolled</option>
                  <option value="2"{{ old('status') == 2 || old('status') === NULL && $enrollmentEntry->status == 2 ? ' selected' : '' }}>Dropped Out</option>
                </select>
              </div>
            </div>
            <div class="form-group buttons">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('dashboard.students-courses-enrollment.edit', ['enrollmentEntry' => $enrollmentEntry]) }}" class="btn btn-default">Reset</a>
                <a href="{{ route('dashboard.students-courses-enrollment.index') }}" class="btn btn-default">Students & Courses Enrollment List</a>
                <a href="javascript: window.history.back();" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection