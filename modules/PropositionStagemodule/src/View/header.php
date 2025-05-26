<header>
    <div><img src="./ressources/logo.png" alt="logo" class="logo"></div>
    <div class="nav">
            <div><a href="/Accueil" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active-link' : ''; ?>">Accueil</a></div>
            <div><a href="/proposition_stage" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'proposals.php') ? 'active-link' : ''; ?>">Propositions</a></div>
            <?php if(isset($_SESSION['user'])){
                if($_SESSION['user']['role']==='GC'){
                ?>
            <div><a href="/candidatures">Gérer candidatures</a></div>
                <?php }
                        }?>
            <?php if(isset($_SESSION['user'])){
                if($_SESSION['user']['role']==='R&C'){
                ?>
            <div><a href="/PropositionBoard">Gérer propositions</a></div>
                <?php }
                        }?>
            <?php if(isset($_SESSION['user'])){
                if($_SESSION['user']['role']==='partenaire'){
                ?>
            <div><a href="/propositionCreate">Proposer un stage</a></div>
                <?php }
                        }?>
            <div><a href="/register" class="connexion">Inscription</a></div>
            <div><a href="/login" class="connexion">Connexion</a></div>
            <?php if(isset($_SESSION['user'])){
                if($_SESSION['user']['role']==='individus'){
                ?>
            <div><a href="/profile"><img src="./ressources/profil-de-lutilisateur.png" alt="Profil utilisateur" class="user-icon"></a></div>
                <?php }
                        }?>
            <!-- <div><a href="#"><img src="./ressources/profile_img.svg" alt="Profil utilisateur" class="user-icon"></a></div> -->
    </div>
</header>
    