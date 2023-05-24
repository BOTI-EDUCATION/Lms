<?php

namespace Models\Pick;

use \Models\Model;

class Vehicule extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'pick_vehicules';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Modele' => array(
            'fk' => 'Pick\\Modele',
        ),
        'Matricule' => array(
            'type' => 'varchar',
        ),
        'NumeroInterne' => array(
            'type' => 'varchar',
        ),
        'DateMiseCirculation' => array(
            'type' => 'date',
        ),
        'Km' => array(
            'type' => 'int',
        ),
        'HorsService' => array(
            'type' => 'text',
        ),
        'Remarques' => array(
            'type' => 'text',
        ),
        'Creation' => array(
            'type' => 'text',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),


    );

    static function documents(){

        $documents = array(
            'vignette' => 'Vignette',
            'assurance' => 'Assurance',
            'patente' => 'Patente',
            'visite_nationale' => 'Visite technique',
        );

        return $documents;
    }
    
}
