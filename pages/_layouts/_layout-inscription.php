<?php

$schoolName = Config::has('nom_ecole') ? Config::get('nom_ecole') : 'Ecole';

$dateFiles = \Tools::getRandChars(6, '123456');
$promotion = Models\Promotion::promotion_overte_pour_inscriptions();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> <?php echo $schoolName ?> inscription en ligne <?php echo $promotion ? $promotion->get('Label') : '' ?> </title>

  <!-- Prevent the demo from appearing in search engines -->
  <meta name="robots" content="noindex">

  <!-- Perfect Scrollbar -->
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/vendor/perfect-scrollbar.css" rel="stylesheet">

  <!-- App CSS -->
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/app.css" rel="stylesheet">
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/app.rtl.css" rel="stylesheet">

  <!-- Material Design Icons -->
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-material-icons.css" rel="stylesheet">
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-material-icons.rtl.css" rel="stylesheet">

  <!-- Font Awesome FREE Icons -->
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-fontawesome-free.css" rel="stylesheet">
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-fontawesome-free.rtl.css" rel="stylesheet">

  <!-- ion Range Slider -->
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-ion-rangeslider.css" rel="stylesheet">
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/vendor-ion-rangeslider.rtl.css" rel="stylesheet">
  <link type="text/css" href="<?php echo URL::base() ?>assets/student/css/custom.<?php echo $dateFiles ?>.css" rel="stylesheet">
</head>

<body class="">
  <!-- ////////////////////////////////////////////////////////////////////////////-->

  <?php include 'pages/' . $view . '.php' ?>
  <!-- ////////////////////////////////////////////////////////////////////////////-->


  <!-- jQuery -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/jquery.min.js"></script>

  <!-- Bootstrap -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/popper.min.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/vendor/bootstrap.min.js"></script>

  <!-- Perfect Scrollbar -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/perfect-scrollbar.min.js"></script>

  <!-- DOM Factory -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/dom-factory.js"></script>

  <!-- MDK -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/material-design-kit.js"></script>

  <!-- Range Slider -->
  <script src="<?php echo URL::base() ?>assets/student/vendor/ion.rangeSlider.min.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/ion-rangeslider.js"></script>

  <!-- App -->
  <script src="<?php echo URL::base() ?>assets/student/js/toggle-check-all.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/check-selected-row.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/dropdown.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/sidebar-mini.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/app.js"></script>
  <script src="<?php echo URL::base() ?>assets/student/js/custom.js"></script>

</body>

</html>