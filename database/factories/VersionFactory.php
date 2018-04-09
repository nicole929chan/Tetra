<?php

use Faker\Generator as Faker;

$factory->define(App\Version::class, function (Faker $faker) {
    return [
        'room_id' => function () {
        	return factory(App\Room::class)->create()->id;
        },
        'image_path' => 'files/1/versions/version1.png',
        'active' => true
    ];
});
