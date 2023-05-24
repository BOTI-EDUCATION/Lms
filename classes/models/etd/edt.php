<?php

namespace Models\ETD;

use Models\Etd\EdtPeroide;
use \Models\Model;

class Edt extends Model
{

    protected static $sqlQueries = array();

    protected static $table = 'sco_edt';
    protected static $pk = array(
        'ID' => array(
            'auto' => true,
        ),
    );
    protected static $fields = array(

        'Label' => array(
            'type' => 'varchar',
        ),
        'Alias' => array(
            'type' => 'varchar',
        ),


        'Classe' => array(
            'fk' => 'Classe',
        ),
        'Group' => array(
            'fk' => 'Groupes',
        ),

        'Promotion' => array(
            'fk' => 'Promotion',
        ),
        'Creation' => array(
            'type' => 'datetime',
        ),
        'CreationBy' => array(
            'fk' => 'User',
        ),

        'Modification' => array(
            'type' => 'datetime',
        ),
        'ModificationBy' => array(
            'fk' => 'User',
        ),

        'Active' => array(
            'type' => 'boolean',
        ),


        'Valide' => array(
            'type' => 'datetime',
        ),
        'ValideBy' => array(
            'fk' => 'User',
        ),

        'Annule' => array(
            'type' => 'datetime',
        ),
        'AnnuleBy' => array(
            'fk' => 'User',
        ),
    );

    public static function getList($args = null, $query = null)
    {
        if (!is_array($args))
            $args = array();

        $args['where'][] = '`Active` = 1';
        return parent::getList($args, $query);
    }


    static function  workWeek()
    {
        $days = array(0, 1, 2, 3, 4, 5);
        $work_week = [];
        foreach ($days  as $key => $day) {
            $begin = new \DateTime();
            // $begin->modify('tomorrow')->modify('previous Sunday');
            $begin->modify('Sunday last week');
            $begin->modify('+' . $day . ' day');
            $work_week[$key] =   $begin->format('Y-m-d');
        }
        return $work_week;
    }


    static function  weekDates()
    {
        $days = array(0, 1, 2, 3, 4, 5, 6,);
        $days_dates = [];
        foreach ($days  as $key => $day) {
            $begin = new \DateTime();
            // $begin->modify('tomorrow')->modify('previous Sunday');
            $begin->modify('Sunday last week');
            $begin->modify('+' . $day . ' day');
            $days_dates[$key] =   $begin->format('Y-m-d');
        }
        return $days_dates;
    }

    function addPeroide($data)
    {
        $data['Edt'] = $this->get('ID');
        return $this->add(EdtPeroide::class, $data);
    }

    function getPeroides()
    {
        $list  =  EdtPeroide::getList(array('where' => array('Edt' => $this->get('ID'))));
        return $list;
    }

    function addSeance($data)
    {
        $data['Edt'] = $this->get('ID');
        return $this->add(Seance::class, $data);
    }

    function getSeances()
    {
        $list  =  Seance::all(array('where' => array('Edt' => $this->get('ID')), 'order' => array('Day' => true, 'From' => true)));
        return $list;
    }

    public function  delete()
    {
        $seances = Seance::all(array('where' => array('Edt' => $this->ID)));

        foreach ($seances as $seance) {
            $seance->delete();
        }

        $periodes = EdtPeroide::all(array('where' => array('Edt' => $this->ID)));
        foreach ($periodes as $p) {
            $p->delete();
        }

        parent::delete();
    }
}
