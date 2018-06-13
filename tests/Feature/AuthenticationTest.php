<?php

/**
 * The PHPUnit Test class for testing authentication of the Dashboard.
 * @author Marty Zhang
 * @createdAt 11:46 PM AEST, 9 Jun 2018
 * @version 0.9.201806132308
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AuthenticationTest extends TestCase {

  /**
   * Tests authentication again the project web root.
   */
  public function testRootAuthentication() {
    // Homepage should be redirected to '/dashboard'.
    $response = $this->get('/');
    $response->assertRedirect('/dashboard');

    // If not a logged-in user, when trying to access '/dashboard' should be redirected to the login page.
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');

    // A logged-in user should be able to access '/dashboard'.
    $user = User::inRandomOrder()->first();
    $response = $this->actingAs($user, 'api')
            ->get('/dashboard');
    $response->assertStatus(200);
  }

}
