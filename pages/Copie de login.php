<div class="content-header row">
</div>
<div class="content-body">
	<section class="flexbox-container">
		<div class="col-lg-4 offset-lg-4 col-xs-10 offset-xs-1 box-shadow-3 p-0">
			<div class="card border-grey border-lighten-3 px-1 py-1 m-0">
				<div class="card-header no-border">
					<div class="card-title text-xs-center">
						<img src="<?php echo URL::base() ?>assets/icons/boti.svg" alt="Botischool" style="width:120px;">
					</div>
					<h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>SMART EDUC</span></h6>
				</div>
				<div class="card-body collapse in">
					<div class="card-block card-login">
						<?php if(isset($errorLogin) && $errorLogin==true) { ?>
							<div class="alert alert-danger">
								<button class="close" data-close="alert"></button>
								<span> <?php echo $errorLogin ?></span>
							</div>
						<?php } ?>
						<form method="post" action="" class="form-horizontal form-login" novalidate>
							<?= cf_fields() ?>
							<fieldset class="form-group position-relative has-icon-left">
								<input  type="text" name="login" class="form-control" id="user-name" placeholder="Adresse E-mail" required>
								<div class="form-control-position">
									<i class="ft-user"></i>
								</div>
							</fieldset>
							<fieldset class="form-group position-relative has-icon-left">
								<input type="password" name="password" class="form-control" id="user-password" placeholder="Mot de passe" required>
								<div class="form-control-position">
									<i class="fa fa-key"></i>
								</div>
							</fieldset>
							<fieldset class="form-group row">
								<div class="col-md-6 col-xs-12 text-xs-center text-sm-left">
									<fieldset>
										<input type="checkbox" id="remember-me" class="chk-remember">
										<label for="remember-me"> Se souvenir de moi :)</label>
									</fieldset>
								</div>
								<div class="col-md-6 col-xs-12 float-sm-left text-xs-center text-sm-right">
								<a href="#" data-login-card=".form-code"  class="btn-inscription-avocat btn-main">Se connecter avec un code</a>
								</div>
							</fieldset>
							
							<input type="hidden" name="op" value="login" />
							<button type="submit" class="btn btn-outline-primary btn-block"><i class="ft-unlock"></i> Se connecter</button>
							
						</form>
						<form method="post" action="" class="form-horizontal form-code" novalidate>
							<?= cf_fields() ?>
							<fieldset class="form-group position-relative has-icon-left">
								<input  type="text" name="code" class="form-control" id="user-code" placeholder="Saisir votre code" required>
								<div class="form-control-position">
									<i class="ft-user"></i>
								</div>
							</fieldset>
							<input type="hidden" name="op" value="login" />
							<button type="submit" class="btn btn-outline-primary btn-block"><i class="ft-unlock"></i> S'authentifier</button>
							<a href="#" data-login-card=".form-login"  class="btn-inscription-avocat btn-main">Se connecter avec un compte</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>