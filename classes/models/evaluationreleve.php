<?php

namespace Models;

class EvaluationReleve extends Model
{
    protected static $sqlQueries = array();

    protected static $table = 'evaluations_releves';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Promotion' => array(
            'fk' => 'Promotion',
        ),
        'Classe' => array(
            'fk' => 'Classe',
        ),
        'Unite' => array(
            'fk' => 'Unite',
        ),
        'Matiere' => array(
            'fk' => 'Matiere',
        ),
        'Periode' => array(
            'type' => 'int',
        ),
        'Semestre' => array(
            'type' => 'int',
        ),
        'AppreciationActivationSaisie' => array(
            'type' => 'text',
        ),
        'AppreciationValidationSaisie' => array(
            'type' => 'text',
        ),
        'CreatedAt' => array(
            'type' => 'datetime',
        )
    );

    public static function hasEvaluationReleve($classe, $unite, $peroide, $semestre)
    {
        $apprs = self::getList(array('where' => array(
            'Classe' => $classe,
            'Unite' => $unite,
            'Periode' => $peroide,
            'Semestre' => $semestre
        )));

        if (count($apprs)) return $apprs[0];

        return null;
    }

    public static function isActiveted($classe, $unite, $peroide, $semestre)
    {
        $has_eva =  self::hasEvaluationReleve($classe, $unite, $peroide, $semestre);
        return $has_eva && $has_eva->AppreciationActivationSaisie;
    }


    public static function isValidated($classe, $unite, $peroide, $semestre)
    {
        $has_eva =  self::hasEvaluationReleve($classe, $unite, $peroide, $semestre);
        return $has_eva && $has_eva->AppreciationValidationSaisie;
    }
    
}
