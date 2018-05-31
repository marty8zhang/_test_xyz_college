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

Route::prefix('dashboard') // Prepends a URI to the URIs inside the group.
        ->namespace('Dashboard') // Prepends a namespace to the controllers inside the group.
        ->name('dashboard.') // Prepends a route name to the route names inside the group.
        ->group(function () {
          Route::resource('students', 'StudentController', [
              'only' => [
                  'index',
                  'show',
              ],
          ])->names('students');

          Route::resource('courses', 'CourseController')->names('courses');
          Route::get('courses/{course}/deletion-confirmation', 'CourseController@confirmDestroying')->name('courses.deletion-confirmation'); // Test-drives providing parameters to a specific controller action.

          Route::resource('students-courses-enrollment', 'StudentsCoursesController', [
              'names' => 'students-courses-enrollment',
              'except' => [
                  'show',
                  'destroy',
              ],
              'parameters' => [
                  'students-courses-enrollment' => 'enrollment-entry', // 'resource-name' => 'controller-parameter-name', this affects the parameter name for implicit Route Model binding.
              ],
          ]);
          /* Development Note: The above is the same as the below. */
//          Route::resource('students-courses-enrollment', 'StudentsCoursesController')
//          ->names('students-courses-enrollment')
//          ->except([
//              'show',
//              'destroy',
//          ])
//          ->parameters([
//              'students-courses-enrollment' => 'enrollment-entry', // 'resource-name' => 'controller-parameter-name', this affects the parameter name for implicit Route Model binding.
//          ]);
        });
