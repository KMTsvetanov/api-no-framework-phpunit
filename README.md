INSTALLATION
------------

docker-compose build

docker-compose up -d

docker cp database.sql mysql3:/database.sql;

docker exec -i mysql3 mysql -uroot -proot DBname < database.sql

exit

docker exec -it api-no-framework-phpunit_phpfpm_1 /bin/bash

cd web

composer update

REST API INTRODUCTION
------------

Make a REST API supporting the following methods:

GET  /news

@return [{id, title, date, text}]

--

GET  /news/:id

@return {id, title, date, text}

--

POST /news/:id [:title, :date, :text]

@return {success}

--

POST /news/ [:title, :date, :text]

@return {id, title, date, text}

--

DELETE /news/:id

@return {success}

---

Write a Router class to the API that can be used as follows:

$router = new Router();

$router->map( 'GET, '/news/[:id]', function($id) {});

$router->map( 'POST', '/news/:id', 'News#update' {});

$router->map( 'POST', '/news/', 'News#create {});

$router->map( 'DELETE', '/news/:id', [$news, 'delete']);

$router->map( 'GET', '/users/:userId/comments/[:id]', function($userId, $id) {}); // this is just usage example

$result = $router->match();

---
Requirements

The code should be OOP and should be as simple, clear and convenient for expansion as possible

The data should be saved in MySQL (optimized database) and returned as json in the specified format.

Do not use framework and ready code, but have a structure

Validation of user input data

Access validation (administrator / normal user)

No security issues

UnitTest