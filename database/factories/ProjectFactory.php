<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'name' => str_random(15),
        'started_at' => $faker->date($format = 'Y-m-d', $max = 'now') . ' ' . $faker->time($format = 'H:i:s', $max = 'now'),
        'ended_at' => $faker->date($format = 'Y-m-d', $max = 'now') . ' ' . $faker->time($format = 'H:i:s', $max = 'now'),
        'status_id' => $faker->numberBetween($min = 1, $max = 2),
        'user_id' => $faker->numberBetween($min = 16, $max = 25),
    ];
});
