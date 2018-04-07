<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
    	'project_id' => function () {
    		return factory(App\Project::class)->create()->id;
    	},
        'name' => $faker->word,
        'selection' => 0,
        'version' => 0,
        'status' => 0
    ];
});
