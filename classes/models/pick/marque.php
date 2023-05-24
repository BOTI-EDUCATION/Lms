<?php

namespace Models\Pick;

use \Models\Model;

class Marque extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'pick_marques';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(

        'Label' => array(
            'type' => 'varchar',
        ),
        'Logo' => array(
            'type' => 'varchar',
        ),
    );
    public function getImage($full_link = false)
    {

        $icon = $this->get('Logo');
        if (!$icon) {
            return null;
        }
        return ($full_link ? \URL::AbsoluteLink() : \URL::base('')) . \Config::get('path-images-picks-marque') . $icon;
    }
}
