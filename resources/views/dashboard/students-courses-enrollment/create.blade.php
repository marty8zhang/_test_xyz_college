@php
/**
* The VIEW for displaying the create form of a student & course enrollment entry in the Dashboard.
* @author Marty Zhang
* @createdAt 12:34 PM AEST, 8 May 2018
* @version 0.9.201805092305
*/
@endphp
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Create a new Student & Course Enrollment Entry</div>
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
          <form action="{{ route('dashboard.students-courses-enrollment.store') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="student-id" class="col-sm-2 control-label">Student</label>
              <div class="col-sm-10">
                <select class="form-control" name="sId" id="student-id">
                  <option value="">- Please Select a Student -</option>
                  @foreach ($students as $s)
                  <option value="{{ $s->id }}"{{ old('sId') == $s->id || old('sId') === NULL && $student && $s->id == $student->id ? ' selected' : '' }}>{{ $s->lastName . ', ' . $s->middleName . ' ' . $s->firstName . ' - ' . $s->studentId }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="course-id" class="col-sm-2 control-label">Course</label>
              <div class="col-sm-10">
                <select class="form-control" name="cId" id="course-id">
                  <option value="">- Please Select a Course -</option>
                  @foreach ($courses as $c)
                  <option value="{{ $c->id }}"{{ old('cId') == $c->id || old('cId') === NULL && $course && $c->id == $course->id ? ' selected' : '' }}>{{ $c->courseCode . ' - ' . $c->courseName }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="enrollment-year" class="col-sm-2 col-xs-6 control-label">Year</label>
              <div class="col-sm-4 col-xs-6">
                <select class="form-control" name="enrollmentYear" id="enrollment-year">
                  <option value="">- Please Select -</option>
                  @for ($i = 0; $i < 5; $i++)
                  <option value="{{ date('Y') + $i }}"{{ old('enrollmentYear') == date('Y') + $i ? ' selected' : '' }}>{{ date('Y') + $i }}</option>
                  @endfor
                </select>
              </div>
              <label for="enrollment-semester" class="col-sm-2 col-xs-6 control-label">Semester</label>
              <div class="col-sm-4 col-xs-6">
                <select class="form-control" name="enrollmentSemester" id="enrollment-semester">
                  <option value="">- Please Select -</option>
                  <option value="1"{{ old('enrollmentSemester') == 1 ? ' selected' : '' }}>Semester 1</option>
                  <option value="2"{{ old('enrollmentSemester') == 2 ? ' selected' : '' }}>Summer School</option>
                  <option value="3"{{ old('enrollmentSemester') == 3 ? ' selected' : '' }}>Semester 2</option>
                  <option value="4"{{ old('enrollmentSemester') == 4 ? ' selected' : '' }}>Winter School</option>
                </select>
              </div>
            </div>
            <div class="form-group buttons">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Save</button>
                @php
                /*
                <a href="{{ route('dashboard.students-courses-enrollment.edit', ['enrollmentEntry' => $enrollmentEntry]) }}" class="btn btn-default">Reset</a>
                */
                @endphp
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