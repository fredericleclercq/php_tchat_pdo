<?php

/* mon require */
require_once('inc/init.php');

if (!isset($_SESSION['pseudo'])) // si on a pas de pseudo enregistré en session, c'est que je ne suis pas passé par la page connexion
{
    header('location:connexion.php'); // redirection vers connexion
}

require_once('header.php');

?>


<div class="row d-flex">
    <div id="message_tchat" class="col-md-8 main bg-dark text-light px-4 py-2 order-1 order-md-0">

        <h5>Connecté en tant que <?= ucfirst($_SESSION['pseudo']) ?></h5>
        <?php
        $result = $pdo->query("SELECT 
            d.id_dialogue,m.pseudo,m.civilite,d.message,
            DATE_FORMAT(d.date,'%d/%m/%Y') as datefr,
            DATE_FORMAT(d.date, '%H:%i:%s') as heurefr
            FROM dialogue d, membre m
            WHERE m.id_membre = d.id_membre 
            ORDER BY d.date");
        while ($dialogue = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($dialogue['civilite'] == 'm') {
                $couleur = "bleu";
            } else {
                $couleur = "rose";
            }

            echo '<p title="' . $dialogue['datefr'] . ' - ' . $dialogue['heurefr'] . '" class="' . $couleur . '">' . $dialogue['heurefr'] . ' <strong>' . ucfirst($dialogue['pseudo']) . '</strong>
                : ' . htmlspecialchars($dialogue['message'], ENT_NOQUOTES) . '</p>';
        }
        ?>
    </div>
    <div id="liste_membre_connecte" class="col-md-4 main bg-dark text-light px-4 py-2 order-0 order-md-1">
        <h5>Membres connectés:</h5>
        <?php
        $resultat = $pdo->query("SELECT * FROM membre WHERE date_active >" . (time() - 1800) . " ORDER BY pseudo ASC");
        while ($membre = $resultat->fetch(PDO::FETCH_ASSOC)) {
            if ($membre['civilite'] == 'm') {
                $couleur = 'bleu';
                $titre = "Homme";
            } else {
                $couleur = 'rose';
                $titre = "Femme";
            }

            $avatar = (!empty($membre['avatar'])) ? $membre['avatar'] : 'unknown.png';
            echo '<div class="d-flex  align-items-center justify-content-start ">
                        <img src="avatars/' . $avatar . '" class="avatar">
                        <p class="' . $couleur . ' pl-2" title="' . $titre . ', ' . $membre['ville'] . ', ' . age($membre['date_de_naissance']) . ' ans">' . ucfirst($membre['pseudo']) . '</p>
                    </div>';
        }
        ?>
    </div>

</div>

<div class="row">
    <div class="col-12  px-3 py-3">
        <img class="smiley" src="smil/smiley1.png" alt=":)">
        <img class="smiley" src="smil/smiley2.png" alt=":(">
        <img class="smiley" src="smil/smiley3.png" alt=";)">
        <img class="smiley" src="smil/smiley4.png" alt=":p">
        <img class="smiley" src="smil/smiley5.png" alt=":x">
        <img class="smiley" src="smil/smiley6.png" alt=":/">
    </div>
</div>


<div id="formulaire_tchat">
    <form method="post" action="#" class="row  bg-dark text-light px-2 py-2 bg-light">
        <div class="col-md-8">
            <input type="text" id="message" name="message" class="form-control">
        </div>
        <div class="col-md-4 mt-3 mt-md-0 d-flex align-items-center justify-content-center">
            <input type="submit" name="envoi" value="Envoi" class="btn btn-primary btn-sm d-block mx-auto" id="submit">
            <button type="button" class="btn btn-warning btn-sm d-block mx-auto" id="deco_button">Déconnecter</button>
        </div>
    </form>
</div>
</div>
</body>

</html>