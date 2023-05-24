<section class="new_inscription">
	<div class="">
		<div class="row no-gutters">
			<div class="col-md-8 order-2 order-md-1">
				<div class="row">
					<div class="col-md-10 offset-md-2">
						<div class="inscription-form-container">
							<form action="<?php echo URL::absolute('/ajax', null, true) ?>" id="inscription-ecole">
							<div class="lds-ripple-container">
								<div class="lds-ripple">
									<div></div>
									<div></div>
								</div>
							</div>
							<input type="hidden" name="op" value="demo-form" />
							<input  type="hidden" name="cf_token" value="<?php echo cf_token(); ?>" /> 
								<div class="text-center">
									<img src="<?php echo URL::base() ?>assets/student/img/logo.svg" alt="">
									<h1>Tout ce qu’il vous faut pour <span>réussir</span> le <br> passage de votre école à l’ère du digital.</h1>
								</div>
								
								<div class="form-group">
									<label for="">Sélectionnez un ou plusieurs cycles d'enseignement</label>
									<div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
										<label class="btn btn-light active btn-check">
											<input type="checkbox" name="niveaux[]" id="maternelle" value="maternelle"> Maternelle
										</label>
										<label class="btn btn-light btn-check">
											<input type="checkbox" name="niveaux[]" id="primaire" value="primaire"> Primaire
										</label>
										<label class="btn btn-light btn-check">
											<input type="checkbox" name="niveaux[]" id="college" value="collége"> Collège
										</label>
										<label class="btn btn-light btn-check">
											<input type="checkbox" name="niveaux[]" id="lycee" value="lycee"> Lycée
										</label>
									</div>
								</div>

								<div class="form-group">
									<label for="">Nom de l’école</label>
									<input type="text" id="inp_ecole" class="form-control main-input" name="ecole" placeholder="Nom de l’école" required />
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Nom de la personne à contacter</label>
											<input type="text"  class="form-control main-input" id="inp_nom" name="nom" class="form-control"  placeholder="Nom complet" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Ville</label>
											<select id="ville" name="ville" class="form-control">
												<option value="Casablanca" selected>Casablanca</option>
												<option value="Rabat">Rabat</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">GSM</label>
											<input type="text" id="inp_phone" class="form-control main-input" name="tel" placeholder="GSM" required />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Email</label>
											<input type="text" id="inp_email"  class="form-control main-input"  name="email" placeholder="Email" />
										</div>
									</div>
								</div>
								<button class="btn btn-main btn-block text-center text-uppercase">Envoyer la demande</button>
							</form>
							<div class="inscription-form-container inscription-ecole-success" style="display:none">
                                <div class="text-center">
                                    <img src="<?php echo URL::base() ?>assets/student/img/logo.svg" alt="">
                                </div>
                                <div class="inscription_success mt-5 text-center">
                                    <img src="<?php echo URL::base() ?>assets/student/img/success.svg" alt="">
                                    <h6>Demande envoyée <br> avec succès</h6>
                                    <p>Votre demande a été envoyée avec succès, <br> nous revenons vers vous le plus <br> rapidement possible.</p>
                                </div>
                            </div>
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-4 order-1 order-md-2">
				<div class="inscription_new_presentation">
					<img src="<?php echo URL::base() ?>assets/student/img/boti.png" class="img-fluid" alt="">
					<h2>Continuez à gérer vos écoles à distance en période de crise du Coronavirus</h2>
					<p>Suite à la décision du ministère de l’éducation de fermer tous les établissements éducatifs à partir du lundi 16 Mars 2020 pour prévenir contre le Coronavirus, Boti Education, agence spécialisée dans la Transformation Digitale du secteur de l’éducation, offre gratuitement une version simplifiée de sa solution de gestion des établissements éducatifs aux écoles marocaines pour assurer la continuité des cours à distance.</p>
					<p>La version simplifiée de Botischool développée spécifiquement pour aider les écoles en cette période de crise permettra aux administrateurs d’écoles de :</p>
					<ul>
						<li>Communiquer en ligne et en temps réel avec élèves et parent d’élèves</li>
						<li>Partager avec les élèves toutes les ressources pédagogiques pour assurer une continuité d’apprentissage</li>
						<li>Traiter et suivre les demandes administratives et pédagogiques des parents</li>
						<li>Impliquer le corps professoral en leur permettant un accès en ligne</li>
						<li>Administrer sa base de classes, élèves, enseignants …</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>