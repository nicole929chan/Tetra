<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
	// $comment = factory(App\Comment::class)->create();

    return [
        'owner_id' => function () {
        	return factory(App\User::class)->create()->id;
        },
        'repliable_type' => '',
        'repliable_id' => 0,
        'body' => $faker->sentence
    ];
});
