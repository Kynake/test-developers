<?php
namespace Docspace;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Behavior\Timestampable;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;


class Signatures extends Model {
  public $id;
  public $name;
  public $issuer;
  public $timestamp;
  public $ordering;
  public $id_document;

  public function initialize() {
    $this->belongsTo('id_document', 'Documents', 'id');

    $this->addBehavior(
      new Timestampable([
        'beforeCreate' => [
          'field'  => 'timestamp',
          'format' => 'Y-m-d',
        ]
      ])
    );
  }

  /**
   *  Validation
   */
  public function validation() {
    $validator = new Validation();

    // Document name must be unique
    $validator->add(
      'name',
      new Uniqueness([
        'field'   => 'name',
        'message' => 'The document name must be unique',
      ])
    );

    // Check if any messages have been produced
    if ($this->validationHasFailed() === true) {
      return false;
    }
  }
}
?>