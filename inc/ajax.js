$(document).ready(function () {

    //initialisations diverses
    //var lastid = 0;
    "use strict";

    if ($('#message_tchat').length > 0) {

        var timer = setInterval(affichage_message, 5000);
        var timer_membre_connecte = setInterval(affichage_membre_connecte, 10000);
        $('#message_tchat').scrollTop($('#message_tchat')[0].scrollHeight); // met l'ascenceur tout en bas pour rendre visible le dernier message
        convertir_smiley();

    }



    function affichage_membre_connecte() {

        showLoader('#liste_membre_connecte');

        $.post('inc/ajax.php', 'action=affichage_membre_connecte', function (donnees) {
            if (donnees.validation == 'ok') {
                $("#liste_membre_connecte").empty().append(donnees.resultat);
                hideLoader();
            }
        }, 'json');
    }

    function convertir_smiley() {
        $('#message_tchat p').each(function () {

            $('#message_tchat').html($('#message_tchat').html().replace(':)', '<img src="smil/smiley1.png" class="smiley" alt="happy">'));
            $('#message_tchat').html($('#message_tchat').html().replace(':(', '<img src="smil/smiley2.png" class="smiley" alt="sad">'));
            $('#message_tchat').html($('#message_tchat').html().replace(';)', '<img src="smil/smiley3.png" class="smiley" alt="glance">'));
            $('#message_tchat').html($('#message_tchat').html().replace(':p', '<img src="smil/smiley4.png" class="smiley" alt="tongue">'));
            $('#message_tchat').html($('#message_tchat').html().replace(':x', '<img src="smil/smiley5.png" class="smiley" alt="heart">'));
            $('#message_tchat').html($('#message_tchat').html().replace(':/', '<img src="smil/smiley6.png" class="smiley" alt="angry">'));
        });
    }


    function affichage_message() {

        //showLoader('#message_tchat');

        $.post('inc/ajax.php', 'action=affichage_message&lastid=' + lastid, function (donnees) {

            $('#message_tchat').append(donnees.resultat);
            lastid = donnees.lastid;
            $('#message_tchat').scrollTop($('#message_tchat')[0].scrollHeight);
            convertir_smiley();
            // hideLoader();
        }, 'json')

    }

    // Insertion de message sur clic du bouton envoi
    $('#submit').on('click', function (event) {

        event.preventDefault();
        showLoader('#formulaire_tchat');
        clearInterval(timer);
        var message = $('#message').val();
        var parameters = 'message=' + message + '&action=envoi_message';
        $.post('inc/ajax.php', parameters, function (donnees) {
            if (donnees.validation == 'ok') {
                affichage_message();
                $('#message').val('');
                $('#message').focus();
            } else {
                alert("pb");
            }
            timer = setInterval(affichage_message, 5000);
            hideLoader();
        }, 'json');

    })

    $('#deco_button').on('click', function (event) {

        event.preventDefault();
        var parameters = 'action=deco';
        $.post('inc/ajax.php', parameters, function (donnees) {
            if (donnees.validation == 'ok') {
                location.reload();
            }
        }, 'json');

    });

    // insertion de smiley au clic
    $(".smiley").on('click', function (event) {
        var prevMsg = $("#message").val();
        var emotiText = $(event.target).attr("alt");
        $("#message").val(prevMsg + emotiText);
    });


    function showLoader(div) {
        $(div).append('<div class="loader"></div>');
        $('.loader').fadeTo(500, 0.6);

    }
    function hideLoader() {
        $('.loader').fadeOut(500, function () {
            $('.loader').remove();
        })
    }

    // --------------------------------------- DROP DE FICHIER AVATAR INSCRIPTION --------------------------------------------

    $('html')
        .on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("#depotavatar").css('border', '5px dashed orange');
        })
        .on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("#depotavatar").css('border', '');
        });


    $("#depotavatar")
        .on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
        })
        .on('dragleave', function (e) {
            e.stopPropagation();
            e.preventDefault();
        })
        .on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("#depotavatar").css('border', '');
            let fichiers = e.originalEvent.dataTransfer.files;
            checkFile(fichiers);
        });

    $('#avatar').on('change', function (e) {
        checkFile(e.target.files);
    });


    let liste = ['image/jpeg', 'image/png', 'image/gif'];
    function checkFile(fic) {



        if (fic.length > 0) {

            if (fic[0].size > 0 && fic[0].size < 2000000) {
                // controle format
                if ($.inArray(fic[0].type, liste) != -1) {

                    $('#avatar')[0].files = fic;   // ! Edge ! pb with strict mode

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let image = new Image();
                        image.src = e.target.result
                        $("#depotavatar").html('<img src="' + e.target.result + '" class="w-25 img-fluid">');
                        $(image).on('load', function () {

                            if (image.width > 1200 || image.height > 1200) {
                                $("#depotavatar").html('Format max non respecté 1200x1200 ');
                                $('#avatar').removeProp('files');
                            } else {
                                $("#depotavatar").append('<br>' + fic[0].type + ' ' + image.width + 'x' + image.height);
                            }
                        });
                    }
                    reader.readAsDataURL(fic[0]);

                } else {
                    $("#depotavatar").html('Format non reconnu' + fic[0].type);
                    $('#avatar').removeProp('files');
                }
            } else {
                taillefichier = fic[0].size || 0;
                $("#depotavatar").html('Taille maxi : 2Mo. Votre fichier fait ' + (taillefichier / 1000000).toFixed(2) + 'Mo');
                $('#avatar').removeProp('files');
            }
        }
    }

    if ($('#memoavatar').val() != '') {
        $("#depotavatar").html('<img src="avatars/' + $('#memoavatar').val() + '" class="w-25 img-fluid">');
    }


}); // fin du document ready