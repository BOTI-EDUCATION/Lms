<?php

namespace Models;

class EvaluationsPv extends Model
{

  protected static $sqlQueries = array();

  protected static $table = 'sco_evaluations_pvs';

  protected static $pk = array(
    'ID' => array(
      'auto' => true,
    ),
  );

  protected static $fields = array(
    'Inscription' => array(
      'fk' => 'Inscription',
    ),
    'Classe' => array(
      'fk' => 'Classe',
    ),
    'Unite' => array(
      'fk' => 'Unite',
    ),
    'Semestre' => array(
      'type' => 'int',
    ),
    'Moyenne' => array(
      'type' => 'decimal',
    ),
    'MoyenneMatieres' => array(
      'text' => 'decimal',
    ),
    'Date' => array(
      'type' => 'datetime',
    ),
    'EditHistory' => array(
      'type' => 'text',
    ),
  );
}
