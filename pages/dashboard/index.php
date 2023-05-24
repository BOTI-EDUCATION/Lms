<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="icon" href="<?php echo assets('spa/dashboard/boti_icon.ico') ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Un espace de synthèse et d'aide à la décision pour votre école">
    <meta name="theme-color" content="#000000" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="apple-touch-icon" href="<?php echo assets('logoBoti.png') ?>" />
    <link rel="manifest" href="<?php echo assets('spa/dashboard/asset-manifest.json') ?>" />
    <title>Boti School Dashboard</title>
    <script defer="defer" src="<?php echo assets('spa/dashboard/static/js/main.js?v=' . time()) ?>"></script>
    <link href="<?php echo assets('spa/dashboard/static/css/main.css?v=' . time()) ?>" rel="stylesheet">
    <meta name="basepath" content="<?php echo URL::base() ?>/dashboard" />
    <meta name="baseurl" content="<?php echo URL::absolute(URL::base()) ?>" />
</head>

<body>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root"></div>
</body>

</html>