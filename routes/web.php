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

Route::resource('/dashboard/students', 'Dashboard\StudentController', [
    'only' => [
        'index',
        'show',
    ],
])->names('dashboard.students');

Route::resource('/dashboard/courses', 'Dashboard\CourseController')->names('dashboard.courses');
Route::get('/dashboard/courses/{course}/deletion-confirmation', 'Dashboard\CourseController@confirmDestroying')->name('dashboard.courses.deletion-confirmation'); // Test-drives providing parameters to a specific controller action.

Route::resource('/dashboard/students-courses-enrollment', 'Dashboard\StudentsCoursesController', [
            'except' => [
                'show',
                'destroy',
            ],
        ])
        ->names('dashboard.students-courses-enrollment')
        ->parameters([
            'students-courses-enrollment' => 'enrollment-entry', // 'resource-name' => 'controller-parameter-name', this affects the parameter name for implicit Route Model binding.
        ]);

Route::resource('/dashboard/tests', 'Dashboard\TestController')->names('dashboard.tests');
