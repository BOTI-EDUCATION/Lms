<?php

namespace Models;

class PostTag extends Model
{

	protected static $sqlQueries = array();

	protected static $table = 'com_posttags';

	protected static $pk = array(
		'ID' => array(
			'auto' => true,
		),
	);
	protected static $fields = array(
		'Post' => array(
			'fk' => 'Post',
		),
		'Photo' => array(
			'type' => 'varchar',
		),
		'Video' => array(
			'type' => 'varchar',
		),
		'Inscriptions' => array(
			'type' => 'varchar',
		),
	);


	public function getImage()
	{

		if ($this->get('Photo') && file_exists(_basepath . \Config::get('path-images-posts') . $this->get('Photo'))) {
			return \URL::base() . \Config::get('path-images-posts') . $this->get('Photo');
		}elseif($this->get('Photo')){
			return 'https://storage.googleapis.com/boti_bucket/'. \Config::get('path-images-posts') .$this->get('Photo');
		}

		return $this->get('Video')?\URL::base('assets/images/blank-video.png'):\URL::base('assets/images/add-image.png');
	}


	public function getVideo()
	{
		if(!$this->get('Video'))
		return null;

		return 'https://storage.googleapis.com/boti_bucket/'. \Config::get('path-images-posts') .$this->get('Video');
		

	}

	public function getInscriptions()
	{
		$items = $this->get('Inscriptions') ? Inscription::getList(array('where' => array('ID IN (' . implode(',', $this->getInscriptionsIds()) . ')'))) : array();

		return $items;
	}

	public function getInscriptionsIds()
	{
		return $this->get('Inscriptions') ? explode(',', str_replace('"', '', $this->get('Inscriptions'))) : array();
	}
}
