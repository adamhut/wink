<?php

use Faker\Generator;
use Wink\WinkCategory;


$factory->define(WinkCategory::class, function (Faker\Generator $faker) {
    $sentence = $faker->sentence;
    return [
        'id'    =>$faker->uuid,
        'slug' => str_slug($sentence),
        'name' => $sentence,

    ];
});

