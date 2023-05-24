<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            color: #333;
            font-family: \'Segoe UI\', Arial, sans-serif;
            max-width: 600px;
            font-size: 13px
        }

        h1,
        h2,
        h3 {
            font-size: 14px;
            font-weight: bold;
            color: #010101;
        }

        h3 {
            font-size: 1.3em;
        }

        ul {
            list-style: none;
            padding-left: 15px;
        }

        a {
            color: #66C;
            text-decoration: none
        }

        a:hover {
            color: #339;
            text-decoration: underline
        }
    </style>
</head>

<body>
    Bonjour ,
    <br>
    Votre demande a été envoyée avec succès.
    <br>
    Nous vous remercions pour votre canfiance

    <ul>
        <li><b>Nom complet de l'élève</b> : <?php echo  $inscription->get('Nom') . " " . $inscription->get('Prenom') ?> </li>
        <li><b>Niveau </b> : <?php echo  $inscription->get('NiveauEnseignement')->get("Label") ?> </li>
        <li><b>№ tél</b> : <?php echo  $inscription->get('GSM') ?> </li>
        <li><b>Email</b> : <?php echo  $inscription->get('Email') ?> </li>
        <li><b>Adresse</b> : <?php echo  $inscription->get('Adresse') ?> </li>
    </ul>
    <p>Notre équipe pédagogique vous contactera dans les plus bréf délais.</p>
    <p>
        Pour toute information complémentaire n'hésitez pas à nous contacter
    </p>
    <?php if (Config::get('email')) { ?>
        Email : <a style="color: #000 !important;padding: 5px; font-weight: bold;" href="mailto:<?php echo Config::get('email')  ?>" class="d-block"><?php echo Config::get('email') ?></a>
    <?php } ?>
    <br>

    <?php
    if (Config::get('tel')) {
    ?>
        № tél : <b> <?php echo Config::get('tel'); ?> </b>

    <?php } ?>
</body>

</html>