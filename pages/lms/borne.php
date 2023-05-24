<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LMS</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= assets('spa/lms/borne/css/video-js.css') ?>" rel="stylesheet">
    <!-- If you'd like to support IE8 -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <!--end::Fonts-->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,700,800,900&amp;subset=arabic" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="<?= assets('spa/lms/borne/css/style.css?v=63545737') ?>">
    <link rel="stylesheet" href="<?= assets('spa/lms/borne/css/calcule-mental.css') ?>">
    <meta name="base_api" content="<?php echo URL::absolute() . URL::base()  ?>" />
    <meta name="base_api" content="<?php echo URL::absolute() . URL::base()  ?>" />
    <meta name="base_path" content="<?php echo URL::base() ?>" />


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>
    <div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>


    <div id="borne"></div>


    <footer>

    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <script src="<?= assets('spa/lms/borne/js/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= assets('spa/lms/borne/js/jquery.easypiechart.min.js') ?>"></script>

    <script src="https://vjs.zencdn.net/5.19.2/video.js"></script>
    <script src="<?= assets('spa/lms/borne/js/calcule-mental.js') ?>"></script>
    <script src="<?= assets('spa/lms/borne/js/main.js?v=63545737') ?>"></script>

    <script src="<?= assets('spa/lms/borne/js/phaser.js') ?>">
    </script>
    <script src="<?= assets('spa/lms/borne/js/drag-event-parameters.js') ?>">
    </script>
    <script src="<?= assets('spa/lms/js/borne.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.carousel').carousel({
            interval: false,
        });
    </script>

</body>

</html>