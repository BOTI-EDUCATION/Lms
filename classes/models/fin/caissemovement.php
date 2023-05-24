<?php

namespace Models\FIN;

use \Models\Model;
use Session;

class CaisseMovement extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'fnc_caisse_movements';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Ref' => array(
            'type' => 'varchar',
        ),
        'Encaissements' => array(
            'type' => 'text',
        ),
        'From' => array(
            'fk' => 'FIN\Caisse',
        ),
        'To' => array(
            'fk' => 'FIN\Caisse',
        ),
        'Montant' => array(
            'type' => 'doule',
        ),
        'Action' => array(
            'type' => 'varchar',
        ),
        'Agence' => array(
            'type' => 'varchar',
        ),
        'Comment' => array(
            'type' => 'text',
        ),
        'Files' => array(
            'type' => 'text',
        ),
        'Validation' => array(
            'type' => 'text',
        ),
        'User' => array(
            'fk' => 'User',
        ),
        'Date' => array(
            'type' => 'date',
        ),
    );

    
    static function  actions($key = null)
    {
        $items =  [
            'alimentation_caisse' => 'Alimentation de caisse',
            'changement_caisse' => 'Changement de caisse',
            'enclose_caisse' => 'Clôturer la caisse',
            'encaissement' => 'Encaissements',
            'depot_cheque' => 'Chèques déposés à la banque',
            'encaissement_cheque' => 'Chèque encaissé à la banque',
        ];

        if ($key) {
            return $items[$key] ?? null;
        }

        return  $items;
    }

    public function getFile()
    {
        return  \GoogleStorage::getUrl(\Config::get('path-encaissements') . 'caisse/' . $this->get('Files'));
    }

    public function userCanValidateMovement()
    {
        if (roleIs('admin') && !$this->To->User) {
            return true;
        }

        return $this->To->User && $this->To->User->getKey() == Session::user()->getKey();
    }
}
