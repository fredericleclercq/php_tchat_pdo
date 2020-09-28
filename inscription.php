<?php

require_once('inc/init.php');

// traitement du formulaire en post
if (isset($_POST['inscription'])) // si on clique sur connexion
{

    // prévoir la copie fichier avatar 
    $avatar = $_POST['memoavatar'] ?? '';

    if (!empty($_FILES['avatar']['name'])) {
        $avatar = date('YmdHis') . '_' . $_FILES['avatar']['name'];
        $chemin = $_SERVER['DOCUMENT_ROOT'] . URL . 'avatars/';
        move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin . $avatar);
    }

    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $resultat->execute(array('pseudo' => $_POST['pseudo']));
    $membre = $resultat->fetch();

    if ($resultat->rowCount() == 0) {
        //controles additionnels
        $nbEmpty = 0;
        foreach ($_POST as $index => $value) {
            if ($index != 'memoavatar' && $value == '') $nbEmpty++;
        }
        if ($nbEmpty > 0) {
            $msg[] = 'Il manque ' . $nbEmpty . ' information' . (($nbEmpty > 1) ? 's' : '');
        } else {
            // insertion en base d'un nouveau membre
            $result = $pdo->prepare("INSERT INTO membre VALUES (NULL, :pseudo,:mdp,:civilite,:ville,:date_de_naissance," . time() . ",:avatar)");
            $result->execute(array(
                'pseudo' => $_POST['pseudo'],
                'mdp' => md5($_POST['mdp']),
                'civilite' => $_POST['civilite'],
                'ville' => $_POST['ville'],
                'date_de_naissance' => $_POST['date_de_naissance'],
                'avatar' => $avatar
            ));
            $id_membre = $pdo->lastInsertId();
        }
    } else {
        $msg[] = 'Ce pseudo est déjà réservé';
    }

    if (empty($msg)) {
        // remplir $_SESSION et rediriger vers index.php
        $_SESSION['id_membre'] = $id_membre;
        $_SESSION['pseudo'] = $_POST['pseudo'];
        header('location:index.php');
        exit();
    }
    $msg = '<div class="alert alert-danger">' . implode('<br>', $msg) . '</div>';
}


require_once('header.php');

?>

<?= (!empty($msg) ? $msg : '') ?>
<div class="row">
    <div class="col splash d-flex flex-column justify-content-center align-items-center pt-2 pb-3">
        <fieldset class="w-100">

            <form method="post" action="" enctype="multipart/form-data" class="mx-4 my-4">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" id="pseudo" name="pseudo" required value="<?= $_POST['pseudo'] ?? '' ?>" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="mdp">Mot de passe</label>
                        <input type="password" id="mdp" name="mdp" required value="" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label id="depotavatar" for="avatar" class="d-block bg-primary py-4 text-center rounded">
                        Avatar<br>
                        Cliquer ou déposer ici<br>
                        (jpg, png ou gif - max : 1200x1200)
                    </label>
                    <input type="file" id="avatar" name="avatar" class="d-none" accept="image/png, image/jpeg, image/gif">
                    <input type="text" id="memoavatar" name="memoavatar" value="<?= (!empty($avatar)) ? $avatar : ''  ?>" class="d-none">
                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label>Genre</label>
                        <select name="civilite" class="form-control">
                            <option value="f" selected>Femme</option>
                            <option value="m" <?= (isset($_POST['civilite']) && ($_POST['civilite'] == 'm')) ? 'selected' : '' ?>>Homme</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" value="<?= $_POST['ville'] ?? '' ?>" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date_de_naissance">Date de Naissance</label>
                        <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?= $_POST['date_de_naissance'] ?? '' ?>" class="form-control">
                    </div>
                </div>

                <input type="submit" name="inscription" value="Inscription" class="btn btn-primary">
            </form>
        </fieldset>
        <p>
            J'ai déjà un compte : <a href="<?= URL . 'connexion.php' ?>" class="text-light">Se connecter</a>
        </p>
    </div>
</div>
</div>
</body>

</html>