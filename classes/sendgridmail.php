<?php
class SendGridMail
{

	public $mail = null;
	static $sendgrid = null;

	public  function __construct()
	{
		if (static::$sendgrid === null) {
			loadLib('sendgrid_v3');
			static::$sendgrid = new \SendGrid(Config::get('api-keys-sendgrid-mail'));
		}
	}

	public function send($mail)
	{
		return static::$sendgrid->send($mail);
	}

	public function mail()
	{
		return new \SendGrid\Mail\Mail();
	}
}



/* Example
$email
	// Template de l'email
	->setTemplateId('123456789-abcd-12ab-ab12-1234567890ab') // crée sur le Dashboard SendGrid
	// ->addFilter('bypass_list_management', 'enabled', 1) // Only for important messages !! like password resets
	// To
	->addSmtpapiTo('a.digourdi@gmail.com', 'Abderrahim Digourdi')
	->addSmtpapiTo('a.semoud@sysartech.com', 'Ahmed Semoud')
	// From
    ->setFrom('noreply@domain.com')
	->setFromName('domain.com')
	->setReplyTo('contact@comain.com')
	// Content, vide si une template est utilisé, le contenu sera remplacé par les variables ci-dessous
	->setSubject(' ')
	->setText(' ')
	->setHtml(' ')
	// Variables
	->addSubstitution("%firstname%", array('Abderrahim', 'Ahmed'))
	->addSubstitution("%lastname%", array('Digourdi', 'Semoud'))
	->addSubstitution("%fullname%", array('Abderrahim Digourdi', 'Ahmed Semoud'))
	// Tracking
	->addUniqueArg('ClientID', '116-273')
	// ->addCategory('SYS-Reservation')
    // ->addCategory('SYS-NewClient')
	// ->addCategory('SYS-Reservation-Rappel')
	;

Mail::send($email);
//*/
