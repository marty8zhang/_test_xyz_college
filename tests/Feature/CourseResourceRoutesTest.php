<?php

/**
 * The PHPUnit Test class for testing the Dashboard Course resource routes which are handled by App\Http\Controllers\Dashboard\CourseController.
 * @author Marty Zhang
 * @createdAt 11:46 PM AEST, 9 Jun 2018
 * @version 0.9.201806140616
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Course;

class CourseResourceRoutesTest extends TestCase {

  /**
   * Tests the Courses listing page.
   * @return void
   */
  public function testIndex() {
    $user = User::inRandomOrder()->first();

    // If not a logged-in user, when trying to access the listing page should be redirected to the login page.
    $response = $this->get(route('dashboard.courses.index'));
    $response->assertRedirect('/login');
    // A logged-in user should be able to access the listing page.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index'));
    $response->assertStatus(200);

    // Tests the pagination functionality.
    // The current page should be marked as 'active' in the pagenation.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', ['page' => 1]));
    $response->assertStatus(200)
            ->assertSee('<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>');
    // Non-existing page should diaplay a friendly notification.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', ['page' => 9999]));
    $response->assertStatus(200)
            ->assertSeeText('No courses have been found.');

    // Tests the "x Records Per Page" functionality.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', ['n' => 500]));
    $response->assertStatus(200)
            ->assertSee('<option value="500" selected>500</option>');

    // Tests the sorting functionality.
    // Default sorting criteria.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index'));
    $response->assertStatus(200)
            ->assertViewHasAll([
                'sortingFields' => ['status', 'courseCode'],
                'sortingOrders' => ['ASC', 'ASC']
    ]);
    // One sorting field.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', ['s' => 'courseCode']));
    $response->assertStatus(200)
            ->assertViewHasAll([
                'sortingFields' => ['courseCode'],
                'sortingOrders' => ['ASC']
    ]);
    // Multiple sorting fields with invalid and duplicate field names.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', [
        's' => ['test', 'status', 'coursePoints', 'test2', 'id', 'status'],
        'o' => ['ASC', 'DESC', 'DESC', 'DESC', 'asc', 'DESC'],
    ]));
    $response->assertStatus(200)
            ->assertViewHasAll([
                'sortingFields' => ['status', 'coursePoints', 'id'],
                'sortingOrders' => ['DESC', 'DESC', 'ASC']
    ]);

    // Tests the search functionality.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', ['q' => 'test']));
    $response->assertStatus(200)
            ->assertSee('name="q" value="test"')
            ->assertSeeText('Showing the results for the search term "test".');

    // Tests everything altogether.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.index', [
        'page' => 999,
        'n' => 2,
        's' => ['status', 'bad-field-name', 'courseCode'],
        'o' => ['DESC', 'DESC', 'ASC'],
        'q' => 'bad-search-term'
    ]));
    $response->assertStatus(200)
            ->assertSeeInOrder([
                'name="s[]" value="status"',
                'name="s[]" value="courseCode"',
                'name="o[]" value="DESC"',
                'name="o[]" value="ASC"',
            ])
            ->assertSeeText('No courses have been found.')
            ->assertSee('name="q" value="bad-search-term"');
  }

  /**
   * Tests the details page of a specific course.
   * @return void
   */
  public function testShow() {
    $user = User::inRandomOrder()->first();
    $course = Course::inRandomOrder()->first();

    // Tries to show the details of a non-existing course.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.show', ['course' => 'non-existing-course-code']));
    $response->assertStatus(404);

    // Tries to show the details of an existing course.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.show', ['course' => $course->courseCode]));
    $response->assertStatus(200)
            ->assertSeeText($course->id)
            ->assertSeeText($course->courseCode)
            ->assertSeeText($course->courseName)
            ->assertSee($course->courseDescription)
            ->assertSeeText($course->coursePoints)
            ->assertSeeText(htmlentities($course->getStatusText(), ENT_QUOTES));
  }

  /**
   * Tests the functionality of creating a new course.
   * @return void
   */
  public function testCreate() {
    $user = User::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.create'));
    $response->assertStatus(200);
  }

  /**
   * Tests the functionality of storing a newly created course.
   * @return void
   */
  public function testStore() {
    $user = User::inRandomOrder()->first();
    $course = Course::inRandomOrder()->first();
    $newCourse = factory(Course::class)->make();

    // Tests the validation rules.
    // With empty data.
    $response = $this->actingAs($user)
            ->post(route('dashboard.courses.store'), []);
    $response->assertSessionHasErrors([
        'courseCode' => 'The Course Code is required.',
        'courseName' => 'The Course Name is required.',
        'coursePoints' => 'The Course Points is required.',
    ]);
    // With some invalid data.
    $response = $this->actingAs($user)
            ->post(route('dashboard.courses.store'), [
        'courseCode' => $course->courseCode,
        'courseName' => $newCourse->courseName,
        'coursePoints' => 9999,
    ]);
    $response->assertSessionHasErrors([
        'courseCode' => 'The Course Code has already been taken.',
        'coursePoints' => 'The Course Points can only be a value among 5, 10, or 15.',
    ]);

    // Successful submission.
    $response = $this->actingAs($user)
            ->post(route('dashboard.courses.store'), [
        'courseCode' => $newCourse->courseCode,
        'courseName' => $newCourse->courseName,
        'courseDescription' => $newCourse->courseDescription,
        'coursePoints' => $newCourse->coursePoints,
    ]);
    $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', "The \"{$newCourse->courseCode} - {$newCourse->courseName}\" course has been successfully added.");
  }

  /**
   * Tests the functionality of editing an existing course.
   * @return void
   */
  public function testEdit() {
    $user = User::inRandomOrder()->first();
    $course = Course::inRandomOrder()->first();

    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.edit', ['course' => 'non-existing-course-code']));
    $response->assertStatus(404);
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.edit', ['course' => $course->courseCode]));
    $response->assertStatus(200)
            ->assertSee($course->courseCode)
            ->assertSee($course->courseName)
            ->assertSee(htmlentities($course->courseDescription, ENT_QUOTES))
            ->assertSee($course->coursePoints);
  }

  /**
   * Tests the functionality of updating the details of an existing course.
   * @return void
   */
  public function testUpdate() {
    $user = User::inRandomOrder()->first();
    $course = Course::inRandomOrder()->first();
    $anotherCourse = Course::where('courseCode', '<>', $course->courseCode)->inRandomOrder()->first();
    $newCourse = factory(Course::class)->make();

    $response = $this->actingAs($user)
            ->patch(route('dashboard.courses.update', ['course' => 'non-existing-course-code']), [
        'courseCode' => $newCourse->courseCode,
        'courseName' => $newCourse->courseName,
        'courseDescription' => $newCourse->courseDescription,
        'coursePoints' => $newCourse->coursePoints,
        'status' => 1,
    ]);
    $response->assertStatus(404);

    // Tests the validation rules.
    // With empty data.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.courses.update', ['course' => $course->courseCode]), []);
    $response->assertSessionHasErrors([
        'courseCode' => 'The Course Code is required.',
        'courseName' => 'The Course Name is required.',
        'coursePoints' => 'The Course Points is required.',
    ]);
    // With some invalid data.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.courses.update', ['course' => $course->courseCode]), [
        'courseCode' => $anotherCourse->courseCode,
        'courseName' => $newCourse->courseName,
        'coursePoints' => 9999,
        'status' => 99,
    ]);
    $response->assertSessionHasErrors([
        'courseCode' => 'The Course Code has already been taken.',
        'coursePoints' => 'The Course Points can only be a value among 5, 10, or 15.',
        'status' => 'The Status must be between 0 and 2.',
    ]);

    // Successful submission.
    $response = $this->actingAs($user)
            ->patch(route('dashboard.courses.update', ['course' => $course->courseCode]), [
        'courseCode' => $newCourse->courseCode,
        'courseName' => $newCourse->courseName,
        'courseDescription' => $newCourse->courseDescription,
        'coursePoints' => $newCourse->coursePoints,
        'status' => 1,
    ]);
    $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', "The \"{$newCourse->courseCode} - {$newCourse->courseName}\" course has been successfully updated.");
  }

  /**
   * Tests the functionality of deleting an existing course.
   * @return void
   */
  public function testDestroy() {
    $user = User::inRandomOrder()->first();
    $course = Course::whereNull('deleted_at')->inRandomOrder()->first();

    // Tests the deletion confirmation page of a specific course.
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.deletion-confirmation', ['course' => 'non-existing-course-code']));
    $response->assertStatus(404);
    $response = $this->actingAs($user)
            ->get(route('dashboard.courses.deletion-confirmation', ['course' => $course->courseCode]));
    $response->assertSeeText($course->courseCode)
            ->assertSeeText($course->courseName);

    // Tests the actual deletion process.
    $response = $this->actingAs($user)
            ->delete(route('dashboard.courses.destroy', ['course' => 'non-existing-course-code']));
    $response->assertStatus(404);
    $response = $this->actingAs($user)
            ->delete(route('dashboard.courses.destroy', ['course' => $course->courseCode]));
    $response->assertSessionHasNoErrors()
            ->assertSessionHas('status', "The \"{$course->courseCode} - {$course->courseName}\" course has been marked as deleted.");
    $course = Course::withTrashed()->find($course->courseCode);
    $this->assertNotEmpty($course->deleted_at);
    $course->deleted_at = NULL;
    $course->save();
  }

}
