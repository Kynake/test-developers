<?php
use Pahlcon\Exception;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Postgresql as PdoPostgresql;
use Phalcon\Db\Column as Column;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces([ 'Docspace' => __DIR__ . '/models/' ]);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
try{
  $di->set(
    'db',
    function () {
      return new PdoPostgresql([
        'host'     => 'database',
        'port'     => 5432,
        'dbname'   => 'devtest',
        'username' => 'docker',
        'password' => 'senha'
      ]);
    }
  );
} catch(Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}

// Create and bind the DI to the application
$app = new Micro($di);

/**
 *  Documents Routes
 */

// GET documents
$app->get(
    '/api/documents',
    function () use ($app) {
      // Operation to fetch all the documents

      // echo "GET api documents";

      $phql = 'SELECT * FROM Docspace\Documents ORDER BY name';
      $documents = $app->modelsManager->executeQuery($phql);
      $data = [];

      foreach ($documents as $doc) {
        $data[] = [
          'id'        => $doc->id,
          'name'      => $doc->name,
          'content'   => $doc->content,
          'timestamp' => $doc->timestamp
        ];
      }

      echo json_encode($data);
    }
);

// GET document by id
$app->get(
    '/api/documents/{id:[0-9]+}',
    function ($id) {
      echo "GET api documents $id";
      // Operation to fetch document with id $id
    }
);

/**
 * Signatures Routes
 */

// GET signatures
$app->get(
  '/api/signatures',
  function () {
    echo "GET api signatures";
    // Operation to fetch all the signatures
  }
);

// GET signature by id
$app->get(
  '/api/signatures/{id:[0-9]+}',
  function ($id) {
    echo "GET api signatures $id";
    // Operation to fetch signature with id $id
  }
);

// POST signature
$app->post(
    '/api/signatures',
    function () {
      echo "POST api signature";
      // Operation to create a new signature
    }
);

// PUT signature
$app->put(
    '/api/signatures/{id:[0-9]+}',
    function ($id) {
      echo "PUT api signature $id";
      // Operation to update a signature with id $id
    }
);

$app->handle();
// phpinfo();


?>