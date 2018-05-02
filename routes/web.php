<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::resource('/dashboard/students', 'Dashboard\StudentController')->names('dashboard.students');
Route::resource('/dashboard/courses', 'Dashboard\CourseController')->names('dashboard.courses');
Route::get('/dashboard/courses/{course}/deletion-confirmation', 'Dashboard\CourseController@confirmDestroying')->name('dashboard.courses.deletion-confirmation'); // Test-drives providing parameters to a specific controller action.
Route::get('/dashboard/assign-courses', 'Dashboard\AssignCoursesController@index')->name('dashboard.assign-courses');
