<?php

/**
 * The CONTROLLER for the Course model in the Dashboard.
 * @author Marty Zhang
 * @version 0.9.201805021209
 */

namespace App\Http\Controllers\Dashboard;

use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class CourseController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource with the pagination, sorting, and searching features.
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {

    $courseFields = Schema::getColumnListing((new Course)->getTable()); // All field names (in an array) of the database table.
    $courses = NULL; // Development Note: When use Course::all() here instead, it will be hard to implement the pagination feature since $courses will become Collections rather than a query builder.
    // Determines the number of records per page. The default value is 20.
    $recordsPerPage = $request->get('n') && $request->get('n') == (int) $request->get('n') ? $request->get('n') : 20;

    // Determines the searching keyword.
    $keyword = $request->get('q') ?: '';

    // Prepares to determine the sorting criteria.
    $sortingFields = ['status', 'courseCode']; // The default sorting fields.
    $defaultNumberOfSortingFields = count($sortingFields);
    $sortingOrders = [];
    $tempSortingOrders = empty($request->get('o')) || !is_array($request->get('o')) ? [] : $request->get('o');
    // Determines the sorting fields.
    if (!empty($request->get('s')) || !is_array($request->get('s'))) {
      $tempSortingFields = $request->get('s');

      for ($i = 0; $i < min(count($tempSortingFields), count($courseFields)); $i++) { // Also determins the maximum number of times for orderBy() here.
        if (in_array($tempSortingFields[$i], $courseFields)) {
          $sortingFields[$i] = $tempSortingFields[$i];
          if (count($sortingFields) > count(array_unique($sortingFields))) { // It's a duplicate value (field name).
            unset($tempSortingOrders[$i]); // All duplicate values will be removed after this FOR loop has ended, but first we'll need to remove the corresponding value from the sorting orders array.
          }
        } else if ($i >= $defaultNumberOfSortingFields) {
          break; // Invalid field names, which are out of the default sorting field range, are not allowed and the rest of the given sorting fields will be ignored.
        }
      }

      $sortingFields = array_unique($sortingFields); // Removes all duplicate values.
    }
    // Determines the sorting orders.
    for ($i = 0; $i < count($sortingFields); $i++) { // Makes sure the sizes of sorting fields and orders are the same by using the size of $sortingFields instead of $tempSortingOrders here.
      if (empty($tempSortingOrders[$i]) || !in_array(strtoupper($tempSortingOrders[$i]), ['ASC', 'DESC'])) {
        $sortingOrders[$i] = 'ASC'; // The default sorting order.
      } else {
        $sortingOrders[$i] = strtoupper($tempSortingOrders[$i]); // Development Note: Use strtoupper() for easy comparison to determine sortBy() or sortByDesc() below.
      }
    }

    // Queries all courses based on the given query conditions.
    if ($keyword) {
      if ($courses === NULL) { // Development Note: This IF check is not necessary at this stage, but it's kept here to ease possible future updates in relation to adding more query conditions before this $keyword check.
        $courses = Course::where('courseCode', 'LIKE', "%$keyword%")
                ->orWhere('courseName', 'LIKE', "%$keyword%");
      } else {
        $courses->where('courseCode', 'LIKE', "%$keyword%")
                ->orWhere('courseName', 'LIKE', "%$keyword%");
      }
    }
    for ($i = 0; $i < count($sortingFields); $i++) {
      if ($courses === NULL && $i == 0) {
        $courses = Course::orderBy($sortingFields[$i], $sortingOrders[$i]);
      } else {
        $courses->orderBy($sortingFields[$i], $sortingOrders[$i]);
      }
    }
    // Development Note: Not like other query building methods, which update the original object directly, paginate() does its work by returning a new updated object. Hence, the re-assignment below is necessary.
    $courses = $courses->paginate($recordsPerPage);

    // Removes all invalid sorting criteria (if any) from the query string.
    $request->merge([
        's' => $sortingFields,
        'o' => $sortingOrders,
    ]);
    // Collects the query conditions for the further uses in the Blade template.
    $queryConditions = $request->all([
        'n', // The number of records per page.
        'q', // The search string.
        's', // The sorting field(s).
        'o', // The sorting order(s).
    ]);

    return view('dashboard.courses', [
        'courseFields' => $courseFields,
        'queryConditions' => $queryConditions,
        'sortingFields' => $sortingFields,
        'sortingOrders' => $sortingOrders,
        'courses' => $courses->appends($queryConditions), // appends() other query conditions (if among the given list and provided) to the pagination links.
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('dashboard.courses.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $this->validate($request, [
        'courseCode' => 'bail|required|min:6|max:20|regex:/[a-z]{2,6}[0-9]{4,18}/i|unique:courses,courseCode',
        'courseName' => 'bail|required|min:2|max:191',
        'courseDescription' => 'max:65535',
        'coursePoints' => 'bail|required|integer|in:5,10,15',
    ]);

    $course = new Course;
    $course->courseCode = strtoupper($request->courseCode);
    $course->courseName = $request->courseName;
    $course->courseDescription = $request->courseDescription ?: '';
    $course->coursePoints = $request->coursePoints;
    $course->save();

    return redirect()->route('dashboard.courses.index')->with('status', "The \"{$course->courseCode} - {$course->courseName}\" course has been successfully added.");
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function show(Course $course) {
    return view('dashboard.courses.show', ['course' => $course]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function edit(Course $course) {
    return view('dashboard.courses.edit', ['course' => $course]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Course $course) {
    $this->validate($request, [
        'courseCode' => [
            'bail',
            'required',
            'min:6',
            'max:20',
            'regex:/[a-z]{2,6}[0-9]{4,18}/i',
            Rule::unique('courses')->ignore($course->courseCode, 'courseCode'),
        ],
        'courseName' => 'bail|required|min:2|max:191',
        'courseDescription' => 'max:65535',
        'coursePoints' => 'bail|required|integer|in:5,10,15',
        'status' => 'bail|required|integer|between:0,2',
    ]);

    $course->courseCode = strtoupper($request->courseCode);
    $course->courseName = $request->courseName;
    $course->courseDescription = $request->courseDescription ?: '';
    $course->coursePoints = $request->coursePoints;
    $course->status = $request->status;
    $course->save();

    return redirect()->route('dashboard.courses.index')->with('status', "The \"{$course->courseCode} - {$course->courseName}\" course has been successfully updated.");
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function destroy(Course $course) {
    $course->delete();
    return redirect()->route('dashboard.courses.index')->with('status', "The \"{$course->courseCode} - {$course->courseName}\" course has been marked as deleted.");
  }

  /**
   * Test-drives a non-JavaScript solution for deletion confirmation.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function confirmDestroying(Course $course) {
    return view('dashboard.courses.deletion-confirmation', ['course' => $course]);
  }

}
