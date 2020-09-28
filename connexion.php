<?php

require_once('inc/init.php');

// traitement du formulaire en post
if (isset($_POST['connexion'])) // si on clique sur connexion
{

    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo AND password=:mdp");
    $resultat->execute(array(
        'pseudo' => $_POST['pseudo'],
        'mdp' => md5($_POST['mdp'])
    ));


    if ($resultat->rowCount()  == 1) {
        $membre = $resultat->fetch();
        $_SESSION['id_membre'] = $membre['id_membre'];
        $_SESSION['pseudo'] = $_POST['pseudo'];
        // update date activité
        $resultat = $pdo->prepare("UPDATE membre SET date_active=:date_activite WHERE id_membre=:id_membre");
        $resultat->execute(array(
            'id_membre' => $membre['id_membre'],
            'date_activite' => time()
        ));
        header('location:index.php');
        exit();
    } else {
        $msg[] = 'Indentifiants invalides';
    }

    $msg = '<div class="alert alert-danger col-md-8 offset-md-2 ">' . implode('<br>', $msg) . '</div>';
}

require_once('header.php');

?>
<?= (!empty($msg) ? $msg : '') ?>
<div class="row">
    <div class="col-md-8 offset-md-2 splash d-flex flex-column justify-content-center align-items-center pt-2 pb-3">
        <fieldset class="w-100">
            <form method="post" action="" class="mx-4 my-4">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" required value="" class="form-control">
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" required value="" class="form-control"><br>
                </div>

                <input type="submit" name="connexion" value="Connexion à Simple Tchat" class="btn btn-primary">
            </form>
        </fieldset>
        <p>
            Pas encore de compte ? <a href="<?= URL . 'inscription.php' ?>" class="text-light">Inscrivez-vous ici</a>
        </p>
    </div>
</div>
</div>
</body>

</html>