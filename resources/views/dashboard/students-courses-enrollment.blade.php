@php
/**
* The VIEW for displaying the students and courses enrollment list in the Dashboard.
* @author Marty Zhang
* @createdAt 3 May 2018, 2:57 PM AEST
* @version 0.9.201806091101
*/
@endphp
@php
use App\Student;
use App\Course;
use App\StudentsCourses;

$isStudentActive = NULL;
if (app('request')->input('si')) {
$student = Student::find(app('request')->input('si'));
$isStudentActive = $student && $student->status === Student::STATUS_ENROLLED ? TRUE : FALSE;
}
$isCourseActive = NULL;
if (app('request')->input('cc')) {
$course = Course::find(app('request')->input('cc'));
$isCourseActive = $course && $course->status === Course::STATUS_ACTIVE ? TRUE : FALSE;
}

$paginationInformation = 'Showing ';
if ($enrollmentEntries->total() === 0 || $enrollmentEntries->total() === 1) {
$paginationInformation .= $enrollmentEntries->total();
} else {
$paginationInformation .= $enrollmentEntries->perPage() * ($enrollmentEntries->currentPage() - 1) + 1 . '-';
$paginationInformation .= ($enrollmentEntries->hasMorePages() ? $enrollmentEntries->perPage() * $enrollmentEntries->currentPage() : $enrollmentEntries->total());
}
$paginationInformation .= ' of ' . $enrollmentEntries->total();

