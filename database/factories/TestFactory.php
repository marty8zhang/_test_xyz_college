<?php

use Faker\Generator as Faker;

$factory->define(App\Test::class, function (Faker $faker) {
  return [
      'name' => rtrim(ucwords($faker->unique()->sentence(2)), '.'),
  ];
});
