<section class="new_inscription" style="background: #f0f0f0;display: flex;justify-content: center;align-items: center;">

	<div class="" style="width: 90%;border-radius: 19px !important;border-collapse: separate !important; overflow: hidden;perspective: 1px;">
		<div class="row no-gutters">
			<div class="col-md-6 order-2 order-md-1" style="background: #fff;">
				<div class="row">
					<div class="col-md-12">
						<div class="inscription-form-container">
							<?php
							if (isset($_SESSION['success_inscription'])) {
								unset($_SESSION['success_inscription']);
							?>
								<div class="inscription-form-container">
									<div class="inscription_success text-center">
										<img src="<?php echo URL::base() ?>assets/student/img/success.svg" alt="">
										<h6>Demande envoyée <br> avec succès</h6>
										<p>Votre demande a été envoyée avec succès, <br> nous revenons vers vous le plus <br> rapidement possible.</p>

										<?php if (isset($_SESSION['http_referer_inscription'])) {

										?>
											<a href="<?php echo $_SESSION['http_referer_inscription']; ?>"> <i class="fad fa-bullseye-pointer"></i> Retour vers le site</a>

										<?php }
										unset($_SESSION['http_referer_inscription']);
										?>

									</div>
								</div>
								<?php if (false) {

								?>
									<script>
										(function() {
											setTimeout(
												function() {
													location.replace("<?php echo $_SESSION['http_referer_inscription'];  ?>");
												}, 1000)
										})();
									</script>
								<?php
									unset($_SESSION['http_referer_inscription']);
								}

								?>


							<?php } else { ?>
								<form action="<?php echo URL::base('/request_inscriptions/add') ?>" method="post">
									<div class="lds-ripple-container">
										<div class="lds-ripple">
											<div></div>
											<div></div>
										</div>
									</div>
									<input type="hidden" name="cf_token" value="<?php echo cf_token(); ?>" />
									<div class="text-center">
										<h1 style="color:#2532a1"> Inscription en ligne <?php echo date('Y') ?>/<?php echo date('Y') + 1 ?> </h1>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<!-- <label for="">Nom</label> -->
												<input style="font-weight: normal !important;" type="text" id="nom" class="form-control main-input" name="nom" placeholder="Nom de l'élève *" required />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<!-- <label for="">Prenom</label> -->
												<input style="font-weight: normal !important;" type="text" id="prenom" class="form-control main-input" name="prenom" placeholder="Prénom de l'élève *" required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="">Niveau d’enseinement</label> -->
												<select style="font-weight: normal !important;" name="niveau" class="form-control main-input select2" placeholder="Niveau d’enseignement *" required>
													<option value="" selected style="font-weight: normal !important;"> Niveau d’enseignement * </option>
													<?php foreach (Models\Niveau::getList(array('where' => array('Enabled' => true), 'order' => array('Ordre' => true))) as $key => $niveau) { ?>
														<option value="<?php echo  $niveau->get('ID') ?>"> <?php echo $niveau->get('Label') ?> </option>
													<?php }  ?>
												</select>
												<span style="float:right;float: right;font-size: 11px;color: #b1b1b1;"> de l’année scolaire <?php echo date('Y') ?>/<?php echo date('Y') + 1 ?></span>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<label class="card">
												<input name="campus" class="radio" type="radio" value="Rabat" checked>
												<span class="plan-details">
													<span class="plan-type">Rabat</span>

												</span>
											</label>
										</div>
										<div class="col-md-6">
											<label class="card">
												<input name="campus" class="radio" value="Casablanca" type="radio">
												<span class="plan-details" aria-hidden="true">
													<span class="plan-type">Casablanca</span>
												</span>
											</label>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="">Adresse</label> -->
												<input style="font-weight: normal !important;" type="text" id="adresse" class="form-control main-input" name="adresse" placeholder="Adresse " required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="">GSM</label> -->
												<input style="font-weight: normal !important;" type="text" id="tel" class="form-control main-input" name="tel" placeholder="GSM du parent *" required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<!-- <label for="">Email</label> -->
												<input style="font-weight: normal !important;" type="email" id="email" class="form-control main-input" name="email" placeholder="Email du parent" />
											</div>
										</div>
									</div>
									<br>

									<button class="btn btn-main btn-block text-center text-uppercase">Envoyer la demande</button>
								</form>
							<?php } ?>
						</div>
					</div>
				</div>

			</div>
			<?php
			$logo = (Config::get('logo_white') ? URL::base('assets/images/schools/white/' . Config::get('logo_white')) : URL::base('assets/images/logo.103983.png'));
			$mainColor = Config::get('main_color') ? Config::get('main_color') : '004c8b';
			?>
			<div class="col-md-6 order-1 order-md-2" style="background: url(<?php echo URL::base(Config::get('path-images')) ?>ins_back.png);">
				<div style="background:#<?php echo $mainColor ?>;color: #FFF;padding: 30px 60px 20px;text-align:center;opacity: 72%;display: flex;justify-content: center;align-items: center;height: 100%;">
					<div>
						<img src="<?php echo $logo ?>" class="logo-navbar" alt="<?php echo Config::get('nom_ecole') ?>" style="width: 200px;">
						<br>
						<br>
						<div>
							<h3 style="padding: 17px;"> Contactez-nous ! </h3>
							<p> Pour toutes vos questions & demandes d'informations </p>
							<div>
								<?php if (Config::get('email')) { ?>
									<a style="color: #fff !important;padding: 5px; font-weight: bold;" href="mailto:<?php echo Config::get('email')  ?>" class="d-block"><span class="icon fa fa-envelope"></span> <?php echo Config::get('email') ?></a>
								<?php } ?>
								<?php if (Config::get('tel')) { ?>
									<?php foreach (explode(",", Config::get('tel')) as $tel) { ?>
										<a style="color: #fff !important;padding: 5px;display:block;font-weight: bold;" href="tel:<?php echo $tel ?>"><span class="material-icons">phone</span> <?php echo $tel ?></a>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<style>
	.inscription-form-container label {
		color: #b1b1b1 !important;
		font-weight: bold;
	}

	.form-control {
		border: 1px solid #c7c7c7 !important;
		font-weight: bold !important;
		color: #b1b1b1 !important;
	}

	input:hover {
		border: 1px solid #7336ff !important;
	}

	input:focus {
		border: 1px solid #7336ff !important;
	}

	input:active {
		border: 1px solid #7336ff !important;
	}

	.inscription-form-container .form-group {
		margin-bottom: 9px !important;
	}

	@media (max-width: 991px) {
		.inscription-form-container h1 {
			white-space: pre-wrap;
			width: 100%;
			margin-bottom: 20px;
			line-height: 1.3;
			font-size: 25px;
			overflow: hidden;
			width: none !important;
			padding: none !important;
			margin: none !important;
		}
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
		visibility: hidden;
		background: transparent;
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

	.card:hover .plan-details {
		border-color: var(--color-dark-gray);
	}

	.radio:checked~.plan-details {
		border-color: var(--color-green);
	}

	.radio:focus~.plan-details {
		box-shadow: 0 0 0 2px var(--color-dark-gray);
	}

	.radio:disabled~.plan-details {
		color: var(--color-dark-gray);
		cursor: default;
	}

	.radio:disabled~.plan-details .plan-type {
		color: var(--color-dark-gray);
	}

	.card:hover .radio:disabled~.plan-details {
		border-color: var(--color-gray);
		box-shadow: none;
	}

	.card:hover .radio:disabled {
		border-color: var(--color-gray);
	}

	.plan-type {
		color: var(--color-green);
		font-size: 1.5rem;
		font-weight: bold;
		line-height: 1em;
	}

	.plan-cost {
		font-size: 2.5rem;
		font-weight: bold;
		padding: 0.5rem 0;
	}

	.slash {
		font-weight: normal;
	}

	.plan-cycle {
		font-size: 2rem;
		font-variant: none;
		border-bottom: none;
		cursor: inherit;
		text-decoration: none;
	}

	.hidden-visually {
		border: 0;
		clip: rect(0, 0, 0, 0);
		height: 1px;
		margin: -1px;
		overflow: hidden;
		padding: 0;
		position: absolute;
		white-space: nowrap;
		width: 1px;
	}
</style>