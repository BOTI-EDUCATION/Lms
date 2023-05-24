<div class="content-body page-reinscription">
<div class="card">
	<div class="row">
		<div class="col-lg-6 offset-lg-3 col-xs-12">
			<div class="card-body">
				<div class="card-block">
					<?php if($reinscriptionFaite && $eleves) { ?>
						 <div class="header-reinscription">
							 <div class="varow-xs">
								 <div class="vacol-xs-3">
									<img src="<?php echo URL::base() ?>assets/images/svg/medal.svg" alt="STEVE JOBS SCHOOL" style="">
								</div>
								<div class="vacol-xs-9">
									<h2 style="">La ré-inscription de <?php echo (count($eleves) > 1)?'vos enfants':'votre enfant' ?> pour l'année scolaire <?php echo $promotion->get('Label') ?> a été faite.</h2>
								</div>
							 </div>
						 </div>
					<?php } else { ?>
						 <div class="header-reinscription">
							 <div class="varow-xs">
								 <div class="vacol-xs-3">
									<img src="<?php echo URL::base() ?>assets/images/svg/medal.svg" alt="STEVE JOBS SCHOOL" style="">
								</div>
								<div class="vacol-xs-9">
									<h2 style="">RÉ-INSCRIPTION - <?php echo $promotion->get('Label') ?></h2>
									<h3 style="">Bonjour <?php echo Session::getInstance()->getCurUser()->getNomComplet()?>,</h3>
									<p style="">Merci de valider la réinscription de <?php echo (count($eleves) > 1)?'vos enfants':'votre enfant' ?>  pour l'année scolaire <?php echo $promotion->get('Label') ?>  :</p>
								</div>
							 </div>
						 </div>
						
						<form method="post" >
							<?= cf_fields() ?>
							<div class="body-reinscription" >
								<div class="col-md-8 offset-lg-2 col-xs-12" >
									<?php foreach($eleves as $item) {
										$inscription = $item['inscription'];
										$eleve = $item['eleve'];
										
									?>
									 <div class="etudiant-parrainage" for="d" style="">
										<label style="" for="d<?php echo $eleve->get('ID') ?>">
										<input type="checkbox" name="eleves[]" id="d<?php echo $eleve->get('ID') ?>" value="<?php echo $eleve->get('ID') ?>" checked> 
										<img src="<?php echo URL::base() ?>assets/images/svg/check-success.svg" class="check" alt="">
										 <div class="varow-xs">
											 <div class="vacol-xs-3">
												<img class="eleve-img img-responsive" src="<?php echo $eleve->get('User')->getImage() ?>" class="" style="">
											</div>
											<div class="vacol-xs-9">
												<h3 style=""><?php echo $eleve->get('User')->getNomComplet() ?></h3>
												<p style=""><?php echo $inscription->get('Classe')->get('Niveau')->get('Label') ?> <img style="" src="<?php echo URL::base() ?>assets/images/svg/right-arrow.svg" alt=""> <?php echo $inscription->get('Classe')->get('Niveau')->nextNiveau()->get('Label') ?></p>
											</div>
										</div>
										</label>
									</div>
									<input type="hidden" name="op" value="reinscription" />
									<input type="hidden" name="op" value="reinscription" />
									<?php } ?>
									<button style="" type="submit" class="btn btn-info btn-block  btn-min-width mr-1 mb-1">Confirmer la ré-inscription</button>
								</div>
							</div>
						</form>
					<?php }  ?>
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