$enrollmentEntries->render();
@endphp
@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Students and Courses Enrollment Entries <span class="pull-right">{{ $paginationInformation }}</span></div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-sm-7 text-center-xs mb24">
              @if (app('request')->input('si'))
              <button data-href="{{ route('dashboard.students-courses-enrollment.create', ['si' => app('request')->input('si')]) }}" class="btn btn-default btn-redirect"{{ $isStudentActive ? '' : ' disabled' }}{!! $isStudentActive ? '' : ' data-toggle="tooltip" data-placement="bottom" title="This student isn\'t currently enrolled to the college yet."' !!}>Enroll in a Course</button>
              @elseif (app('request')->input('cc'))
              <button data-href="{{ route('dashboard.students-courses-enrollment.create', ['cc' => app('request')->input('cc')]) }}" class="btn btn-default btn-redirect"{{ $isCourseActive ? '' : ' disabled' }}{!! $isCourseActive ? '' : ' data-toggle="tooltip" data-placement="bottom" title="This course isn\'t currently active yet."' !!}>Enroll a Student</button>
              @else
              <a href="{{ route('dashboard.students-courses-enrollment.create') }}" class="btn btn-default">Enroll a Student in a Course</a>
              @endif
            </div>
            <div class="col-sm-5 text-right text-center-xs mb24">
              <!--<div class="col-sm-4 text-center mb24">-->
              <form action="" method="GET" class="form-inline form-inline-xs">
                @foreach ($queryConditions as $qck => $qcv)
                @continue($qck == 'page' || $qck == 'n' || empty($qcv))
                @if (is_array($qcv))
                @foreach ($qcv as $v)
                <input type="hidden" name="{{ $qck }}[]" value="{{ $v }}">
                @endforeach
                @else
                <input type="hidden" name="{{ $qck }}" value="{{ $qcv }}">
                @endif
                @endforeach
                <div class="form-group">
                  <select class="form-control" name="n" id="select-records-per-page">
                    <option value="100"{{ app('request')->input('n') == 100 ? ' selected' : '' }}>100</option>
                    <option value="200"{{ app('request')->input('n') == 200 || app('request')->input('n') === NULL ? ' selected' : '' }}>200</option>
                    <option value="500"{{ app('request')->input('n') == 500 ? ' selected' : '' }}>500</option>
                    <option value="1000"{{ app('request')->input('n') == 1000 ? ' selected' : '' }}>1000</option>
                    <option value="2000"{{ app('request')->input('n') == 2000 ? ' selected' : '' }}>2000</option>
                  </select>
                  <label for="select-records-per-page">Records Per Page</label>
                </div>
              </form>
            </div>
            @php
            /*
            * To-do.
            <div class="col-sm-4 text-right text-center-xs mb24">
              <button id="buttonSearchCourse" class="btn btn-default" title="Search" data-toggle="modal" data-target="#search-modal"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
              <button id="buttonSortCourses" class="btn btn-default" title="Sort" data-toggle="modal" data-target="#sorting-modal"><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></button>
            </div>
            */
            @endphp
          </div>
          <div class="text-center pagination-wrapper">
            {{ $enrollmentEntries->links() }}
          </div>
          @if (app('request')->input('si'))
          <p class="text-info">Showing the results for the student (Student Id: {{ app('request')->input('si') }}).</p>
          @elseif (app('request')->input('cc'))
          <p class="text-info">Showing the results for the course (Course Code: {{ app('request')->input('cc') }}).</p>
          @endif
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Course Status</th>
                  <th>Student Id</th>
                  <th>Student Name</th>
                  <th>Student Status</th>
                  <th>Enrolled Semester</th>
                  <th>Enrolled Status</th>
                </tr>
              </thead>
              @if (count($enrollmentEntries) >= 20)
              <tfoot>
                <tr>
                  <th>Course Status</th>
                  <th>Student Id</th>
                  <th>Student Name</th>
                  <th>Student Status</th>
                  <th>Enrolled Semester</th>
                  <th>Enrolled Status</th>
                </tr>
              </tfoot>
              @endif
              <tbody>
                @if (count($enrollmentEntries))
                @php
                $isFirstEntry = TRUE;
                $currentCourseCode = '';
                @endphp
                @foreach ($enrollmentEntries as $ee)
                @if ($isFirstEntry || $currentCourseCode != $ee['course']->courseCode)
                @php
                $isFirstEntry = FALSE;
                $currentCourseCode = $ee['course']->courseCode;
                @endphp
                <tr>
                  <th colspan="6" class="text-center"><a href="{{ route('dashboard.courses.show', ['course' => $ee['course']->courseCode]) }}" title="View Course Details">{{ $ee['course']->courseCode }} - {{ $ee['course']->courseName }}</a></th>
                </tr>
                @endif
                <tr>
                  <td>{{ $ee['course']->getStatusText() }}</td>
                  <td><a href="{{ route('dashboard.students.show', ['student' => $ee['student']->studentId]) }}" title="View Student Details">{{ $ee['student']->studentId }}</a></td>
                  <td>{{ $ee['student']->lastName }}, {{ $ee['student']->firstName }} {{ $ee['student']->middleName }}</td>
                  <td>{{ $ee['student']->getStatusText() }}</td>
                  <td>{{ StudentsCourses::getSemesterText($ee['student']->pivot->semester) }}</td>
                  <td><a href="{{ route('dashboard.students-courses-enrollment.edit', ['enrollmentEntry' => $ee['student']->pivot->id]) }}" title="Change the Status of the Student & Course Enrollment Entry">{{ StudentsCourses::getStatusText($ee['student']->pivot->status) }}</a></td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="6" class="text-center text-warning"><em>No enrollment information has been found.</em></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="text-center pagination-wrapper">
            {{ $enrollmentEntries->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@php
/*
* To-do.
{{-- Search Modal --}}
<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="search-modal-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="PUT" id="form-search-courses">
        @foreach ($queryConditions as $qck => $qcv)
        @continue($qck == 'page' || $qck == 'q' || empty($qcv))
        @if (is_array($qcv))
        @foreach ($qcv as $v)
        <input type="hidden" name="{{ $qck }}[]" value="{{ $v }}">
        @endforeach
        @else
        <input type="hidden" name="{{ $qck }}" value="{{ $qcv }}">
        @endif
        @endforeach
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="sorting-modal-title">Search Courses</h4>
        </div>
        <div class="modal-body">
          <input name="q" value="{{ app('request')->input('q') ?: '' }}" placeholder="Search keyword for course name or course code" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Search</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- Sorting Modal --}}
<div class="modal fade" id="sorting-modal" tabindex="-1" role="dialog" aria-labelledby="sorting-modal-title">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="PUT" id="form-sort-courses">
        @foreach ($queryConditions as $qck => $qcv)
        @continue($qck == 'page' || $qck == 's' || $qck == 'o' || empty($qcv))
        <input type="hidden" name="{{ $qck }}" value="{{ $qcv }}">
        @endforeach
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="sorting-modal-title">Sort Courses</h4>
        </div>
        <div class="modal-body">
          @for ($i = 0; $i < count($sortingFields); $i++)
          <div class="row sorting-condition">
            <div class="col-sm-5 mb8-xs">
              <select name="s[]" class="form-control">
                @foreach ($courseFields as $cf)
                <option value="{{ $cf }}"{{ $cf == $sortingFields[$i] ? ' selected' : '' }}>{{ ucwords(preg_replace(['/([A-Z])/', '/_([a-z])/'], [' $1', ' $1'], $cf)) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-4 col-xs-7">
              <select name="o[]" class="form-control">
                <option value="ASC"{{ $sortingOrders[$i] == 'ASC' ? ' selected' : '' }}>Ascending</option>
                <option value="DESC"{{ $sortingOrders[$i] == 'DESC' ? ' selected' : '' }}>Descending</option>
              </select>
            </div>
            <div class="col-sm-3 col-xs-5 text-right">
              <button type="button" title="Add" class="btn btn-info add-sorting-condition"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
              <button type="button" title="Remove" class="btn btn-danger remove-sorting-condition"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
            </div>
          </div>
          @endfor
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Sort</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
*/
@endphp
@endsection
@push('footer_scripts')
<script>
  jQuery(function ($) {
    // Actives Bootstrap 3 tooltips.
    $('[data-toggle="tooltip"]').tooltip();

    // Initialises the buttons acting as re-direct links.
    $('.btn-redirect').on('click', function (e) {
      e.preventDefault();

      if ($(this).attr('disabled') != false) {
        window.location.href = $(this).data('href');
      }
    });

    // Deals with the Records Per Page form.
    $('select#select-records-per-page').on('change', function () {
      $(this).parents('form').trigger('submit');
    });

    /*
     * To-do.
     // Deals with the Search form.
     $('#search-modal').on('shown.bs.modal', function () {
     $(this).find('input[name="q"]').select();
     })

     // Deals with the Sorting form.
     $('#sorting-modal').on('shown.bs.modal', function () {
     $(this).find('select[name="s[]"]:first').focus();
     })
     $('#sorting-modal .add-sorting-condition').on('click', function () {
     $(this).closest('.row.sorting-condition').after($(this).closest('.row').clone(true));
     });
     $('#sorting-modal .remove-sorting-condition').on('click', function () {
     $(this).closest('.row.sorting-condition').remove();
     });
     */
  });
</script>
@endpush