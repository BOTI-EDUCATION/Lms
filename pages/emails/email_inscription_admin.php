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
    Une nouvelle demande d'inscription a été effectuée le <?php echo Tools::dateFormat($inscription->get('CreatedAt'), '%d %b %Y %H:%M')  ?> sur votre site web.
    <br>
    <ul>
        <li><b>Nom complet de l'élève</b> : <?php echo  $inscription->get('Nom') . " " . $inscription->get('Prenom') ?> </li>
        <li><b>Niveau </b> : <?php echo  $inscription->get('NiveauEnseignement')->get("Label") ?> </li>
        <li><b>№ tél</b> : <?php echo  $inscription->get('GSM') ?> </li>
        <li><b>Email</b> : <?php echo  $inscription->get('Email') ?> </li>
        <li><b>Adresse</b> : <?php echo  $inscription->get('Adresse') ?> </li>
    </ul>
    <p>Pour plus de détails,Veuillez vous rendre sur votre espace admin sur <?php echo  URL::absolute(URL::admin('inscriptions/request_inscriptions')) ?> </p>
</body>

</html>