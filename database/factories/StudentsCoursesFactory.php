<?php

/**
 * The Factory for generating dummy data for the students and courses enrollment MODEL.
 * @author Marty Zhang
 * @version 0.9.201805032220
 */
use Faker\Generator as Faker;

$factory->define(App\StudentsCourses::class, function (Faker $faker) {
  return [
      'sId' => function () {
        return App\Student::inRandomOrder()->first()->id; // Method 1: Might encounter unique key constraint errors.
//        return factory(App\Student::class)->create()->id; // Method 2: Not good for getting the dummy data that truely reflect the many-to-many relationship.
      },
      'cId' => function () {
        return App\Course::inRandomOrder()->first()->id; // Method 1: See above.
//        return factory(App\Course::class)->create()->id; // Method 2: See above.
      },
      'semester' => '20' . $faker->numberBetween(10, 18) . '0' . $faker->numberBetween(1, 4),
      'status' => $faker->numberBetween(0, 3),
  ];
});
