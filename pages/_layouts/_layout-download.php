<?php

$schoolName = Config::has('nom_ecole')?Config::get('nom_ecole'):'Ecole';

$dateFiles = \Tools::getRandChars(6, '123456');


?>
<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Learning Management System by SMART UX">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>Application de gestion d'écoles avec un Focus sur la communication</title>
    <link rel="apple-touch-icon" href="<?php echo URL::base() ?>assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo URL::base() ?>assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/fonts/feather/style.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/fonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/vendors/css/extensions/pace.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/vendors/css/forms/icheck/custom.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/app.<?php echo $dateFiles ?>.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/colors.css">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/core/menu/menu-types/vertical-overlay-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/pages/login-register.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/style.<?php echo $dateFiles ?>.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/custom.<?php echo $dateFiles ?>.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/iofrm-style.<?php echo $dateFiles ?>.css">
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/iofrm-theme8.<?php echo $dateFiles ?>.css">
    <!-- END Custom CSS-->
  </head>
  <body>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    
		<?php include 'pages/'.$view.'.php' ?>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <script src="<?php echo URL::base() ?>assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="<?php echo URL::base() ?>assets/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
    <script src="<?php echo URL::base() ?>assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN STACK JS-->
    <script src="<?php echo URL::base() ?>assets/js/core/app-menu.js" type="text/javascript"></script>
    <script src="<?php echo URL::base() ?>assets/js/core/app.js" type="text/javascript"></script>
    <!-- END STACK JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="<?php echo URL::base() ?>assets/js/scripts/forms/form-login-register.20190116.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
  </body>
</html>