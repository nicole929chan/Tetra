<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
	$comment = factory(App\Comment::class)->create();

    return [
        'owner_id' => function () {
        	return factory(App\User::class)->create()->id;
        },
        'repliable_type' => get_class($comment),
        'repliable_id' => $comment->id,
        'body' => $faker->sentence
    ];
});
