<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'creator_id' => function () {
        	return factory(App\User::class)->create(['admin' => true])->id;
        },
        'address' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->country,
        'status' => false
    ];
});
