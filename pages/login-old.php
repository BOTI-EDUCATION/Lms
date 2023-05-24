<div class="form-body">
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
			<div class="info-holder">
				<h3>Tout ce qu’il vous faut pour passer à l’ère du digital.</h3>
				<p>Tout ce qu’il vous faut pour réussir le passage de votre école à l’ère du digital.</p>
				<img src="<?php echo URL::base() ?>assets/images/backgrounds/boti.png" alt="">
			</div>
			<div class="boti-login">
				<img src="<?php echo URL::base() ?>assets/icons/boti.svg" alt="Botischool" >
			</div>
		</div>
		<div class="form-holder">
			<div class="form-content">
				<div class="form-items">
					<div class="website-logo-inside">
						<a href="#">
							<div class="logo">
								<img class="logo-size" src="<?php echo URL::base() ?>assets/images/logo-light.png" alt="">
							</div>
						</a>
					</div>
					<div class="page-links">
						<a href="#" class="active">Connexion</a>
						<?php if(false) { ?>
							<a href="<?php echo URL::link('login/auth') ?>">Se connecter avec un code</a>
						<?php } ?>
					</div>
					<?php if(isset($errorLogin) && $errorLogin==true) { ?>
						<div class="alert alert-danger">
							<button class="close" data-close="alert"></button>
							<span> <?php echo $errorLogin ?></span>
						</div>
					<?php } ?>
					<form method="post" action="" class="form-horizontal form-login" >
						<?= cf_fields() ?>
						<input  type="text" name="login" class="form-control" id="user-name" placeholder="Adresse E-mail" required>
						<input type="password" name="password" class="form-control" id="user-password" placeholder="Mot de passe" required>
						<input type="hidden" name="op" value="login" />
						<div class="form-button">
							<button id="submit" type="submit" class="ibtn">Se connecter</button> <a href="#">Mot de passe oublié ?</a>
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