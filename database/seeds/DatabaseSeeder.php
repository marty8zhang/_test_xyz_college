<?php

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

    factory(App\Student::class, 100)->create();

    factory(App\Course::class, 30)->create();
  }

}
