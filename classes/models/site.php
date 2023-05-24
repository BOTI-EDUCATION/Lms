<?php

namespace Models;

class Site extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'ins_sites';
    protected static $pk = array(
        'Alias' => array(
            'auto' => false,
        ),
    );
    protected static $fields = array(
        'Label' => array(
            'type' => 'varchar',
        ),
        'Etablissement' => array(
            'fk' => 'Partenaire',
        ),
        'Description' => array(
            'type' => 'text',
        ),
        'Image' => array(
            'type' => 'varchar',
        ),
        'Ordre' => array(
            'type' => 'int',
        ),
        'Enabled' => array(
            'type' => 'boolean',
        ),
        'Date' => array(
            'type' => 'datetime',
        ),
        'EditHistory' => array(
            'type' => 'text',
        ),

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


    public function getImage()
    {
        if (!$this->get('Image'))
            return null;
        return \Request::getBase() . \Config::get('path-images') . $this->get('Image');
    }

    public static function getByAlias($alias)
    {
        $idPost = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Alias`=?', array($alias));

        if (!$idPost)
            return null;

        return new self($idPost);
    }


    public function countClasses()
    {


        return   \Models\Classe::getCount(array('where' => array(
            'Site' => $this->get('Alias')
        )));
    }
    public function countInscriptions()
    {
        return   \Models\Inscription::getCount(array('where' => array(
            'Site' => $this->get('Alias')
        )));
    }
}
