# Création d'une API avec Lumen

Sources :

* https://auth0.com/blog/developing-restful-apis-with-lumen/

## Environnement de développement

Installer les outils suivants :

* (iOs) Homebrew : https://brew.sh/index_fr
* git : https://git-scm.com/
* Composer : https://getcomposer.org/download/



Créer un nouveau schéma `tasks` sur votre base de donnée MySQL.



## Installer Lumen

Exécuter la commande suivante dans le terminal pour créer un nouveau projet Lumen :

```shell
composer create-project --prefer-dist laravel/lumen lumen-api
```

Monter dans le nouveau dossier

```shell
cd lumen-api
```

Démarrer le serveur php, et tetser le bon fonctionnement de lumen : http://localhost:8000/

```shell
php -S localhost:8000 -t public
```



### Eloquent et Facades

#### Eloquent

ORM inclu avec Laravel facilitant l'interfacage avec une base de données.

Chaque table de la base est représentée par un **Model PHP** qui est utilisé pour intéragir avec les données.

ORM (object-relational mapping)  : interface entre un programme applicatif et une base de données relationnelle.

#### Facades

Fournit une interface **statique** des classes disponibles dans les différents sercvices de l'application.

Les Facades de Laravel sont définies dans le namespace `Illuminate\Support\Facades`. On peut ainsi facilement y accéder :

use Illuminate\Support\Facades\Cache;
Route::get('/cache', function () {
return Cache::get('key');
});

#### Paramètre BD

Modifier les paramètres base de données du fichier `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tasks
DB_USERNAME=root
DB_PASSWORD=
```

#### Activation de Eloquent et Facades

Ouvrir le fichier `bootstrap/app.php` et décommenter les lignes suivantes :

```php
// $app->withFacades();
// $app->withEloquent();
```



## Création de la base de données

La création des tables de la base de données se fera via des migration.

> Les migrations sont comme un outils de versionning pour votre base de données, elles permettent à votre équipe de facilement modifier et partager le shéma de la base de données.

### Migration `create_task_table`

Entrez la commande suivante pour créer le fichier de migration de la future table task.

```shell
php artisan make:migration create_task_table
```

La nouvelle migration sera créée dans le dossier `datables/migrations`.

Le nom des fichiers de migration se compose d'un timestamp permettant à Lumen de déterminer l'ordre des migrations. Exemple : `2019_04_08_135158_create_task_table.php`

Ouvrez le fichier de migration et modifier le comme suit :

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
  /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task';
  
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
```

### Création de la table `task` dans la base

Excéucter la migration avec la commande suivante.

```shell
php artisan migrate
```

Vérifier que la table `task` a correctement été créée dans votre base de données.

![bd-table-task](/Users/stevefallet/Dev/lumen-api/_docs/bd-table-task.png)



## Création du modèle `Task`

L'ORM Eloquent que nous avons précédement activé, offre une implémentation pour travailler avec votre base de données. 

Chaque table de base de données a un "Modèle" correspondant qui est utilisé pour interagir avec cette table. Les modèles vous permettent d'interroger les données de vos tables et d'insérer de nouveaux enregistrements dans la table.

Les modèles se trouvent dans le dossier `app/` de Lumen. 

Créez le fichier `app/Task.php` en y ajoutant le code suivant :

```php
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
```



## Création des routes de l'API

Les routes sont déclarée dans le fichier `routes/web.php` de Lumen. Ouvrez ce fichier et modifier-le comme suit :

```php
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
// Création du groupe de route api : http://localhost:8000/api/
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
```

Dans le code ci-dessus, nous avons abstrait la fonctionnalité de chaque route dans un contrôleur, `TaskController`. 

Nous avons également créer un groupe de routes. Les groupes de routes permettent de partager des attributs de routes, tels que des middleware ou des namespaces, sur un grand nombre de routes.

Dans notre exemple, chaque route aura un préfixe `/api`.

Prochaine étape, création du controller `TaskController`.



## Création du controller `TaskController`

Les controlleurs se trouvent dans le dossier `app/Http/Controllers`. 

Créer un fichier TaskController.php et ajoutez-y le code suivant :







Analysons le code ci-dessus. Tout d'abord, nous avions besoin du modèle Task, utilisez App\Author. Pour aller de l'avant, nous avons invoqué les méthodes nécessaires à partir du modèle Task pour chaque méthode de contrôleur. Nous avons ici cinq méthodes. showAllAuthors, showOneAuthor, créer, mettre à jour et supprimer.

showAllAuthors - /GET
showOneAuthor - /GET
créer - /POST
update - /PUT
supprimer - /DELETE

Par exemple, si vous faites une demande POST à /api/authors API endpoint, la fonction create sera appelée.

La méthode showAllAuthors vérifie toutes les ressources de l'task.
La méthode de création crée une nouvelle ressource task.
La méthode showOneAuthor vérifie la présence d'une seule ressource task.
La méthode de mise à jour vérifie si une ressource task existe et permet la mise à jour de la ressource.

La méthode de suppression vérifie si une ressource task existe et la supprime.

response() est une fonction d'aide globale qui obtient une instance de la factory de réponse. response()->json() retourne simplement la réponse au format JSON.
200 est un code d'état HTTP qui indique que la requête a réussi.
201 est un code d'état HTTP qui indique qu'une nouvelle ressource vient d'être créée.
findOrFail lance une méthode ModelNotFoundException si aucun résultat n'est trouvé.

Enfin, testez les routes API avec Postman.

Traduit avec www.DeepL.com/Translator

