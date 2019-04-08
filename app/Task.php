<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    /**
     * Liste des attributs modifiables
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'order', 'completed', 'due_date'
    ];




    /**
     * Liste des attributs cachés
     * Seront exclus de l'objet JSON dans les réponses
     *
     * @var array
     */
    protected $hidden = [];
}