<?php

use Faker\Generator as Faker;

$factory->define(App\Selection::class, function (Faker $faker) {
    return [
        'room_id' => function () {
    		return factory(App\Room::class)->create()->id;
    	},
    	'image_path' => 'images/project/selections/image.jpg'
    ];
});
