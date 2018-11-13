<?php

require_once('inc/init.php');

// traitement du formulaire en post
if ( isset($_POST['connexion']) ) // si on clique sur connexion
{

    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo AND password=:mdp");
    $resultat->execute( array(
        'pseudo' => $_POST['pseudo'],
        'mdp' => md5($_POST['mdp'])
        ) );
   

    if ( $resultat->rowCount()  == 1 )
    {
        $membre = $resultat->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id_membre'] = $membre['id_membre'];
        $_SESSION['pseudo'] = $_POST['pseudo'];
        header('location:index.php');
        exit();
    }
    else
    {
        $msg .='<div class="alert alert-danger">Indentifiants invalides</div>';
    }


}

require_once('header.php');

?>
<?= $msg ?> 
<fieldset>
    <form method="post" action=""  class="mx-4 my-4">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" required value="" class="form-control"><br>

        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" required value="" class="form-control"><br>

        <input type="submit" name="connexion" value="Connexion au Tchat!" class="btn btn-primary">
    </form>
</fieldset>
<p>
Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a>
</p>


</body>
</html>