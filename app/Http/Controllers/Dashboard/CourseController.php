<?php

namespace App\Http\Controllers\Dashboard;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $courses = Course::orderBy('status')
            ->orderBy('courseCode')
            ->paginate(20);

    return view('dashboard.courses', ['courses' => $courses]);
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

    return redirect()->route('dashboard.courses.index')->with('status', "The \"{$course->courseName}\" course has been successfully added.");
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
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Course $course) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Course  $course
   * @return \Illuminate\Http\Response
   */
  public function destroy(Course $course) {
    //
  }

}
