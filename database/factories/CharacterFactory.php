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
        'freq' => $faker->randomDigit,
        'heisig_keyword' => $faker->word,
        'pinyin' => $faker->word,
        'translations' => $faker->word,


    ];
});
