@php
/**
* The VIEW for displaying the course list in the Dashboard.
* @author Marty Zhang
* @version 0.9.201805021211
*/
@endphp
@extends('layouts.app')
@section('content')
@php
$paginationInformation = 'Showing ';
if ($courses->total() === 0 || $courses->total() === 1) {
$paginationInformation .= $courses->total();
} else {
$paginationInformation .= $courses->perPage() * ($courses->currentPage() - 1) + 1 . '-';
$paginationInformation .= ($courses->hasMorePages() ? $courses->perPage() * $courses->currentPage() : $courses->total());
}
$paginationInformation .= ' of ' . $courses->total();
@endphp
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Courses <span class="pull-right">{{ $paginationInformation }}</span></div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-sm-4 text-center-xs mb24">
              <a class="btn btn-default" href="{{ route('dashboard.courses.create') }}">Add a New Course</a>
            </div>
            <div class="col-sm-4 text-center mb24">
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
                    <option value="10"{{ app('request')->input('n') == 10 ? ' selected' : '' }}>10</option>
                    <option value="20"{{ app('request')->input('n') == 20 || app('request')->input('n') === NULL ? ' selected' : '' }}>20</option>
                    <option value="50"{{ app('request')->input('n') == 50 ? ' selected' : '' }}>50</option>
                    <option value="100"{{ app('request')->input('n') == 100 ? ' selected' : '' }}>100</option>
                    <option value="500"{{ app('request')->input('n') == 500 ? ' selected' : '' }}>500</option>
                    <option value="1000"{{ app('request')->input('n') == 1000 ? ' selected' : '' }}>1000</option>
                  </select>
                  <label for="select-records-per-page">Records Per Page</label>
                </div>
              </form>
            </div>
            <div class="col-sm-4 text-right text-center-xs mb24">
              <button id="buttonSearchCourse" class="btn btn-default" title="Search" data-toggle="modal" data-target="#search-modal"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
              <button id="buttonSortCourses" class="btn btn-default" title="Sort" data-toggle="modal" data-target="#sorting-modal"><span class="glyphicon glyphicon-sort" aria-hidden="true"></span></button>
            </div>
          </div>
          <div class="text-center pagination-wrapper">
            {{ $courses->links() }}
          </div>
          @if (app('request')->input('q'))
          <p class="text-info">Showing the results for the search term "{{ app('request')->input('q') }}".</p>
          @endif
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Course Code</th>
                  <th>Course Name</th>
                  <th>Points</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @if (count($courses))
                @foreach ($courses as $course)
                <tr>
                  <td>{{ $course->id }}</td>
                  <td><a href="{{ route('dashboard.courses.show', ['course' => $course->courseCode]) }}">{{ $course->courseCode }}</a></td>
                  <td>{{ $course->courseName }}</td>
                  <td>{{ $course->coursePoints }}</td>
                  <td>{{ $course->getStatusText() }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="5" class="text-center text-warning"><em>No courses have been found.</em></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="text-center pagination-wrapper">
            {{ $courses->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
@endsection
@push('footer_scripts')
<script>
  jQuery(function ($) {
    // Deals with the Records Per Page form.
    $('select#select-records-per-page').on('change', function () {
      $(this).parents('form').trigger('submit');
    });

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
  });
</script>
@endpush