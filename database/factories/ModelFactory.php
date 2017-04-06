<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
/*
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});
*/
$factory->define(App\Models\Media::class, function (Faker\Generator $faker) {
    return [
				'title' => $faker->word,
				'description' => $faker->sentence,
				'view_count'=> $faker->randomNumber,
				'email' =>$faker->email,
				'web_link'=>$faker->url,
				'type'=> $faker->randomElement(array('video','text','image')),
				'file_name' => $faker->word,
				'screen_name' => $faker->firstName,
    ];
});
