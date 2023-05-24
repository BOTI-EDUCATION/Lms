<?php

namespace Models\FIN;

use \Models\Model;

use function PHPSTORM_META\map;

class Avoir extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'fnc_avoirs';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );


    protected static $fields = array(
        'Ref' => array(
            'type' => 'varchar',
        ),
        'Promotion' => array(
            'fk' => 'Promotion',
        ),
        'Encaissement' => array(
            'fk' => 'FIN\\Encaissement',
        ),
        'NumRecuEncaissement' => array(
            'type' => 'varchar',
        ),

        'Parent' => array(
            'fk' => 'Parentt',
        ),
        'ParentName' => array(
            'type' => 'varchar',
        ),
        'Inscriptions' => array(
            'type' => 'text',
        ),


        'Amount' => array(
            'type' => 'double',
        ),
        'ConsumedAmount' => array(
            'type' => 'double',
        ),
        'Consommations' => array(
            'type' => 'text',
        ),
        'Consumed' => array(
            'type' => 'boolean',
        ),

        'User' => array(
            'fk' => 'User',
        ),
        'Commentaire' => array(
            'type' => 'text',
        ),
        'Date' => array(
            'type' => 'date',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),
        'Validated' => array(
            'type' => 'text',
        ),
        'Refused' => array(
            'type' => 'text',
        ),
    );


    public function _beforeSave()
    {
        $history = $this->getArray('EditHistory') ?: array();
        $user = \Session::getInstance()->getCurUser();
        if ($user) {
            $history[] = array(
                'user' => \Session::getInstance()->getCurUser()->ID,
                'action' => $this->saved ? 'update' : 'add',
                'date' => date('Y-m-d H:i:s'),
            );
        }
        $this->setJson('EditHistory', $history);
    }

    public function saveHistory($action, $data = [])
    {
        $history = $this->getArray('EditHistory') ?: array();
        $user = \Session::getInstance()->getCurUser();


        if ($user) {

            $_history = array(
                'user' => \Session::getInstance()->getCurUser()->ID,
                'action' => $action,
                'date' => date('Y-m-d H:i:s'),
            );

            foreach ($data as $key => $value) {
                $_history[$key] = $value;
            }

            $history[] =  $_history;
        }


        $this->setJson('EditHistory', $history);
    }


    function consume($encaissement)
    {

        $this->ConsumedAmount = $this->ConsumedAmount + $encaissement->Montant;
        $consommations = $this->getArray('Consommations') ?: array();
        $consommations[] = $encaissement->ID;
        $this->setJson('Consommations', $consommations);

        if ($this->ConsumedAmount == $this->Amount) {
            $this->set('Consumed', true);
        }

        $encaissement->set('Avoir', $this)->save();
        $this->saveHistory('create_encaissement', ['encaissement' => $encaissement->ID]);
        $this->save();
    }


    function restore($encaissement)
    {
        $this->ConsumedAmount = $this->ConsumedAmount - $encaissement->Montant;
        $this->set('Consumed', false);
        $this->saveHistory('restore_amount', ['encaissement' => $encaissement->ID]);
        $this->save();
    }


    function restoreEncaissement()
    {
        foreach ($this->Encaissement->encaissementLignes() as  $ligne) {
            $ligne->set('Canceled', NULL);
            $ligne->save();
        }

        $this->Encaissement->set('Canceled', NULL);
        $this->Encaissement->save();
    }
}
