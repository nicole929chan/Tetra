<?php

use Faker\Generator as Faker;

$factory->define(App\Selection::class, function (Faker $faker) {
    return [
        'room_id' => function () {
    		return factory(App\Room::class)->create()->id;
    	},
    	'image_path' => 'files/1/selections/demo_main.jpg'
    ];
});
