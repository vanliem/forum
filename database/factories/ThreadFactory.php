<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraph,
        'user_id' => function () {
        	return factory('App\User')->create()->id;
        },
       'channel_id' => function () {
       		return factory('App\Channel')->create()->id;
       }
    ];
});
