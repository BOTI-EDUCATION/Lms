<div class="home">
	<div class="login">


		<div class="website-logo-inside">
			<a href="#">
				<div class="logo">
					<img class="logo-size" src="<?php echo (Config::get('logo_white') ? URL::absolute(URL::base('assets/images/schools/white/' . Config::get('logo_white'))) : URL::absolute(URL::base('assets/images/logo.92874874.png'))) ?>" alt="<?php echo (Config::get('nom_ecole') ? Config::get('nom_ecole') : 'BOTI SCHOOL'); ?>">
				</div>
			</a>
		</div>
		<?php if (isset($errorLogin) && $errorLogin == true) { ?>
			<div class="alert alert-danger">
				<button class="close" data-close="alert"></button>
				<span> <?php echo $errorLogin ?></span>
			</div>
		<?php } ?>
		<form method="post" action="<?php echo URL::link('login') ?>" class="form-horizontal form-login">
			<?= cf_fields() ?>
			<input type="text" name="gsm" class="form-control mb-2 mt-2" id="user-name" placeholder="Nnuméro de GSM OU E-mail" required>
			<input type="hidden" name="op" value="password" />
			<div class="form-button">
				<button id="submit" type="submit" class="btn btn-hero">Réinitlaiser le mot de passe</button>
				<a href="<?php echo URL::link('login') ?>" class="btn" style="color:#171656;font-weight: 700;">Se connecter</a>
			</div>
		</form>
	</div>
</div>
<style>
	.home {
		height: 100vh;
		display: flex;
		justify-content: center;
		align-items: center;
		background: rgb(131, 58, 180);
		background: -moz-linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
		background: -webkit-linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
		background: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#833ab4", endColorstr="#fcb045", GradientType=1);
	}

	.home .login {
		width: 30%;
		height: 50%;
		background: white;
		padding: 20px;
		border-radius: 14px;
		box-shadow: 0px 0px 7px #00000061;
	}

	.btn-hero {
		background: linear-gradient(180deg, #f1465d 0%, #f86836 100%);
		border-radius: 50px;
		color: white;
	}
</style>