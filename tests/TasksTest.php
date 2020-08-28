<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class TasksTest extends TestCase
{
    use DatabaseTransactions; // Rollback la BD après chaque tests

    public function testCreateATask()
    {
        //$faker = Faker::create();

        // Crée un nouvel objet Task avec des données aléatoire
        // La méthode make() crée un objet sans le sauvegarder dans la BD
        // La méthode create() créer un objet et le sauvegarde dans la BD
        $newTask = factory('App\Task')->make();

        // Appelle la route de création d'une tache et teste la réponse
        $this->post('/api/tasks', $newTask->toArray())
            ->seeStatusCode(201) // Test si status de la réponse = 201
            ->seeJson($newTask->toArray()) // Test si nouvelle tâche dans la réponse
            ->seeJsonStructure([ // Test si la structure de la réponse est OK
                "id",
                "title",
                "content",
                "order",
                "completed",
                "due_date",
            ]);

        // Test si nouvelle tâche existe dans la BD
        $this->seeInDatabase('task', $newTask->toArray());

    }

    public function testCreateATaskWithErrors()
    {
        //$faker = Faker::create();
        $newTask = factory('App\Task')->make([
            'title' => ''
        ]);

        $this->post('/api/tasks', $newTask->toArray())
            ->seeStatusCode(422) // Test si status de la réponse = 422
            ->seeJsonContains([ // Test si le contenu de la réponse est OK
                    "title" => ["Le champ titre est obligatoire."]
            ]);


        $this->notSeeInDatabase('task', $newTask->toArray());
    }


    public function testGetAllTasks()
    {
        // Création de 10 tâches dans la BD
        $tasks = factory('App\Task', 5)->create();

        $this->get('/api/tasks')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                '*' => [
                    "id",
                    "title",
                    "content",
                    "order",
                    "completed",
                    "due_date",
                    ]
            ]);
    }
}