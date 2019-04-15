<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class TasksTest extends TestCase
{
    use DatabaseTransactions; // Rollback la BD après chaque tests

    public function testCreateATask()
    {
        //$faker = Faker::create();
        $newTask = factory('App\Task')->make();
        //var_dump($newTask->toArray());
        $response = $this->call('POST','/api/tasks', $newTask->toArray());
        $this->assertEquals(201, $response->status());
        $this->seeJson($newTask->toArray());
        $this->seeJsonStructure([
            "id",
            "title",
            "content",
            "order",
            "completed",
            "due_date",
        ]);
        $this->seeInDatabase('task', $newTask->toArray());

    }

    public function testCreateATaskWithErrors()
    {
        //$faker = Faker::create();
        $newTask = factory('App\Task')->make([
            'title' => ''
        ]);
        $response = $this->call('POST','/api/tasks', $newTask->toArray());
        $this->assertEquals(422, $response->status());
        $this->notSeeInDatabase('task', $newTask->toArray());
    }


    public function testGetAllTasks()
    {
        // Création de 10 tâches dans la BD
        $tasks = factory('App\Task', 10)->create();
        $response = $this->call('GET', '/api/tasks');
        $this->assertEquals(200, $response->status());
        $this->seeJsonEquals($tasks->toArray());
    }
}