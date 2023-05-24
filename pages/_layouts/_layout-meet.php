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
    <link rel="stylesheet" type="text/css" href="<?php echo URL::base() ?>assets/css/meet.<?php echo $dateFiles ?>.css">
    <!-- END Custom CSS-->
    <?php if (isset($app)) { ?>
      <script>
        app = <?php echo json_encode($app) ?>
      </script>
    <?php } ?>
  </head>
  <body>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    
		<?php include 'pages/'.$view.'.php' ?>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <script src="<?php echo URL::base() ?>assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    
    <script src="https://meet.jit.si/external_api.js"></script>
    <script src="<?php echo URL::base() ?>assets/js/scripts/meet.<?php echo $dateFiles ?>.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
  </body>
</html>