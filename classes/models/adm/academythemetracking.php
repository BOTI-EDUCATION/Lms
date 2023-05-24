<?php
namespace Models\ADM;

class AcademyThemeTracking extends \Models\Model {

	protected static $sqlQueries = array();

	protected static $table = 'adm_theme_tracking';
    
	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);

	protected static $fields = array(
		'User' => array(
			'fk' => 'User',
		),
		'Theme' => array(
			'type' => 'varchar',
		),
		'Validation' => array(
			'type' => 'text',
		),
		'Score' => array(
			'type' => 'int',
        ),
        'DateLastAction' => array(
            'type' => 'date',
        ),
		'Date' => array(
			'type' => 'date',
		)
	);

    public static function getState($theme,$user = null){

        if(!$user)
        $user = new \Models\User(1);
        // $user = \Session::getInstance()->getCurUser();

        $tracking_theme = AcademyThemeTracking::getList(array(
           'where' => array(
               'Theme' => $theme,
               'User' => $user->get('ID')
           ) 
        ));

        if( !$tracking_theme ){
            
            return [
                'icon' => null,
                'label' => null, 
                'value' => null, 
                'color' => 'dark'
            ];

        }

        if( $tracking_theme[0]->get('Validation') ){
            return [
                'icon' => 'mdi-check-circle',
                'label' => 'Passé', 
                'value' => $tracking_theme[0]->get('Score'), 
                'color' => 'success'
            ];
        }

        return [
            'icon' => 'mdi-clock-time-four',
            'label' => 'En cours', 
            'value' => null, 
            'color' => 'warning'
        ];

    }
	
	
}
