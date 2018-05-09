<?php

/**
 * The Factory for generating dummy data for the Student MODEL.
 * @author Marty Zhang
 * @version 0.9.201805041115
 */
use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
  return [
//      'id' => $faker->unique()->numberBetween(1, 999999999), // Development Note: This attribute here is necessary when StudentFactory is used inside the StudentsCoursesFactory.
      'studentId' => $faker->unique()->numberBetween(20000100001),
      'firstName' => $faker->firstName,
      'lastName' => $faker->lastName,
      'middleName' => strtoupper($faker->randomLetter) . '.',
      'birthday' => $faker->date(),
      'homeAddress' => $faker->address,
      'contactNumber' => $faker->phoneNumber,
      'studentEmailAddress' => $faker->unique()->safeEmail,
      'personalEmailAddress' => $faker->unique()->safeEmail,
      'status' => $faker->numberBetween(0, 4),
  ];
});
