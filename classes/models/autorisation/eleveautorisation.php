<?php

namespace Models\Autorisation;

use Models\Model;

class EleveAutorisation extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'gen_eleve_autorisations';

    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Autorisation' => array(
            'fk' => 'Autorisation\\TypeAutorisation',
        ),
        'Promotion' => array(
            'fk' => 'Promotion',
        ),
        'Eleve' => array(
            'fk' => 'Eleve',
        ),
        'Inscription' => array(
            'fk' => 'Inscription',
        ),
        'ByWhom' => array(
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
        )
    );

    public function beforeSave()
    {
        $history = $this->getArray('EditHistory') ?: array();
        $history[] = array(
            'user' => \Session::getInstance()->getCurUser()->ID,
            'action' => $this->saved ? 'update' : 'add',
            'date' => date('Y-m-d H:i:s'),
        );
        $this->setJson('EditHistory', $history);
    }


    public static function hasAutorisation($inscription, $type)
    {

        return \Models\Autorisation\EleveAutorisation::where(['Inscription' => $inscription->getKey(), 'Autorisation' => $type->getKey()])->first();
    }
}
