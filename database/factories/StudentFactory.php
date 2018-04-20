<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
  return [
      'studentId' => $faker->unique()->numberBetween(20000100001),
      'firstName' => $faker->firstName,
      'lastName' => $faker->lastName,
      'middleName' => strtoupper($faker->randomLetter) . '.',
      'birthday' => $faker->date(),
      'homeAddress' => $faker->address,
      'contactNumber' => $faker->phoneNumber,
      'studentEmailAddress' => $faker->unique()->safeEmail,
      'personalEmailAddress' => $faker->unique()->safeEmail,
      'status' => $faker->numberBetween(1, 5),
  ];
});
