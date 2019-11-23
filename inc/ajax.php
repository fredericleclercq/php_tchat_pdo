<?php

require_once('init.php');
extract($_POST);

$tab=array();

if ( $action == 'envoi_message')
{
    //insertion
    //$message = addslashes($message);
    $message = htmlspecialchars($message,ENT_NOQUOTES);

    if ( !empty($message) ){
        // insère le message
        $result=$pdo->prepare("INSERT INTO dialogue VALUES (NULL,:id_membre,:message,NOW())");
        $result->execute(array(
            'id_membre' => $_SESSION['id_membre'],
            'message' => $message
        ));
        // mise à jour du timestamp de l'activité
        $result=$pdo->prepare("UPDATE membre SET date_active=".time()." WHERE id_membre=:id_membre");
        $result->execute(array('id_membre' => $_SESSION['id_membre']));
    }

    $tab['validation']='ok';

}

if ( $action == 'affichage_message')
{
    $lastid=floor($lastid); // methode de forçage au type INTeger
    $result = $pdo->prepare("SELECT 
    d.id_dialogue,m.pseudo,m.civilite,d.message,
    DATE_FORMAT(d.date,'%d/%m/%Y') as datefr,
    DATE_FORMAT(d.date, '%H:%i:%s') as heurefr
    FROM dialogue d, membre m
    WHERE m.id_membre = d.id_membre 
    AND d.id_dialogue > :lastid
    ORDER BY d.date");
    $result->execute(array('lastid' => $lastid));
    $tab['resultat']='';
    $tab['lastid'] = $lastid;
    while ( $dialogue = $result->fetch(PDO::FETCH_ASSOC) )
    {
        if ($dialogue['civilite'] == 'm' ) { $couleur="bleu";}else{$couleur="rose";}

        $tab['resultat'] .=  '<p title="'.$dialogue['datefr'].'-'.$dialogue['heurefr'].'" class="'.$couleur.'">'.$dialogue['heurefr'].' <strong>'.ucfirst($dialogue['pseudo']).'</strong> : '. htmlspecialchars($dialogue['message'],ENT_NOQUOTES).'</p>';

        $tab['lastid'] = $dialogue['id_dialogue'];
    }
    $tab['validation'] = 'ok';
}

if ( $action == 'affichage_membre_connecte')
{
    $resultat=$pdo->query("SELECT * FROM membre WHERE date_active >".(time()-1800)." ORDER BY pseudo");
    $tab['resultat'] = '<h5>Membres connectés</h5>';
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
        $tab['resultat'] .= '<p class="'.$couleur.'" 
        title="'.$titre.', '.$membre['ville'].', '.age($membre['date_de_naissance']).' ans">'.ucfirst($membre['pseudo']).'</p>';
    }
    $tab['validation']= 'ok';
}

if ( $action == 'deco'){
    $result=$pdo->prepare("UPDATE membre SET date_active=0 WHERE id_membre=:id_membre");
    $result->execute(array('id_membre' => $_SESSION['id_membre']));
    session_destroy();
    $tab['validation']= 'ok';
}

echo json_encode($tab);
