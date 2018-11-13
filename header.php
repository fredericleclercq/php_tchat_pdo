<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tchat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="inc/style.css">
    <script>
        <?php 
        $result = $pdo->query("SELECT id_dialogue FROM dialogue ORDER BY id_dialogue DESC LIMIT 0,1");
        $donnees = $result->fetch(PDO::FETCH_ASSOC);
        ?>
        var lastid = <?= $donnees['id_dialogue'] ?? 0 ?>;
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="inc/ajax.js"></script>
</head>
<body>
<h1 class="text-center pt-3">Bienvenue dans mon T'chat</h1>
<div class="container pt-3">