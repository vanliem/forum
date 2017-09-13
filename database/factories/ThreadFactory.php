<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'user_id' => function () {
        	return factory('App\User')->create()->id;
        },
       'channel_id' => function () {
       		return factory('App\Channel')->create()->id;
       }
    ];
});
