<?php

use adamhut\Wink\WinkPost;
use Faker\Generator;


$factory->define(WinkPost::class, function (Faker\Generator $faker) {
    $sentence = $faker->sentence;
    return [
        'id'    => $faker->uuid,
        'slug'  => str_slug($sentence),
        'title' => $sentence,
        'excerpt'   => $faker->sentence,
        'body'  => $faker->paragraph(),
        'featured_image'    => null,
        'featured_image_caption'    => '',
        'author_id' => $faker->uuid
    ];
});
