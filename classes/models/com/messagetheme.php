<?php

namespace Models\COM;

class MessageTheme extends \Models\Model
{

    protected static $sqlQueries = array();

    protected static $table = 'com_message_themes';

    public static $icons_folder = 'messages_themes';

    protected static $pk = array(
        // 'ID' => array(
        //     'auto' => true,
        // ),
        'Alias' => array(
            'required' => true,
        ),
    );

    protected static $fields = array(
        'Label' => array(
            'type' => 'varchar',
            'required' => true,
        ),
        'ShortDescription' => array(
            'type' => 'varchar',
        ),
        'Description' => array(
            'type' => 'text',
        ),
        'Icon' => array(
            'type' => 'varchar',
        ),
        'Ordre' => array(
            'type' => 'int',
        ),
        'Enabled' => array(
            'type' => 'boolean',
        ),
        'EditHistory' => array(
            'type' => 'varchar',
        ),
    );

    public function getImage()
    {
        if (!$this->get('Icon'))
            return null;
        return \Request::getBase() . static::iconsFolder() . $this->get('Icon');
    }

    public static function getByAlias($alias)
    {
        $idPost = \DB::scallar('SELECT `ID` FROM ' . static::wrapField(static::$table) . ' WHERE `Alias`=?', array($alias));

        if (!$idPost)
            return null;

        return new self($idPost);
    }

    public static function iconsFolder()
    {
        return \Config::get('path-repository-icons') . '/messages_themes/';
    }
}
