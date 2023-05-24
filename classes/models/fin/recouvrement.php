<?php

namespace Models\FIN;

use \Models\Model;

use function PHPSTORM_META\map;

class Recouvrement extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'fnc_recouvrements';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );


    protected static $fields = array(
        'Action' => array(
            'type' => 'varchar',
        ),
        'Parent' => array(
            'fk' => 'Parentt',
        ),
        'Eleve' => array(
            'fk' => 'Eleve',
        ),
        'Inscription' => array(
            'fk' => 'Inscription',
        ),
        'ParentName' => array(
            'type' => 'varchar',
        ),
        'Scheduled' => array(
            'type' => 'text',
        ),
        'Done' => array(
            'type' => 'text',
        ),
        'Canceled' => array(
            'type' => 'text',
        ),
        'User' => array(
            'fk' => 'User',
        ),
        'DateAction' => array(
            'type' => 'date',
        ),
        'Date' => array(
            'type' => 'date',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),
    );



    public  static function actions($action =  null)
    {
        $items  = [
            'call' => [
                'label' => 'Appel téléphonique',
                'reponses' => [
                    'Numéro erroné',
                    'Réponse',
                    'Ne réponds pas',
                    'A promis de régulariser',
                    'Cas de conflit',
                    'Autres'
                ]
            ],
            'sms' => [
                'label' => 'SMS',
                'reponses' => [
                    'Envoyé',
                    'Non Envoyé'
                ]
            ],
            'notification_boti' => [
                'label' => 'Notification Mobile',
                'reponses' => [
                    'Envoyé',
                    'Non Envoyé'
                ]
            ]
        ];

        if ($action) {
            return $items[$action];
        }

        return $items;
    }
}
