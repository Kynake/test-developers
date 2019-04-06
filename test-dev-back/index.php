<?php
use Phalcon\Exception;
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
  function ($id) use ($app) {
    // Operation to fetch document with id $id
    // echo "GET api documents $id";

    $sqldocs = 'SELECT * FROM Docspace\Documents  WHERE id          = :id:';
    $sqlsigs = 'SELECT * FROM Docspace\Signatures WHERE id_document = :id:';

    $documents  = $app->modelsManager->executeQuery($sqldocs, ['id' => $id]);
    $signatures = $app->modelsManager->executeQuery($sqlsigs, ['id' => $id]);

    $data = [];
    foreach ($documents as $doc) {
      $data[] = [
        'id'         => $doc->id,
        'name'       => $doc->name,
        'content'    => $doc->content,
        'timestamp'  => $doc->timestamp,
        'Signatures' => $signatures
      ];
    }

    if(count($data) > 0) {
      echo json_encode($data[0]);
    } else {
      echo json_encode(null);
    }
  }
);

/**
 * Signatures Routes
 */

// GET signatures
$app->get(
  '/api/signatures',
  function () use ($app) {
    // Operation to fetch all the signatures
    // echo "GET api signatures";

    $phql = 'SELECT * FROM Docspace\Signatures ORDER BY id_document, ordering';
    $signatures = $app->modelsManager->executeQuery($phql);
    $data = [];

    foreach ($signatures as $sig) {
      $data[] = [
        'id'          => $sig->id,
        'name'        => $sig->name,
        'issuer'      => $sig->issuer,
        'timestamp'   => $sig->timestamp,
        'ordering'    => $sig->ordering,
        'id_document' => $sig->id_document
      ];
    }

    echo json_encode($data);
  }
);

// GET signature by id
$app->get(
  '/api/signatures/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to fetch signature with id $id
    // echo "GET api signatures $id";

    $sqlsigs = 'SELECT * FROM Docspace\Signatures WHERE id = :id:';
    $sqldocs = 'SELECT * FROM Docspace\Documents  WHERE id = :id_document:';

    $signatures = $app->modelsManager->executeQuery($sqlsigs, ['id' => $id]);
    // $documents  = $app->modelsManager->executeQuery($sqldocs, ['id' => $id]);

    // echo json_encode($signatures[0]);

    // $tempData = [];
    // foreach ($documents as $doc) {
    //   $tempData[] = [
    //     'id'          => $sig->id,
    //     'name'        => $sig->name,
    //     'issuer'      => $sig->issuer,
    //     'timestamp'   => $sig->timestamp,
    //     'ordering'    => $sig->ordering,
    //     'id_document' => $sig->id_document
    //   ];
    // }

    if(count($signatures) > 0) {
      $id_document = $signatures[0]->id_document;
      $document = $app->modelsManager->executeQuery($sqldocs, ['id_document' => $id_document]);

      $data = [];
      foreach ($signatures as $sig) {
        $data[] = [
          'id'          => $sig->id,
          'name'        => $sig->name,
          'issuer'      => $sig->issuer,
          'timestamp'   => $sig->timestamp,
          'ordering'    => $sig->ordering,
          'id_document' => $sig->id_document,
          'Document'    => $document[0]
        ];
      }

      echo json_encode($data[0]);
    } else {
      echo json_encode(null);
    }
  }
);

// POST signature
$app->post(
    '/api/signatures',
    function () use ($app) {
      // Operation to create a new signature
      echo "POST api signature";
    }
);

// PUT signature
$app->put(
    '/api/signatures/{id:[0-9]+}',
    function ($id) use ($app) {
      // Operation to update a signature with id $id
      echo "PUT api signature $id";
    }
);

$app->delete(
  '/api/signatures/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to delete a signature with id $id
    echo "DELETE api signature $id";
  }
);

$app->handle();
// phpinfo();

?>