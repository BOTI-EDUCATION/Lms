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
        <form method="post" action="" class="form-horizontal form-login">
            <?= cf_fields() ?>
            <input type="text" name="login" class="form-control mb-2" id="user-name" placeholder="Adresse E-mail" required>
            <input type="password" name="password" class="form-control mb-2" id="user-password" placeholder="Mot de passe" required>
            <input type="hidden" name="op" value="login" />
            <div class="form-button">
                <button id="submit" type="submit" class="btn btn-hero">Se connecter</button>
                <a href="<?php echo URL::link('login/auth') ?>" class="btn" style="color:#171656;font-weight: 700;">Mot de passe oublié ?</a>
            </div>
        </form>
        <div class="other-links hidden">
            <span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
        </div>
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