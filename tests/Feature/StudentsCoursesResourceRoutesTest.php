<?php

/**
 * The PHPUnit test class for testing the Dashboard Students & Courses resource routes which are handled by App\Http\Controllers\Dashboard\StudentsCoursesController.
 * @author Marty Zhang
 * @createdAt 8:51 PM AEST, 13 Jun 2018
 * @version 0.9.201806140614
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Student;
use App\Course;
use App\StudentsCourses;

class StudentsCoursesResourceRoutesTest extends TestCase {

  /**
   * Tests the Students & Courses Enrollment listing page.
   * @return void
   */
  public function testIndex() {
    $user = User::inRandomOrder()->first();

    // If not a logged-in user, when trying to access the listing page should be redirected to the login page.
    $response = $this->get(route('dashboard.students-courses-enrollment.index'));
    $response->assertRedirect('/login');
    // A logged-in user should be able to access the listing page.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.index'));
    $response->assertStatus(200);

    // Tests the pagination functionality.
    // The current page should be marked as 'active' in the pagenation.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.index', ['page' => 1]));
    $response->assertStatus(200)
            ->assertSee('<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>');
    // Non-existing page should diaplay a friendly notification.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.index', ['page' => 9999]));
    $response->assertStatus(200)
            ->assertSeeText('No enrollment information has been found.');

    // Tests the "x Records Per Page" functionality.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.index', ['n' => 500]));
    $response->assertStatus(200)
            ->assertSee('<option value="500" selected>500</option>');

    // Tests everything altogether.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.index', [
        'page' => 2,
        'n' => '100'
    ]));
    $response->assertStatus(200)
            ->assertSee('<li class="page-item active" aria-current="page"><span class="page-link">2</span></li>')
            ->assertSee('<option value="100" selected>100</option>')
            ->assertDontSeeText('No enrollment information has been found.');
  }

  /**
   * Tests the details page of a specific student & course enrollment entry.
   * @return void
   */
  public function testShow() {
    $user = User::inRandomOrder()->first();
    $enrollmentEntry = StudentsCourses::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->get("/dashboard/students-courses-enrollment/{$enrollmentEntry->id}");
    // This route isn't needed and should have been disabled at this stage.
    $response->assertStatus(405);
  }

  /**
   * Tests the functionality of creating a new student & course enrollment entry.
   * @return void
   */
  public function testCreate() {
    $user = User::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.create'));
    $response->assertStatus(200);
  }

  /**
   * Tests the functionality of storing a newly created student & course enrollment entry.
   * @return void
   */
  public function testStore() {
    $user = User::inRandomOrder()->first();
    $enrollmentEntry = StudentsCourses::inRandomOrder()->first();
    do { // Development Note: Makes sure the new student & course enrollment entry won't violate the unique key constraints of their relationship.
      $newEnrollmentEntry = factory(StudentsCourses::class)->make();
      $newEnrollmentEntry->semester = date('Y') . '01'; // Development Note: The StudentsCoursesFactory doesn't always generate a semester string that will pass the form validation.
    } while (StudentsCourses::where([
        ['sId', $newEnrollmentEntry->sId],
        ['cId', $newEnrollmentEntry->cId],
        ['semester', $newEnrollmentEntry->semester],
    ])->count());
    $student = Student::where('id', $newEnrollmentEntry->sId)->first();
    $course = Course::where('id', $newEnrollmentEntry->cId)->first();

    // Tests the validation rules.
    // With empty data.
    $response = $this->actingAs($user)
            ->post(route('dashboard.students-courses-enrollment.store'), []);
    $response->assertSessionHasErrors([
        'enrollmentYear' => 'The Year is required.',
        'enrollmentSemester' => 'The Semester is required.',
        'sId' => 'The Student Record Id is required.',
        'cId' => 'The Course Record Id is required.',
    ]);
    // With some invalid data.
    $response = $this->actingAs($user)
            ->post(route('dashboard.students-courses-enrollment.store'), [
        'enrollmentYear' => date('Y') - 1,
        'enrollmentSemester' => 5,
        'sId' => 99999,
        'cId' => 99999,
    ]);
    $response->assertSessionHasErrors([
        'enrollmentYear' => 'The Year must be between ' . date('Y') . ' and ' . (date('Y') + 1) . '.',
        'enrollmentSemester' => 'The Semester must be between 1 and 4.',
        'sId' => 'The selected Student Record Id is invalid.',
        'cId' => 'The selected Course Record Id is invalid.',
    ]);
    $response = $this->actingAs($user)
            ->post(route('dashboard.students-courses-enrollment.store'), [
        'enrollmentYear' => (int) substr($enrollmentEntry->semester, 0, strlen($enrollmentEntry->semester) - 2),
        'enrollmentSemester' => (int) substr($enrollmentEntry->semester, -2),
        'sId' => $enrollmentEntry->sId,
        'cId' => $enrollmentEntry->cId,
    ]);
    $response->assertSessionHasErrors([
        'cId' => 'There is already a record for this student related the same course in the same semester. If you\'d like to update the enrollment status of this record, please do it through the Students & Courses Enrollment list.',
    ]);

    // Successful submission.
    $response = $this->actingAs($user)
            ->post(route('dashboard.students-courses-enrollment.store'), [
        'enrollmentYear' => (int) substr($newEnrollmentEntry->semester, 0, strlen($newEnrollmentEntry->semester) - 2),
        'enrollmentSemester' => (int) substr($newEnrollmentEntry->semester, -2),
        'sId' => $newEnrollmentEntry->sId,
        'cId' => $newEnrollmentEntry->cId,
    ]);
    $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', "The student (Student Id: {$student->studentId}) has been enrolled in the \"{$course->courseCode} - {$course->courseName}\" course for " . StudentsCourses::getSemesterText($newEnrollmentEntry->semester) . ".");
  }

  /**
   * Tests the functionality of editing an existing student & course enrollment entry.
   * @return void
   */
  public function testEdit() {
    $user = User::inRandomOrder()->first();
    $enrollmentEntry = StudentsCourses::inRandomOrder()->first();
    $student = Student::where('id', $enrollmentEntry->sId)->first();
    $course = Course::where('id', $enrollmentEntry->cId)->first();

    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.edit', ['enrollmentEntry' => 'non-existing-course-code']));
    $response->assertStatus(404);
    $response = $this->actingAs($user)
            ->get(route('dashboard.students-courses-enrollment.edit', ['enrollmentEntry' => $enrollmentEntry->id]));
    $response->assertStatus(200)
            ->assertSee(htmlentities($student->studentId . ' - ' . $student->lastName . ', ' . $student->firstName . ' ' . $student->middleName, ENT_QUOTES))
            ->assertSee(htmlentities($course->courseCode . ' - ' . $course->courseName, ENT_QUOTES))
            ->assertSee(htmlentities(StudentsCourses::getSemesterText($enrollmentEntry->semester), ENT_QUOTES));
    if (strpos(StudentsCourses::getStatusText($enrollmentEntry->status), 'cannot be recognised') === FALSE) {
      $response->assertSee("<option value=\"{$enrollmentEntry->status}\" selected>");
    }
  }

  /**
   * Tests the functionality of updating the details of an existing student & course enrollment entry.
   * @return void
   */
  public function testUpdate() {
    $user = User::inRandomOrder()->first();
    $enrollmentEntry = StudentsCourses::inRandomOrder()->first();
    $student = Student::where('id', $enrollmentEntry->sId)->first();
    $course = Course::where('id', $enrollmentEntry->cId)->first();

    $response = $this->actingAs($user)
            ->patch(route('dashboard.students-courses-enrollment.update', ['enrollmentEntry' => 'non-existing-course-code']), ['status' => 0]);
    $response->assertStatus(404);

    // Tests the validation rules.
    // With empty data.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.students-courses-enrollment.update', ['enrollmentEntry' => $enrollmentEntry->id]), []);
    $response->assertSessionHasErrors([
        'status' => 'The Status is required.',
    ]);
    // With some invalid data.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.students-courses-enrollment.update', ['enrollmentEntry' => $enrollmentEntry->id]), ['status' => 99]);
    $response->assertSessionHasErrors([
        'status' => 'The Status must be between 0 and 2.',
    ]);

    // Successful submission.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.students-courses-enrollment.update', ['enrollmentEntry' => $enrollmentEntry->id]), ['status' => 0]);
    $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', "The student's (Student Id: {$student->studentId}) enrollment status of the \"{$course->courseCode} - {$course->courseName}\" course for " . StudentsCourses::getSemesterText($enrollmentEntry->semester) . " has been successfully updated.");
  }

  /**
   * Tests the functionality of deleting an existing student & course enrollment entry.
   * @return void
   */
  public function testDestroy() {
    $user = User::inRandomOrder()->first();
    $enrollmentEntry = StudentsCourses::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->delete("/dashboard/students-courses-enrollment/{$enrollmentEntry->id}");
    // Deleting an existing student & course enrollment entry shouldn't be allowed at this stage.
    $response->assertStatus(405); // "Method Not Allowed".
  }

}
