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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

// Définition d'une factory pour le modèle Task
$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence, // Phrase aléatoire
        'content' => $faker->paragraph, // Paragraphe aléatoire
        'order' => $faker->numberBetween(1,100), // Nombre entre 1 et 100
        'completed' => (int) $faker->boolean, // Booléan aléatoire converti en entier
        'due_date' => $faker->date('Y-m-d H:i:s') // Date aléatoire au format MySQL
    ];
});