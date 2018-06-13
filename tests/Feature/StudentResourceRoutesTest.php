<?php

/**
 * The PHPUnit Test class for testing the Dashboard Student resource routes which are handled by App\Http\Controllers\Dashboard\StudentController.
 * @author Marty Zhang
 * @createdAt 11:46 PM AEST, 9 Jun 2018
 * @version 0.9.201806132307
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Student;

class StudentResourceRoutesTest extends TestCase {

  /**
   * Tests the Students listing page.
   * @return void
   */
  public function testIndex() {
    $user = User::inRandomOrder()->first();

    // If not a logged-in user, when trying to access the listing page should be redirected to the login page.
    $response = $this->get(route('dashboard.students.index'));
    $response->assertRedirect('/login');
    // A logged-in user should be able to access the listing page.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students.index'));
    $response->assertStatus(200);

    // Tests the pagination functionality.
    // The current page should be marked as 'active' in the pagenation.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students.index', ['page' => 1]));
    $response->assertStatus(200)
            ->assertSee('<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>');
    // Non-existing page should diaplay a friendly notification.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students.index', ['page' => 9999]));
    $response->assertStatus(200)
            ->assertSeeText('No students have been found.');
  }

  /**
   * Tests the details page of a specific student.
   * @return void
   */
  public function testShow() {
    $user = User::inRandomOrder()->first();
    $student = Student::inRandomOrder()->first();

    // Tries to show the details of a non-existing student.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students.show', ['student' => 'non-existing-student-id']));
    $response->assertStatus(404);

    // Tries to show the details of an existing student.
    $response = $this->actingAs($user)
            ->get(route('dashboard.students.show', ['student' => $student->studentId]));
    $response->assertStatus(200)
            ->assertSeeText($student->id)
            ->assertSeeText($student->studentId)
            ->assertSeeText("{$student->lastName}, {$student->firstName} {$student->middleName}")
            ->assertSeeText($student->birthday)
            ->assertSeeText($student->homeAddress)
            ->assertSeeText($student->contactNumber)
            ->assertSeeText($student->studentEmailAddress)
            ->assertSeeText($student->personalEmailAddress)
            ->assertSeeText(htmlentities($student->getStatusText(), ENT_QUOTES));
  }

  /**
   * Tests the functionality of creating a new student.
   * @return void
   */
  public function testCreate() {
    $user = User::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->get('/dashboard/students/create');
    // Creating a new student shouldn't be allowed at this stage.
    $response->assertStatus(404);
  }

  /**
   * Tests the functionality of storing a newly created student.
   * @return void
   */
  public function testStore() {
    $user = User::inRandomOrder()->first();
    $student = factory(Student::class)->make();

    $response = $this->actingAs($user)
            ->post('/dashboard/students', [
        'studentId' => $student->studentId,
        'firstName' => $student->firstName,
        'lastName' => $student->lastName,
        'middleName' => $student->middleName,
        'birthday' => $student->birthday,
        'homeAddress' => $student->homeAddress,
        'contactNumber' => $student->contactNumber,
        'studentEmailAddress' => $student->studentEmailAddress,
        'personalEmailAddress' => $student->personalEmailAddress,
        'status' => $student->status,
    ]);
    // Creating a new student shouldn't be allowed at this stage.
    $response->assertStatus(405); // "Method Not Allowed".
  }

  /**
   * Tests the functionality of editing an existing student.
   * @return void
   */
  public function testEdit() {
    $user = User::inRandomOrder()->first();
    $student = Student::inRandomOrder()->first();

    // Editing the details of an existing student shouldn't be allowed at this stage.
    $response = $this->actingAs($user)
            ->get("/dashboard/students/{$student->studentId}/edit");
    $response->assertStatus(404);
  }

  /**
   * Tests the functionality of updating the details of an existing student.
   * @return void
   */
  public function testUpdate() {
    $user = User::inRandomOrder()->first();
    $student = Student::inRandomOrder()->first();
    $newStudentDetails = factory(Student::class)->make();

    $response = $this->actingAs($user)
            ->patch("/dashboard/students/{$student->studentId}", [
        'firstName' => $newStudentDetails->firstName,
        'lastName' => $newStudentDetails->lastName,
        'middleName' => $newStudentDetails->middleName,
        'birthday' => $newStudentDetails->birthday,
        'homeAddress' => $newStudentDetails->homeAddress,
        'contactNumber' => $newStudentDetails->contactNumber,
        'studentEmailAddress' => $newStudentDetails->studentEmailAddress,
        'personalEmailAddress' => $newStudentDetails->personalEmailAddress,
        'status' => $newStudentDetails->status,
    ]);
    // Editing the details of an existing student shouldn't be allowed at this stage.
    $response->assertStatus(405); // "Method Not Allowed".
  }

  /**
   * Tests the functionality of deleting an existing student.
   * @return void
   */
  public function testDestroy() {
    $user = User::inRandomOrder()->first();
    $student = Student::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->delete("/dashboard/students/{$student->studentId}");
    // Deleting an existing student shouldn't be allowed at this stage.
    $response->assertStatus(405); // "Method Not Allowed".
  }

}
