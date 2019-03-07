<?php

use Faker\Generator;
use adamhut\Wink\WinkCategory;


$factory->define(WinkCategory::class, function (Faker\Generator $faker) {
    $sentence = $faker->sentence;
    return [
        'id'    =>$faker->uuid,
        'slug' => str_slug($sentence),
        'name' => $sentence,

    ];
});

