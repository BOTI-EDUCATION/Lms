<?php

namespace Models\Pick;

use \Models\Model;

class Chauffeur extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'pick_chauffeurs';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(

        'Collaborateur' => array(
            'fk' => 'RH\\Collaborateur',
        ),
        'NumeroPermis' => array(
            'type' => 'varchar',
        ),
        'DateValiditePermis' => array(
            'type' => 'date',
        ),
        'VehicilesAffectes' => array(
            'type' => 'text',
        ),
        'VehiculeAffectationHistory' => array(
            'type' => 'text',
        ),
        'HorsService' => array(
            'type' => 'text',
        ),
    );
}
