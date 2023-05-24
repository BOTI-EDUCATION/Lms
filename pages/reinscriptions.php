<section class="new_inscription" style="background: #f0f0f0;display: flex;justify-content: center;align-items: center;">

    <div class="" style="width: 90%;border-radius: 19px !important;border-collapse: separate !important; overflow: hidden;perspective: 1px;">
        <div class="row no-gutters">
            <?php
            $logo = (Config::get('logo_white') ? URL::base('assets/images/schools/white/' . Config::get('logo_white')) : URL::base('assets/images/logo.103983.png'));
            $mainColor = Config::get('main_color') ? Config::get('main_color') : '004c8b';
            ?>
            <div class="col-md-12" style="background: url(<?php echo URL::base(Config::get('path-images')) ?>ins_back.png);">
                <div style="background:#<?php echo $mainColor ?>;color: #FFF;padding: 30px 60px 20px;text-align:center;opacity: 72%;display: flex;justify-content: center;align-items: center;height: 100%;">
                    <div>
                        <img src="<?php echo $logo ?>" class="logo-navbar" alt="<?php echo Config::get('nom_ecole') ?>" style="width: 200px;">
                        <br>
                        <br>
                        <div>
                            <h3 style="padding: 17px;"> Bonjour <?php echo  $parent->User ? $parent->User->getNomComplet() : '' ?> ! </h3>
                            <p> Veuillez valider la réinscription de votre(vos) enfant(s)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="background: #fff;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="inscription-form-container">
                            <?php
                            if (!$parent) {
                            ?>
                                <div class="inscription-form-container">
                                    <div class="inscription_success text-center">
                                        <img src="<?php echo URL::base() ?>assets/student/img/success.svg" alt="">
                                        <h6>Compte parent invalide. Merci de contacter l'administration</h6>
                                    </div>
                                </div>

                            <?php } elseif (isset($_SESSION['success_inscription'])) {
                                unset($_SESSION['success_inscription']);
                            ?>
                                <div class="inscription-form-container">
                                    <div class="inscription_success text-center">
                                        <img src="<?php echo URL::base() ?>assets/student/img/success.svg" alt="">
                                        <h6>La demande de réinscription de vos enfants <br> est bien envoyée. </h6>
                                        <p>Nous vous remercions de votre confiance</p>

                                    </div>
                                </div>

                            <?php } else { ?>
                                <form method="post">
                                    <div class="lds-ripple-container">
                                        <div class="lds-ripple">
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cf_token" value="<?php echo cf_token(); ?>" />
                                    <div class="text-center">
                                        <h1 style="color:#2532a1"> Réinscription en ligne <?php echo date('Y') ?>/<?php echo date('Y') + 1 ?> </h1>
                                    </div>

                                    <?php foreach ($parent->eleves() as $eleve) {
                                        $inscription = $eleve->getInscription();
                                        $request_inscription = Models\RequestInscription::where(['ReInscriptionEleve' => $eleve->ID, 'ReInscriptionPromotion' => $next_promotion->ID])->first();

                                    ?>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="card">
                                                    <?php if (!$eleve->getInscription($next_promotion) && !$request_inscription) {  ?>
                                                        <input name="eleves[]" class="radio" type="checkbox" value="<?php echo $eleve->ID; ?>">
                                                    <?php } ?>
                                                    <span class="plan-details">
                                                        <span class="plan-type"> <?php echo $eleve->User->getNomComplet() ?></span>
                                                        <b style="font-size:12px">
                                                            <?php echo $inscription->Niveau->Label ?> / <?php echo $inscription->Promotion->Label ?> --> <?php echo $inscription->Niveau->NextNiveau()->Label ?> / <?php echo date('Y') ?>/<?php echo date('Y') + 1 ?>
                                                        </b>
                                                        <?php if ($eleve->getInscription($next_promotion)) {  ?>
                                                            <b style="color: green;font-size:12px"> Déjà inscrit pour la promotion <?php echo date('Y') ?>/<?php echo date('Y') + 1 ?> </b>
                                                        <?php } ?>
                                                    </span>

                                                </label>
                                            </div>
                                        </div>

                                    <?php  } ?>
                                    <p style="font-size: 14px;color: #eb7575;font-weight: bold;">
                                        La demande de la réinscription n’est considérée 100 % validée qu’une fois le règlement des frais est réalisé
                                    </p>
                                    <button class="btn btn-main btn-block text-center text-uppercase">Envoyer la demande</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>


<style>
    .btn-main {
        background: #<?php echo $mainColor ?> !important;
    }


    .inscription_success p {
        font-size: 20px !important;
    }

    .inscription_success h6 {
        font-size: 29px !important;
    }


    :root {
        --card-line-height: 1.2em;
        --card-padding: 1em;
        --card-radius: 0.5em;
        --color-green: #<?php echo $mainColor ?>;
        --color-gray: #e2ebf6;
        --color-dark-gray: #c4d1e1;
        --radio-border-width: 2px;
        --radio-size: 1.5em;
    }


    .card {
        background-color: #fff;
        border-radius: var(--card-radius);
        position: relative;
    }

    .card:hover {
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.15);
    }

    .radio {
        accent-color: var(--color-green);
        font-size: inherit;
        margin: 0;
        position: absolute;
        right: calc(var(--card-padding) + var(--radio-border-width));
        top: calc(var(--card-padding) + var(--radio-border-width));
    }

    .plan-details {
        border: var(--radio-border-width) solid var(--color-gray);
        border-radius: var(--card-radius);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        padding: var(--card-padding);
        transition: border-color 0.2s ease-out;
    }

    .plan-details .plan-type {
        color: var(--color-green);
        font-weight: bold;
    }

    .card:hover .plan-details {
        border-color: var(--color-green);
    }

    .radio:checked~.plan-details {
        border-color: var(--color-green);
    }

    .inscription-form-container select,
    .inscription-form-container input {
        height: auto !important;
        font-family: 'Inter', sans-serif;
        font-weight: 400;
    }
</style>