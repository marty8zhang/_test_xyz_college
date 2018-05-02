<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
  return [
      'courseCode' => $faker->unique()->regexify('[A-Z]{3,4}[0-9]{6}'),
      'courseName' => rtrim(ucwords($faker->sentence(2)), '.'),
      'courseDescription' => '<p>' . implode('</p><p>', $faker->paragraphs(3)) . '</p>',
      'coursePoints' => $faker->randomElement(array(10, 15)),
      'status' => $faker->numberBetween(0, 3),
  ];
});
