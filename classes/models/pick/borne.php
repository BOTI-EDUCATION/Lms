<?php

namespace Models\Pick;

use \Models\Model;

class Borne extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'pick_bornes';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(
        'Label' => array(
            'type' => 'varchar',
        ),
        'Code' => array(
            'type' => 'varchar',
        ),
        'Classes' => array(
            'type' => 'varchar',
        ),
        'CreatedAt' => array(
            'type' => 'varchar',
        ),
        'DeletedAt' => array(
            'type' => 'varchar',
        ),
        'EditHistory' => array(
            'type' => 'varchar',
        ),
    );



    public function beforeSave()
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


    public static function getByCode($code)
    {
        $id = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Code`=?', array($code));
        if (!$id)
            return null;
        return new self($id);
    }

    public function classes()
    {

        return \Models\Classe::query()->whereIn('ID', $this->getArray('Classes'))->get();
    }
}
