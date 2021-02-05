# Todolist

Projet création d'une API from scratch en PHP, réalisé dans le cadre d'un test technique pour Médialis

## Subject

* Création d’une API from scratch
* L’api à réaliser servira à créer des TODOLIST.
* On doit donc pouvoir ajouter/modifier/supprimer des listes et les items des listes.
* L’api n’intègrera que le GET / POST / DELETE, on se passera du PUT.
* Il doit y avoir un petit système de credentials (un token statique est accepté)
* Le développement doit respecter l’architecture MVC, et la partie back doit être totalement réalisée en objet.
* La version de PHP est libre mais a minima du 5.6.
* Niveau base de données le choix est libre.
* Et pour le front, IHM/techno libre.

## Routes

* 'GET' /api/authToken
* 'GET' /api/taskList
* 'GET' /api/taskList/{idList}
* 'GET' /api/taskList/{taskListId}/tasks
* 'POST' /api/user
* 'POST' /api/taskList
* 'POST' /api/taskList/{taskListId}
* 'POST' /api/taskList/{taskListId}/task
* 'POST' /api/task/{taskId}
* 'DELETE' /api/taskList/{taskListId}
* 'DELETE' /api/task/{taskId}

## Exemple of request

| Description  | Request path  | Parameter        |Expected return  |
| :--- | :--- |:--- | :--- |
| Create user       | 'POST' /api/user                       | Body {username: "root", password: "Root123*"}                           | 201 "auth_token": "mytoken"  |
| Login user        | 'GET' /api/authToken                   | Header {username: "root", password: "Root123*"}                         | 200 "auth_token": "mytoken"  |
| Create a taskList | 'POST' /api/taskList                   | Header {auth_token": "mytoken"} Body {"title: "My first work list"      | 201 "message": "New list created" |
| Get all taskList  | 'GET' /api/taskList                    | Header {auth_token": "mytoken"}                                         | 200 {"0":{"id_user":26, "id_tasklist":28, "title":"My Home list"}}} |
| Get one taskList  | 'GET' /api/taskList/{taskListId}       | Header {auth_token": "mytoken"}                                         | 200 {"0":{"id_user":26, "id_tasklist":28, "title":"My Home list"}}} |
| Update a taskList | 'POST' /api/taskList/{taskListId}      | Header {auth_token": "mytoken" Body {"title": "new title"}              | 200 {"message": "taskList n°28 has been updated"} |
| Delete a taskList | 'DELETE' /api/taskList/{taskListId}    | Header {auth_token": "mytoken"                                          | 200 {"message": "taskList n°28 has been deleted" |
| Get all tasks     | 'GET' /api/taskList/{taskListId}/tasks | Header {auth_token": "mytoken"                                          | 200 {"0":{"id_task":5,"id_user":26, "id_tasklist":29, "content":"finish front todolist", "status":"active", "created":"2019-11-22 14:12:07", "updated":"2019-11-22 14:12:07"}} |
| Create a task     | 'POST' /api/taskList/{taskListId}/task | Header {auth_token": "mytoken" Body {"content": "new task"}             | 201 {"message":"New task created"}  |
| Update a task     | 'POST' /api/task/{taskId}              | Header {auth_token": "mytoken" Body {"content" : "new content", "status" : "done", "id_tasklist" : "29"}| 200 {"message":"Task n°7 has been updated"} |
| Delete a task     | 'DELETE' /api/task/{taskId}            | Header {auth_token": "mytoken"                                          | 200 {"message":"taskList n°7 has been deleted"} |

## Installation

* Download sources.

```sh
git clone https://github.com/Liliaze/todolist.git todolist
cd todolist
```

* Create your database with `./Migrations/database_setup.sql` file.

* Run your API on localhost server (Ex: WampServer64).

* Configure the parameter of your server on `./config.php`
