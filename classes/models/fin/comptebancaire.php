<?php

namespace Models\FIN;

use \Models\Model;

class CompteBancaire extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'fnc_comptes_bancaires';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Banque' => array(
            'fk' => 'FIN\\Banque',
        ),
        'Etablissement' => array(
            'fk' => 'Etablissement',
        ),
        'Agence' => array(
            'type' => 'varchar',
        ),
        'Rib' => array(
            'type' => 'varchar',
        ),
        'Enabled' => array(
            'type' => 'boolean',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),
    );
}
