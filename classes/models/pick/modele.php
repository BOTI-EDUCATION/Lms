<?php

namespace Models\Pick;

use \Models\Model;

class Modele extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'pick_modeles';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(

        'Marque' => array(
            'fk' => 'pick\\Marque',
        ),
        'Label' => array(
            'type' => 'varchar',
        ),
        'MarqueLabel' => array(
            'type' => 'varchar',
        ),
        'Image' => array(
            'type' => 'varchar',
        ),

    );

    public function getImage($full_link = false)
    {

        $icon = $this->get('Image');
        if (!$icon) {
            return null;
        }
        return ($full_link ? \URL::AbsoluteLink() : \URL::base('')) . \Config::get('path-images-picks-modele') . $icon;
    }
}
