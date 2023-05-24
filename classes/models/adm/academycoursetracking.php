<?php
namespace Models\ADM;


class AcademyCourseTracking extends \Models\Model {

	protected static $sqlQueries = array();

	protected static $table = 'adm_course_tracking';
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Course' => array(
			'type' => 'varchar',
		),
		'QuizScore' => array(
			'type' => 'int',
        ),
		'Done' => array(
			'type' => 'text',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'Progress' => array(
			'type' => 'int',
        ),
		'AccessLog' => array(
			'type' => 'text',
		),
		'Date' => array(
			'type' => 'date',
		),
	);

    public function logAccess(){

    }

	public static function getState($course,$user = null){

        if(!$user)
        $user = new \Models\User(1);
        // $user = \Session::getInstance()->getCurUser();


        $tracking_course = AcademyCourseTracking::getList(array(
           'where' => array(
               'Course' => $course,
               'User' => $user->get('ID')
           ) 
        ));

        if( !$tracking_course ){
            
            return [
                'icon' => null, 
                'label' => null, 
                'value' => null, 
                'color' => 'dark'
            ];

        }

        if( $tracking_course[0]->get('Validation') ){
            return [
                'icon' => 'mdi-check-circle', 
                'label' => 'Passé', 
                'value' => $tracking_course[0]->get('QuizScore'), 
                'color' => 'success'
            ];
        }

        // return [
        //     'icon' => 'mdi-clock-time-four', 
        //     'label' => 'En cours', 
        //     'value' => $tracking_course[0]->get('Progress'), 
        //     'color' => 'warning'
        // ];
        return [
            'icon' => 'mdi-check-circle', 
            'label' => 'Passé', 
            'value' => $tracking_course[0]->get('QuizScore'), 
            'color' => 'success'
        ];

    }

}
