<?php

require_once('inc/init.php');

// traitement du formulaire en post
if ( isset($_POST['inscription']) ) // si on clique sur connexion
{

    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $resultat->execute( array('pseudo' => $_POST['pseudo']) );
    $membre = $resultat->fetch(PDO::FETCH_ASSOC);

    if ( $resultat->rowCount() == 0 )
    {
        //controles additionnels

        // insertion en base d'un nouveau membre
        $result=$pdo->prepare("INSERT INTO membre VALUES (NULL, :pseudo,:mdp,:civilite,:ville,:date_de_naissance,".time().")");
        $result->execute(array(
            'pseudo' => $_POST['pseudo'],
            'mdp' => md5($_POST['mdp']),
            'civilite' => $_POST['civilite'],
            'ville' => $_POST['ville'],
            'date_de_naissance' => $_POST['date_de_naissance']            
        ));
        $id_membre=$pdo->lastInsertId();
    }
    else
    {
        $msg .= '<div class="alert alert-danger">Ce pseudo est déjà réservé</div>';
    }

    if (empty($msg))
    {
        // remplir $_SESSION et rediriger vers index.php
        $_SESSION['id_membre'] = $id_membre;
        $_SESSION['pseudo'] = $_POST['pseudo'];
        header('location:index.php');
        exit();
    }

}


require_once('header.php');

?>

<?= $msg ?> 
<fieldset>
    
    <form method="post" action="" class="mx-4 my-4">
        <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" required value="<?= $_POST['pseudo'] ?? '' ?>" class="form-control">
        </div>

        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" required value="" class="form-control"><br>

        <label for="civilite">Civilité</label>
        <input type="radio" name="civilite" value="f" checked> Femme
        <input type="radio" name="civilite" value="m" <?= ( isset($_POST['civilite']) && ($_POST['civilite'] == 'm' )) ? 'checked' : '' ?>> Homme<br>
        
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" value="<?= $_POST['ville'] ?? '' ?>" class="form-control"><br>

        <label for="date_de_naissance">Date de Naissance (YYYY-MM-DD)</label>
        <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?= $_POST['date_de_naissance'] ?? '' ?>" class="form-control"><br>

        <input type="submit" name="inscription" value="Inscription" class="btn btn-primary">
    </form>
</fieldset>
<p>
J'ai déjà un compte : <a href="connexion.php">Se connecter</a>
</p>

</body>
</html>