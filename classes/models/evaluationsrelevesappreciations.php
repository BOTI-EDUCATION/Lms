<?php

namespace Models;

class EvaluationsRelevesAppreciations extends Model
{
    protected static $sqlQueries = array();

    protected static $table = 'evaluations_releves_appreciations';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Promotion' => array(
            'fk' => 'Promotion',
        ),
        'Inscription' => array(
            'fk' => 'Inscription',
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

        'Moyenne' => array(
            'type' => 'init',
        ),
        'MoyenneEdithistory' => array(
            'type' => 'text',
        ),
        'Appreciation' => array(
            'type' => 'text',
        ),
        'Conseil' => array(
            'type' => 'text',
        ),
        'CustomFields' => array(
            'type' => 'varchar',
        ),
        'AppreciationEnseignant' => array(
            'type' => 'Enseignant',
        ),
        'CreatedAt' => array(
            'type' => 'datetime',
        )
    );

    public static function hasAppreciation($inscription, $unite, $classe, $peroide, $semestre)
    {
        $where = array(
            'Inscription' => $inscription,
            'Classe' => $classe,
            'Periode' => $peroide,
            'Semestre' => $semestre
        );

        if ($unite) {
            $where['Unite'] = $unite;
        } else {
            $where[] = 'Unite is NULL';
        }

        $notes = self::getList(array('where' => $where));

        if (count($notes)) return $notes[0];

        return null;
    }

    public static function CountClasseAppreciation($classe, $peroide, $semestre)
    {
        $where =  array(
            'Classe' => $classe,
            'Periode' => $peroide,
            'Semestre' => $semestre
        );

        $counts   = self::getCount(array('where' => $where));
        return $counts;
    }

    public static function CountUniteAppreciation($unite, $classe, $peroide, $semestre)
    {
        $where =  array(
            'Classe' => $classe,
            'Periode' => $peroide,
            'Semestre' => $semestre
        );

        if ($unite) {
            $where['Unite'] = $unite;
        } else {
            $where[] = 'Unite is NULL';
        }

        $counts = self::getCount(array('where' => $where));
        return $counts;
    }

    public static function customFields($action =  null)
    {
        $items  = [
            'tenue_cahcier_liason' =>  "Tenue du cahier de liaison",
            // 'assiduite' =>  "Assiduité",
            'discipline' =>  "Discipline",
        ];

        if ($action) {
            return $items[$action];
        }

        return $items;
    }
}
