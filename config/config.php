<?php

return array(
	// * srv-sysartech
	'dbserver' => 'localhost',
	'db' => 'boti_2',
	'dbuser' => 'botischool',
	'dbpass' => 'botischool2020',

	//*/

	'api' => 'botiapi',
	'partapi' => 'partapi',
	'csmapi' => 'csmapi',
	'cron' => 'boticron',
	'admin' => 'admin',
	'extranet' => '',


	'display-errors' => true,

	// App settings
	'videoAutoPlay' => false,  // Autoplay videos

	// Emails
	// 'smtpHost' => 'SSL0.OVH.NET',
	// 'smtpPort' => 587,
	// 'smtpUsername' => 'noreply@test.ma',
	// 'smtpPassword' => 'nvi5okkc7#OX',

	'magic_boti_password' => 'magic_boti_password',
	'smtpHost' => 'smtp.gmail.com',
	'smtpPort' => 465,
	'smtpUsername' => 'dev@boti.education',
	'smtpPassword' => 'hlzieuaghicmgynv',
	'fromEmail' => array('email' => 'noreply@boti.education', 'name' => 'Boti Education'),
	'adminEmail' => array(
		array('email' => 'boti@education', 'name' => 'Boti Education'),
	),
	'format-date' => '%d %b %Y',
	'format-date-recrut' => '%d <span>%b<span>',
	'format-dateinput' => '%Y-%m-%d',
	'format-time' => '%Hh%M',
	'format-timeinput' => '%H:%M',
	'format-datetime' => '%d %b %Y, %Hh%M',
	'format-datetimeinput' => '%Y-%m-%d %H:%M',
	'format-datetimeline' => '%d %b %Y %H:%M',

	'path-uploads' => 'assets/schools/' . _school_alias  . '/files/',
	'path-images' => 'assets/schools/' . _school_alias  . '/img/',
	'path-images-services' => 'assets/img/services/',
	'path-images-questions' => 'assets/img/questions/',
	'path-images-lots' => 'assets/schools' . _school_alias . '/img/lots/',
	'path-images-users' => 'assets/lms/users/',
	'path-images-rubriques' => 'assets/schools/' . _school_alias . '/img/rubriques/',
	'path-images-indicateurs' => 'assets/schools/' . _school_alias . '/img/indicateurs/',
	'path-images-cantine' => 'assets/schools/' . _school_alias . '/img/cantine/',
	'path-images-posts' => 'assets/schools/' . _school_alias . '/img/posts/',
	'path-images-avocatarticles' => 'assets/img/avocatarticles/',
	'path-images-postcategories' => 'assets/img/postcategories/',
	'path-images-pubs' => 'assets/schools/' . _school_alias . '/img/pubs/',
	'path-images-partenaires' => 'assets/schools/' . _school_alias . '/img/partenaires/',
	'path-images-events' => 'assets/schools/' . _school_alias . '/img/events/',
	'path-images-rubriques' => 'assets/schools/' . _school_alias . '/img/rubriques/',
	'path-docs-posts' => 'assets/schools/' . _school_alias . '/docs/posts/',
	'path-docs-absences' => 'assets/schools/' . _school_alias . '/docs/absences/',
	'path-images-ressources' => 'assets/schools/' . _school_alias . '/img/ressources/',
	'path-images-picks-marque' => 'assets/picks/marque/',
	'path-images-picks-modele' => 'assets/picks/modele/',
	'path-images-rh-files' => 'assets/schools/' . _school_alias . '/img/rh/files/',
	'path-journalseance-files' => 'assets/schools/' . _school_alias . '/journalseance/files/',
	'path-encaissements' => 'assets/schools/' . _school_alias . '/docs/encaissements/',
	'path-mgmt-evaluation-type' => 'assets/mgmt/evaluationtype/',
	'path-mgmt-docs-absences' => 'assets/mgmt/' . _school_alias . '/docs/absences/',
	'path-reporting-icons' => 'assets/icons/reporting/',
	'path-actions-icons' => 'assets/icons/actions/',
	'path-docs-examens' => 'assets/schools/' . _school_alias . '/docs/examens/',
	'path-docs-cours' => 'assets/schools/' . _school_alias . '/docs/cours/',
	'path-docs-taches' => 'assets/schools/' . _school_alias . '/docs/taches/',
	'path-docs-consultationecrites' => 'assets/schools/' . _school_alias . '/docs/consultationecrites/',
	'path-files-img' => 'uploads/schools/' . _school_alias . '/img/',
	'path-matieres' => 'assets/matieres/',
	'path-suivi-eleve' => 'assets/schools/' . _school_alias . '/suivi_eleves/',
	'path-documents-eleve' => 'assets/schools/' . _school_alias . '/documents/',
	'path-crm-docs' => 'assets/schools/' . _school_alias . '/crm/',
	// 'path-lms-files' => 'assets/schools/' . _school_alias . '/lms/',
	'path-lms-files' => 'assets/schools/lms/',
	'path-dashboard-files' => 'assets/schools/' . _school_alias . '/dashboard/',
	'path-type-suivi' => 'assets/type-suivi/',
	'path-type-abs' => 'assets/type-abs/',
	'path-depenses-types' => 'assets/depenses-types/',
	'path-repository-icons' => 'assets/icons/repository',
	'path-log' => 'assets/docs/',
	'upload-file-max-size' => 25,
	'upload-file-exts' => array('jpg', 'jpeg', 'gif', 'png', 'doc', 'docx', 'pdf', 'txt', 'ppt', 'pptx'),
	'upload-file-image-max-size' => 25,
	'upload-file-image-exts' => array('jpg', 'jpeg', 'gif', 'png'),



	"sms-apiKey" => "f4f14dddf735d821441b5704b4e89a69",
	"sms-sender" => "BOTI",
	"sms-api-key" => "76ced3b8",
	"sms-api-secret" => "01ffc9508b234774",


	"sms-default-login" => "smartux",
	"sms-default-password" => "sma67jux&oadc=BOTI",

	'api-keys-sendgrid-mail' => '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',
	'web-firebase-server-api-access-key' => 'AAAABPalxQ8:APA91bHTe80zEEKbTDAtA9UjYLoF3gXGF48B5Q-X_dPx_v797rEOOIVVfrLwSY7WyetwD0AWA-aFt1H3Iz84Cy1rICHCjOibvbUuI0blr0aG14f05AKPaeHP0Qt0YjxSLpV5VOSMVMLm',

	// Attention de ne jamais modifier Les hash
	'hashchars' => '0123456789abcdefghijklmnopqrstuvwxyz',
	'hashsecretkey' => 'JURIS',
	'hashlength' => 8,

);
