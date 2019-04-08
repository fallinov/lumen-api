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

