<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID
            $table->string('title', 100); // Titre de max 100 caractères
            $table->text('content')->nullable(); // Contenu détaillé, peut être NULL
            $table->integer('order')->default(0); // Ordre, par défaut 0
            $table->boolean('completed')->default(0); // Tâche terminée, par défaut 0 (false)
            $table->dateTime('due_date')->useCurrent(); // Date de fin, par défaut heure et date actuelle
            $table->timestamps(); // Ajoute le champs mouchards created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
