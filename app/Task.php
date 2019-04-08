<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Nom de la table associée au modèle
     * Pas nécessaire si vous nommer vos table au pluriel
     *
     * @var string
     */
    protected $table = 'task';

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