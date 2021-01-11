<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Blog;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        'title' => $faker->company,
        'content' => $faker->paragraph,
        'author_id' => User::first()->id
    ];
});

//executando: php artisan tinker,
// depois de abrir o terminal: factory(App\Blog::class,5)->create()