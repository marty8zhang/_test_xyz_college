<?php

/**
 * The Factory for generating dummy data for the Course MODEL.
 * @author Marty Zhang
 * @version 0.9.201805032216
 */
use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
  return [
//      'id' => $faker->unique()->numberBetween(1, 9999), // Development Note: This attribute here is necessary when CourseFactory is used inside the StudentsCoursesFactory.
      'courseCode' => $faker->unique()->regexify('[A-Z]{3,4}[0-9]{6}'),
      'courseName' => rtrim(ucwords($faker->sentence(2)), '.'),
      'courseDescription' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
      'coursePoints' => $faker->randomElement(array(10, 15)),
      'status' => $faker->numberBetween(0, 3),
  ];
});
