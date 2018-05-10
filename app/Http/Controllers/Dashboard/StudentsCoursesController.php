<?php

/**
 * The CONTROLLER for the students and courses enrollment MODEL in the Dashboard.
 * @author Marty Zhang
 * @createdAt 3 May 2018, 2:28 PM AEST
 * @version 0.9.201805101140
 */

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use App\Course;
use App\Student;
use App\StudentsCourses;

class StudentsCoursesController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   * It can also be used to get a list of enrollment entries against a given student id or course code.
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    // Determines the pagination-related parameters.
    $currentPage = $request->get('page') && $request->get('page') == (int) $request->get('page') ? $request->get('page') : 1;
    $recordsPerPage = $request->get('n') && $request->get('n') == (int) $request->get('n') ? $request->get('n') : 200;

    // Gets the required data from the database.
    $studentId = $request->get('si') && $request->get('si') == (int) $request->get('si') ? $request->get('si') : NULL;
    $courseCode = $request->get('cc') && $request->get('cc') == (int) $request->get('cc') ? $request->get('cc') : NULL;
    // Development Note: The situation when a valid student id and a valid course code are provided in the same request will only trigger the 'if ($studentId)' segment of code. In other words, that situation can be deemed as not allowed.
    if ($studentId) {
      $courses = Course::orderBy('status')
              ->orderBy('courseCode')
              ->with([
                  'students' => function ($query) use ($studentId) {
                    // Development Note: $query here is actually the query builder that's joining the students table with the relationship table.
                    // E.g., SELECT students.*, students_courses.semester AS pivot_semester, ... FROM students INNER JOIN students_courses ON...
                    $query->where('studentId', $studentId)
                    ->orderBy('students_courses.status')
                    ->orderBy('status') // Development Note: Without nominating the table name, it's by default the same as 'students.status'.
                    ->orderByRaw('CAST(studentId AS UNSIGNED INTEGER)'); // Development Note: The studentId field in the database table is in the string format to provide a better compatibility by design. However, to better test-drive this index page, orderByRaw() in conjunction with SQL CAST() are used here.
                  },
              ])
              ->get();
    } else if ($courseCode) {
      $courses = Course::where('courseCode', $courseCode)
              ->orderBy('status')
              ->orderBy('courseCode')
              ->with([
                  'students' => function ($query) {
                    // Development Note: $query here is actually the query builder that's joining the students table with the relationship table.
                    // E.g., SELECT students.*, students_courses.semester AS pivot_semester, ... FROM students INNER JOIN students_courses ON...
                    $query->orderBy('students_courses.status')
                    ->orderBy('status') // Development Note: Without nominating the table name, it's by default the same as 'students.status'.
                    ->orderByRaw('CAST(studentId AS UNSIGNED INTEGER)'); // Development Note: The studentId field in the database table is in the string format to provide a better compatibility by design. However, to better test-drive this index page, orderByRaw() in conjunction with SQL CAST() are used here.
                  },
              ])
              ->get();
    } else {
      $courses = Course::orderBy('status')
              ->orderBy('courseCode')
              ->with([
                  'students' => function ($query) {
                    // Development Note: $query here is actually the query builder that's joining the students table with the relationship table.
                    // E.g., SELECT students.*, students_courses.semester AS pivot_semester, ... FROM students INNER JOIN students_courses ON...
                    $query->orderBy('students_courses.status')
                    ->orderBy('status') // Development Note: Without nominating the table name, it's by default the same as 'students.status'.
                    ->orderByRaw('CAST(studentId AS UNSIGNED INTEGER)'); // Development Note: The studentId field in the database table is in the string format to provide a better compatibility by design. However, to better test-drive this index page, orderByRaw() in conjunction with SQL CAST() are used here.
                  },
              ])
              ->get();
    }

    // Formats the data and generates the pagination.
    $enrollmentEntries = [];
    foreach ($courses as $course) {
      foreach ($course->students as $student) {
        $enrollmentEntries[] = ['course' => $course, 'student' => $student];
      }
    }
    $entriesSlice = array_slice($enrollmentEntries, ($currentPage - 1) * $recordsPerPage, $recordsPerPage);
    // Development Note: What the constructor of LengthAwarePaginator returned can be used for both traversing the data as well as providing pagination related features.
    $entriesSlice = new LengthAwarePaginator($entriesSlice, count($enrollmentEntries), $recordsPerPage, null, ['path' => route('dashboard.students-courses-enrollment.index')]);

    // Collects the query conditions for the further uses in the Blade template.
    $acceptableParameters = [
        'n', // The number of records per page. To-do.
        's', // The sorting field(s). To-do.
        'o', // The sorting order(s). To-do.
    ];
    if ($studentId) {
      $acceptableParameters[] = 'si'; // The student id.
    } else if ($courseCode) {
      $acceptableParameters[] = 'cc'; // The course code.
    }
    $queryConditions = $request->all($acceptableParameters);

    return view('dashboard.students-courses-enrollment', [
        'queryConditions' => $queryConditions,
        'enrollmentEntries' => $entriesSlice->appends($queryConditions), // appends() other query conditions (if among the given list and provided) to the pagination links.
    ]);
  }

  /**
   * Show the form for creating a new resource.
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request) {
    $student = $request->get('si') && $request->get('si') == (int) $request->get('si') ? Student::find($request->get('si')) : NULL;
    $course = $request->get('cc') && $request->get('cc') == (int) $request->get('cc') ? Course::find($request->get('cc')) : NULL;

    $students = Student::where('status', Student::STATUS_ENROLLED)
            ->orderBy('lastName')
            ->orderBy('middleName')
            ->orderBy('firstName')
            ->orderByRaw('CAST(studentId AS UNSIGNED INTEGER)')
            ->get();
    $courses = Course::where('status', Course::STATUS_ACTIVE)
            ->orderBy('courseCode')
            ->get();

    return view('dashboard.students-courses-enrollment.create', [
        'student' => $student,
        'course' => $course,
        'students' => $students,
        'courses' => $courses,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $this->validate($request, [
        'enrollmentYear' => 'bail|required|integer|between:' . date('Y') . ',' . (date('Y') + 1),
        'enrollmentSemester' => 'bail|required|integer|between:1,4',
        'sId' => 'bail|required|integer|exists:students,id',
        'cId' => 'bail|required|integer|exists:courses,id|unique_multiple_fields:students_courses,cId,sId,' . $request->sId . ',' . 'semester,' . ($semester = $request->enrollmentYear . str_pad($request->enrollmentSemester, 2, '0', STR_PAD_LEFT)),
    ]);

    $enrollmentEntry = new StudentsCourses([
        'sId' => $request->sId,
        'cId' => $request->cId,
        'semester' => $semester,
    ]);
    $enrollmentEntry->save();

    $student = Student::where('id', $request->sId)->first();
    $course = Course::where('id', $request->cId)->first();
    return redirect()->route('dashboard.students-courses-enrollment.index')->with('status', "The student (Student Id: {$student->studentId}) has been enrolled in the \"{$course->courseCode} - {$course->courseName}\" course for " . StudentsCourses::getSemesterText($semester) . ".");
  }

  /**
   * Display the specified resource.
   * Development Note: Not in used.
   * @param  \App\StudentsCourses  $enrollmentEntry An object represents one entry of the many-to-many relationship of the students and courses enrollment. Development Note: The non-standard parameter name - $enrollmentEntry - has to be explicitly nominated by parameters() of the resource route registration in /routes/web.php first.
   * @return \Illuminate\Http\Response
   */
  public function show(StudentsCourses $enrollmentEntry) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   * @param  \App\StudentsCourses $enrollmentEntry An object represents one entry of the many-to-many relationship of the students and courses enrollment. Development Note: The non-standard parameter name - $enrollmentEntry - has to be explicitly nominated by parameters() of the resource route registration in /routes/web.php first.
   * @return \Illuminate\Http\Response
   */
  public function edit(StudentsCourses $enrollmentEntry) {
    $student = Student::where('id', $enrollmentEntry->sId)->firstOrFail();
    $course = Course::where('id', $enrollmentEntry->cId)->firstOrFail();
    return view('dashboard.students-courses-enrollment.edit', [
        'enrollmentEntry' => $enrollmentEntry,
        'student' => $student,
        'course' => $course,
    ]);
  }

  /**
   * Update the specified resource in storage.
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\StudentsCourses  $enrollmentEntry
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, StudentsCourses $enrollmentEntry) {
    $this->validate($request, [
        'status' => 'bail|required|integer|between:0,2',
    ]);

    $student = Student::where('id', $enrollmentEntry->sId)->firstOrFail();
    $course = Course::where('id', $enrollmentEntry->cId)->firstOrFail();

//    $student->courses()->updateExistingPivot($course->id, [
//        'status' => $request->status,
//    ]);
    // Development Note: The above way to update a record of this particular relationship is wrong, since it might update more than one record in the pivot table instead of the specific one that's already given via $enrollmentEntry.
    $enrollmentEntry->status = $request->status;
    $enrollmentEntry->save(); // Development Note: This won't work unless the problematic inherited methods concerning $this->pivotParent have been overridden.

    return redirect()->route('dashboard.students-courses-enrollment.index')->with('status', "The student's (Student Id: {$student->studentId}) enrollment status of the \"{$course->courseCode} - {$course->courseName}\" course for " . StudentsCourses::getSemesterText($enrollmentEntry->semester) . " has been successfully updated.");
  }

  /**
   * Remove the specified resource from storage.
   * Development Note: Not in used.
   * @param  \App\StudentsCourses  $enrollmentEntry An object represents one entry of the many-to-many relationship of the students and courses enrollment. Development Note: The non-standard parameter name - $enrollmentEntry - has to be explicitly nominated by parameters() of the resource route registration in /routes/web.php first.
   * @return \Illuminate\Http\Response
   */
  public function destroy(StudentsCourses $enrollmentEntry) {
    //
  }

}
