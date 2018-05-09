<?php

/**
 * The Seeder for generating all required dummy data for test-driving purposes.
 * @author Marty Zhang
 * @version 0.9.201805032225
 */
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table('users')->insert([
        'name' => 'tester',
        'email' => 'tester@test.com',
        'password' => bcrypt('tester'),
    ]);

    factory(App\Student::class, 500)->create();

    factory(App\Course::class, 50)->create();

    // Development Note: Because of the unique key constraint on the sId, cId and semester, this Factory might encounter an SQL error when executing. Usually try to execute it few more times will get you a good result, or otherwise you can comment out the above two Factories and fall back to Method 2 in StudentsCoursesFactory.
    factory(App\StudentsCourses::class, 500)->create();

    factory(App\Test::class, 50)->create();
  }

}
