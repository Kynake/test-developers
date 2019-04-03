<?php
use Phalcon\Mvc\Micro;

$app = new Micro();

// Retrieves all robots
$app->get(
    '/api/teste',
    function () {
      echo "GET api robots";
      // Operation to fetch all the robots
    }
);

// Retrieves robots based on primary key
$app->get(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
      echo "GET api robots $id";
      // Operation to fetch robot with id $id
    }
);

// Adds a new robot
$app->post(
    '/api/robots',
    function () {
      echo "POST api robots";
      // Operation to create a fresh robot
    }
);

// Updates robots based on primary key
$app->put(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
      echo "PUT api robots $id";
      // Operation to update a robot with id $id
    }
);

// Deletes robots based on primary key
$app->delete(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
      echo "DELETE api robots $id";
      // Operation to delete the robot with id $id
    }
);

$app->handle();
// phpinfo();


?>