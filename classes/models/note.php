<?php

namespace Models;

class Note extends Model
{

  protected static $sqlQueries = array();

  protected static $table = 'notes';
  protected static $pk = array(
    'ID' => array(
      'auto' => true,
    ),
  );
  protected static $fields = array(
    'Evaluation' => array(
      'fk' => 'Evaluation',
    ),
    'Inscription' => array(
      'fk' => 'Inscription',
    ),
    'Valeur' => array(
      'type' => 'decimal',
    ),
    'Copie' => array(
      'type' => 'varchar',
    ),
    'Date' => array(
      'type' => 'datetime',
    ),
    'Appreciation' => array(
      'type' => 'text',
    ),
    'Absent' => array(
      'type' => 'boolean',
    ),
    'By' => array(
      'fk' => 'User',
    ),

  );


  public function getCopieLink()
  {
    return \GoogleStorage::getUrl(\Config::get('path-docs-examens') . '/'  . $this->get('Copie'));
  }



  public function getCopieName()
  {
    $name = $this->get('Evaluation')->get('Matiere')->get('Label') . ' ' . $this->get('Inscription')->get('Eleve')->get('User')->getNomComplet();
    $ext = pathinfo($this->get('Copie'), PATHINFO_EXTENSION);
    return \Tools::getAlias('copie-examen-' . $name) . '.' . $ext;
  }


  /* ALTER TABLE  `notes` ADD  `NoteRattrapage` decimal(4,2) NULL AFTER  `NoteAdmin`*/

  public static function hasNote($examen, $inscription)
  {
    $notes = self::getList(array('where' => array('Evaluation' => $examen, 'Inscription' => $inscription)));

    if ($notes) return $notes[0];

    return null;
  }


  public static function hasNoteMatiere($matiere, $type_exam, $semestre, $order, $classe, $inscription)
  {
    $notes = self::where(array('Inscription' => $inscription, 'Evaluation IN (select `ID` FROM `sco_evaluations` WHERE `Matiere` = ' . $matiere . ' AND `Semestre` = ' . $semestre . ' AND `TypeExam` = ' . $type_exam . ' AND `Classe` != ' . $classe . ')'))->get();

    return isset($notes[$order]) ? $notes[$order] : null;
  }
}
