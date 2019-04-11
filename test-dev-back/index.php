<?php
use Phalcon\Exception;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Postgresql as PdoPostgresql;
use Phalcon\Http\Response;

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
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");

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

    $response->setJsonContent([
      'status' => 'OK',
      'data'   => $data
    ]);
    
    return $response;
  }
);

// GET document by id
$app->get(
  '/api/documents/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to fetch document with id $id
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");

    $sqldocs = 'SELECT * FROM Docspace\Documents  WHERE id          = :id:';
    $sqlsigs = 'SELECT * FROM Docspace\Signatures WHERE id_document = :id: ORDER BY ordering, name';

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
      $response->setJsonContent([
        'status' => 'OK',
        'data'   => $data[0]
      ]);
    } else {
      $response->setJsonContent([
        'status' => 'ERROR',
        'data'   => 'Document not found'
      ]);
    }

    return $response;
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
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");

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

    $response->setJsonContent([
      'status' => 'OK',
      'data'   => $data
    ]);

    return $response;
  }
);

// GET signature by id
$app->get(
  '/api/signatures/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to fetch signature with id $id
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");

    $sqlsigs = 'SELECT * FROM Docspace\Signatures WHERE id = :id:';
    $sqldocs = 'SELECT * FROM Docspace\Documents  WHERE id = :id_document:';

    $signatures = $app->modelsManager->executeQuery($sqlsigs, ['id' => $id]);

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

      $response->setJsonContent([
        'status' => 'OK',
        'data'   => $data[0]
      ]);
    } else {
      $response->setJsonContent([
        'status' => 'ERROR',
        'data'   => 'Signature not found'
      ]);
    }

    return $response;
  }
);

// POST signature
$app->post(
  '/api/signatures',
  function () use ($app) {
    // Operation to create a new signature
    $signature = $app->request->getJsonRawBody();
    
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");
    
    $sqlsig = 'INSERT INTO Docspace\Signatures (name, issuer, timestamp, ordering, id_document) VALUES (:name:, :issuer:, :timestamp:, :ordering:, :id_document:)';

    $status = $app->modelsManager->executeQuery($sqlsig, [
      'name'        => $signature->name,
      'issuer'      => $signature->issuer,
      'timestamp'   => $signature->timestamp,
      'ordering'    => $signature->ordering,
      'id_document' => $signature->id_document
    ]);

    if($status->success() === true) {
      $response->setStatusCode(201, 'Created');
      $signature->id = $status->getModel()->id;

      $response->setJsonContent([
        'status' => 'OK',
        'data'   => $signature,
      ]);
    } else {
      $response->setStatusCode(409, 'Conflict');

      // Send errors to the client
      $errors = [];

      foreach ($status->getMessages() as $message) {
        $errors[] = $message->getMessage();
      }

      $response->setJsonContent([
        'status'   => 'ERROR',
        'data' => $errors,
      ]);
    }

    return $response;
  }
);

// PUT signature
$app->put(
  '/api/signatures/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to update a signature with id $id
    $signature = $app->request->getJsonRawBody();
    
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");

    $sqlsig = 'UPDATE Docspace\Signatures
               SET name = :name:, issuer = :issuer:, timestamp = :timestamp:, ordering = :ordering:, id_document = :id_document:
               WHERE id = :id:';

    $status = $app->modelsManager->executeQuery($sqlsig, [
      'name'        => $signature->name,
      'issuer'      => $signature->issuer,
      'timestamp'   => $signature->timestamp,
      'ordering'    => $signature->ordering,
      'id_document' => $signature->id_document,
      'id'          => $signature->id
    ]);

    if($status->success() === true) {
      $response->setStatusCode(201, 'Updated');

      $response->setJsonContent([
        'status' => 'OK',
        'data'   => $signature,
      ]);
    } else {
      $response->setStatusCode(409, 'Conflict');

      $errors = [];

      foreach ($status->getMessages() as $message) {
        $errors[] = $message->getMessage();
      }

      $response->setJsonContent([
        'status'   => 'ERROR',
        'data' => $errors,
      ]);
    }

    return $response;
  }
);

$app->delete(
  '/api/signatures/{id:[0-9]+}',
  function ($id) use ($app) {
    // Operation to delete a signature with id $id
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");
    
    $sqlsig = 'DELETE FROM Docspace\Signatures WHERE id = :id:';
    $status = $app->modelsManager->executeQuery($sqlsig, ['id' => $id]);

    if ($status->success() === true) {
      $response->setJsonContent([
        'status' => 'OK'
      ]);
    } else {
      // Change the HTTP status
      $response->setStatusCode(409, 'Conflict');

      $errors = [];

      foreach ($status->getMessages() as $message) {
          $errors[] = $message->getMessage();
      }

      $response->setJsonContent([
        'status'   => 'ERROR',
        'data' => $errors,
      ]);
    }

    return $response;
  }
);

$app->handle();
?>