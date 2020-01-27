<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Character;
use Faker\Generator as Faker;



$factory->define(Character::class, function (Faker $faker) {
    return [
        /*
            $table->bigIncrements('id');
            $table->string('simp_char');
            $table->string('trad_char');
            $table->integer('freq');
            $table->string('heisig_keyword');
            $table->string('pinyin');
            $table->string('translations');
            $table->timestamps();
        */

        'simp_char' => $faker->lastName,
        'trad_char' => $faker->lastName,
        'freq' => $faker->numberBetween($min = 1, $max = 5),
        'heisig_keyword' => $faker->word,
        'heisig_number' => $faker->unique()->numberBetween($min = 1, $max = 5000),
        'pinyin' => $faker->word,
        'translations' => $faker->word,


    ];
});
