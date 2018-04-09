<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'creator_id' => function () {
        	return factory(App\User::class)->create()->id;
        },
        'version_id' => function () {
        	return factory(App\Version::class)->create()->id;
        },
        'body' => $faker->sentence
    ];
});
