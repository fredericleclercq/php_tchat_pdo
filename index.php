<?php

/* mon require */
require_once('inc/init.php');

if ( !isset($_SESSION['pseudo']) ) // si on a pas de pseudo enregistré en session, c'est que je ne suis pas passé par la page connexion
{
    header('location:connexion.php'); // redirection vers connexion
}

require_once('header.php');

?>


    <div class="row">
        <div id="message_tchat" class="col-md-8 border main bg-light px-4 py-2 scrollbar scrollbar-primary">
            
            <h4>Connecté en tant que <?= ucfirst($_SESSION['pseudo']) ?></h4>
            <?php
            $result = $pdo->query("SELECT 
            d.id_dialogue,m.pseudo,m.civilite,d.message,
            DATE_FORMAT(d.date,'%d/%m/%Y') as datefr,
            DATE_FORMAT(d.date, '%H:%i:%s') as heurefr
            FROM dialogue d, membre m
            WHERE m.id_membre = d.id_membre 
            ORDER BY d.date");
            while ( $dialogue = $result->fetch(PDO::FETCH_ASSOC ))
            {
                if ($dialogue['civilite'] == 'm' ) { $couleur="bleu";}else{$couleur="rose";}

                echo '<p title="'.$dialogue['datefr'].' - '.$dialogue['heurefr'].'" class="'.$couleur.'">'.$dialogue['heurefr'].' <strong>'.ucfirst($dialogue['pseudo']).'</strong>
                : '.htmlspecialchars($dialogue['message'],ENT_NOQUOTES).'</p>';

            }
            ?>
        </div>
        <div id="liste_membre_connecte" class="col-md-4 border main bg-light px-4 py-2">
                <h4>Membres connectés:</h4>
                <?php
                $resultat=$pdo->query("SELECT * FROM membre WHERE date_connexion >".(time()-1800)." ORDER BY pseudo ASC");
                while ( $membre = $resultat->fetch(PDO::FETCH_ASSOC))
                {
                    if ( $membre['civilite'] == 'm' )
                    {
                        $couleur='bleu';
                        $titre="Homme";
                    }
                    else{
                        $couleur='rose';
                        $titre="Femme";
                    }
                    echo '<p class="'.$couleur.'" 
                    title="'.$titre.', '.$membre['ville'].', '.age($membre['date_de_naissance']).' ans">'.ucfirst($membre['pseudo']).'</p>';
                }
            ?>
        </div>
    </div>

    <div class="row">
            <div class="col-12 border px-3 py-3">        
                    <img class="smiley" src="smil/smiley1.png" alt=":)">
                    <img class="smiley" src="smil/smiley2.png" alt=":(">
                    <img class="smiley" src="smil/smiley3.png" alt=";)">
                    <img class="smiley" src="smil/smiley4.png" alt=":p">
                    <img class="smiley" src="smil/smiley5.png" alt=":x">
                    <img class="smiley" src="smil/smiley6.png" alt=":/">
            </div>
   </div>


    <div id="formulaire_tchat">
        <form method="post" action="#" class="row border px-2 py-2 bg-light">
            <div class="col-9">
                <input type="text" id="message" name="message" class="form-control">
            </div>
            <div class="col-1 text-center">
                <input type="submit" name="envoi" value="Envoi" class="btn btn-primary" id="submit">
            </div>
            <div class="col-2 text-center">                        
            <button class="btn btn-warning" id="deco_button">Déconnecter</button>
            </div>
        </form>
        </div>
    <div>       
 </div>
</body>
</html>