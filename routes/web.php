<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
// Création du groupr api : http://localhost:8000/api/
$router->group(['prefix' => 'api'], function () use ($router) {

    // Toutes les tâches
    $router->get('tasks',  ['uses' => 'TaskController@showAllTasks']);

    // Détail d'une tâche
    $router->get('tasks/{id}', ['uses' => 'TaskController@showOneTask']);

    // Ajout d'une tâche
    $router->post('tasks', ['uses' => 'TaskController@create']);

    // Suppression d'une tâche
    $router->delete('tasks/{id}', ['uses' => 'TaskController@delete']);

    // Modification d'une tâche
    $router->put('tasks/{id}', ['uses' => 'TaskController@update']);

    // Fermeture d'une tâche : tâche terminée
     $router->put('tasks/{id}/completed', ['uses' => 'TaskController@completed']);

    // Ouverture d'une tâche : tâche non-terminée
     $router->delete('tasks/{id}/completed', ['uses' => 'TaskController@unCompleted']);
});
