<div class="content-wrapper">

    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Cours</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"> <?php echo $label ?>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">

        <div class="row">
            <?php foreach ($cours as $item) { ?>
                <div class="col-xl-4 col-md-6 col-sm-12 profile-card-2">
                    <div class="card" style="height: 328px;">
                        <div class="card-header mx-auto pb-0">
                            <div class="row m-0">
                                <div class="col-sm-12 text-center">
                                    <h4><?php echo html($item->get('Matiere') ? $item->get('Matiere')->get('Label') : '-') ?></h4>
                                </div>
                                <div class="col-sm-12 text-center">
                                    <p class=""><?php echo html($item->get('Remarque')) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body text-center mx-auto">
                                <div class="avatar avatar-xl">
                                    <img class="img-fluid" src="<?php echo $item->get('Enseignant') ? $item->get('Enseignant')->get('User')->getSmallImage() : '' ?>" alt="img placeholder">
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="uploads">
                                        <p class="font-weight-bold font-medium-2 mb-0"><?php echo html(Tools::dateFormat($item->get('Date'), '%d/%m/%Y')) ?></p>
                                        <span class="">Date</span>
                                    </div>
                                    <div class="followers">
                                        <p class="font-weight-bold font-medium-2 mb-0"><?php echo html($item->getLabelHeure()) ?></p>
                                        <span class="">Heure</span>
                                    </div>
                                </div>
                                <a href="<?php echo URL::admin('cours/meet/' . ($item->get('ID'))) ?>" class="btn gradient-light-primary btn-block mt-2 waves-effect waves-light">Accéder au cours</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>