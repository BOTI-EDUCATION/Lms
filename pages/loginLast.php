<div class="form-body" style="background-color:#<?php echo (Config::get('main_color') ? Config::get('main_color') : '004c8b') ?> !important">
	<div class="website-logo">
		<a href="#">
			<div class="logo">
				<img class="logo-size" src="images/logo-light.svg" alt="">
			</div>
		</a>
	</div>
	<div class="row">
		<div class="img-holder">
			<div class="bg"></div>
			<div class="info-holder" style="background:linear-gradient(to right, ##<?php echo (Config::get('main_color') ? Config::get('main_color') : '004c8b') ?> 0%, ##<?php echo (Config::get('main_color') ? Config::get('main_color') : '004c8b') ?> 60%)">
				<h3><?php echo (Config::get('nom_ecole') ? Config::get('nom_ecole') : 'BOTI SCHOOL') ?></h3>
				<p><?php echo (Config::get('login_tagline') ? Config::get('login_tagline') : 'Pour une nouvelle expérience éducative.') ?></p>
				<img src="<?php echo URL::base() ?>assets/images/backgrounds/boti.png" alt="">
			</div>
			<div class="boti-login">
				<img src="<?php echo URL::base() ?>assets/icons/boti.svg" alt="Botischool">
			</div>
		</div>
		<div class="form-holder">
			<div class="form-content">
				<div class="form-items">
					<div class="website-logo-inside">
						<a href="#">
							<div class="logo">
								<img class="logo-size" src="<?php echo (Config::get('logo_white') ? URL::absolute(URL::base('assets/images/schools/white/' . Config::get('logo_white'))) : URL::absolute(URL::base('assets/images/logo.92874874.png'))) ?>" alt="<?php echo (Config::get('nom_ecole') ? Config::get('nom_ecole') : 'BOTI SCHOOL'); ?>">
							</div>
						</a>
					</div>
					<div class="page-links">
						<a href="#" class="active">Connexion</a>
						<?php if (true) { ?>
							<a href="<?php echo URL::link('login/auth') ?>">Mot de passe oublié</a>
						<?php } ?>
					</div>
					<?php if (isset($errorLogin) && $errorLogin == true) { ?>
						<div class="alert alert-danger">
							<button class="close" data-close="alert"></button>
							<span> <?php echo $errorLogin ?></span>
						</div>
					<?php } ?>
					<form method="post" action="" class="form-horizontal form-login">
						<?= cf_fields() ?>
						<input type="text" name="login" class="form-control" id="user-name" placeholder="Adresse E-mail" required>
						<input type="password" name="password" class="form-control" id="user-password" placeholder="Mot de passe" required>
						<input type="hidden" name="op" value="login" />
						<div class="form-button">
							<button id="submit" type="submit" class="ibtn">Se connecter</button> <a href="<?php echo URL::link('login/auth') ?>">Mot de passe oublié ?</a>
						</div>
					</form>
					<div class="other-links hidden">
						<span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>