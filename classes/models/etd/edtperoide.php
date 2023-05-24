<?php

namespace Models\Etd;
use \Models\Model;
class EdtPeroide extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'sco_edt_peroide';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        
        'Edt' => array(
            'fk' => 'Etd\\Edt',
        ),

        'From' => array(
            'type' => 'date',
        ),
        'To' => array(
            'type' => 'date',
        ),
    );
}
