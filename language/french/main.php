<?php
// $Id: main.php 1243 2010-08-29 18:59:52Z kris_fr $
//%%%%%%        File Name index.php         %%%%%
define('_MD_XNEWS_PRINTER', 'Imprimer cette page');
define('_MD_XNEWS_SENDSTORY', 'Transmettre cet article à un(e) ami(e)');
define('_MD_XNEWS_READMORE', 'En savoir plus...');
define('_MD_XNEWS_COMMENTS', 'Commentaires ?');
define('_MD_XNEWS_ONECOMMENT', '1 comment');
define('_MD_XNEWS_BYTESMORE', '%s bytes supplémentaires');
define('_MD_XNEWS_NUMCOMMENTS', '%s commentaires');
define('_MD_XNEWS_MORERELEASES', 'Plus de publications dans ');

//%%%%%%        File Name submit.php        %%%%%
define('_MD_XNEWS_SUBMITNEWS', 'Proposer un article');
define('_MD_XNEWS_TITLE', 'Titre');
define('_MD_XNEWS_TOPIC', 'Catégorie');
define('_MD_XNEWS_THESCOOP', 'Introduction');
define('_MD_XNEWS_NOTIFYPUBLISH', "M'alerter par email lorsque l'article est publié");
define('_MD_XNEWS_POST', 'Transmettre');
define('_MD_XNEWS_GO', 'Envoyer');
define('_MD_XNEWS_THANKS', 'Merci pour votre contribution.'); //submission of news article

define('_MD_XNEWS_NOTIFYSBJCT', "Nouvelle proposition d'article"); // Notification mail subject
define('_MD_XNEWS_NOTIFYMSG', 'Un nouvel article a été proposé pour votre site.'); // Notification mail message

//%%%%%%        File Name archive.php        %%%%%
define('_MD_XNEWS_NEWSARCHIVES', 'Archives');
define('_MD_XNEWS_ARTICLES', 'Articles');
define('_MD_XNEWS_VIEWS', 'Consultations');
define('_MD_XNEWS_DATE', 'Date');
define('_MD_XNEWS_ACTIONS', 'Actions');
define('_MD_XNEWS_PRINTERFRIENDLY', 'Imprimer');

define('_MD_XNEWS_THEREAREINTOTAL', 'Il y a %s article(s) publiés');

// %s is your site name
define('_MD_XNEWS_INTARTICLE', 'Article intéressant à lire sur %s');
define('_MD_XNEWS_INTARTFOUND', 'Voici un article dont je vous conseille la lecture : %s');

define('_MD_XNEWS_TOPICC', 'Catégorie :');
define('_MD_XNEWS_URL', 'URL :');
define('_MD_XNEWS_NOSTORY', "L'article sélectionné n'existe pas.");

//%%%%%%    File Name print.php     %%%%%

define('_MD_XNEWS_URLFORSTORY', "L'url pour cet article est :");

// %s represents your site name
define('_MD_XNEWS_THISCOMESFROM', 'Cet article provient de %s');

// Added by Hervé
define('_MD_XNEWS_ATTACHEDFILES', 'Fichiers attachés:');
define('_MD_XNEWS_ATTACHEDLIB', 'Des fichiers attachés sont disponibles avec cet article');
define('_MD_XNEWS_NEWSSAMEAUTHORLINK', 'Articles du même auteur');
define('_MD_XNEWS_NO_TOPICS', "Aucune catégorie n'a été trouvée. Veuillez créer au moins une catégorie avant de proposer des articles.");
define('_MD_XNEWS_PREVIOUS_ARTICLE', 'Article précédent');
define('_MD_XNEWS_NEXT_ARTICLE', 'Article suivant');
define('_MD_XNEWS_OTHER_ARTICLES', 'Autres articles');

// Added by Hervé in version 1.3 for rating
define('_MD_XNEWS_RATETHISNEWS', 'Noter cet article');
define('_MD_XNEWS_RATEIT', 'Noter');
define('_MD_XNEWS_TOTALRATE', 'Total des votes');
define('_MD_XNEWS_RATINGLTOH', 'Notation (de la note la plus basse à la plus élevée)');
define('_MD_XNEWS_RATINGHTOL', 'Notation (de la note la plus élevée à la plus basse)');
define('_MD_XNEWS_RATINGC', 'Evaluation: ');
define('_MD_XNEWS_RATINGSCALE', "L'échelle est de 1 à 10, 10 étant la meilleure note possible.");
define('_MD_XNEWS_BEOBJECTIVE', "Veuillez évaluer l'article de manière objective.");
define('_MD_XNEWS_DONOTVOTE', 'Ne votez pas pour vos propres articles.');
define('_MD_XNEWS_RATING', 'Evaluation');
define('_MD_XNEWS_VOTE', 'Voter');
define('_MD_XNEWS_NORATING', 'Aucune note sélectionnée.');
define('_MD_XNEWS_USERAVG', "Notation moyenne de l'utilisateur");
define('_MD_XNEWS_DLRATINGS', "Evalusation de l'article (total des votes: %s)");
define('_MD_XNEWS_ONEVOTE', '1 vote');
define('_MD_XNEWS_NUMVOTES', '%u votes');        // Warning
define('_MD_XNEWS_CANTVOTEOWN', "Vous n'êtes pas autorisé à voter pour vos propres articles.<br>Toutes les évaluations sont enregistrées.");
define('_MD_XNEWS_VOTEDELETED', 'Vote supprimé.');
define('_MD_XNEWS_VOTEONCE', 'Veuillez ne pas voter plusieurs fois pour un même article.');
define('_MD_XNEWS_VOTEAPPRE', 'Merci pour votre évalusation.');
define('_MD_XNEWS_THANKYOU', "Merci d'avoir pris le temps de voter sur %s"); // %s is your site name
define('_MD_XNEWS_RSSFEED', 'Flux RSS'); // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MD_XNEWS_AUTHOR', 'Auteur');
define('_MD_XNEWS_META_DESCRIPTION', 'Meta description');
define('_MD_XNEWS_META_KEYWORDS', 'Meta keywords');
define('_MD_XNEWS_MAKEPDF', 'Générer un PDF à partir de cet article');
define('_MD_XNEWS_POSTEDON', 'Publié le : ');
define('_MD_XNEWS_AUTHOR_ID', "ID de l'auteur");
define('_MD_XNEWS_POST_SORRY', "Soit aucune catégorie n'est disponible soit vous ne disposez pas des autorisation requises. Si vous êtes administrateur, veuillez vérifier les autorisations.");

// Added in v 1.50
define('_MD_XNEWS_LINKS', 'Liens');
define('_MD_XNEWS_PAGE', 'Page');
define('_MD_XNEWS_BOOKMARK_ME', 'Signets sociaux');
define('_AM_XNEWS_TOTAL', 'Total sur %u articles');
define('_AM_XNEWS_WHOS_WHO', 'Annuaire des auteurs');
define('_MD_XNEWS_LIST_OF_AUTHORS', 'Liste des auteurs ayant contribué sur le site : cliquer sur leur nom pour accéder à leurs articles.');
define('_AM_XNEWS_TOPICS_DIRECTORY', 'Répertoire des catégories');
define('_MD_XNEWS_PAGE_AUTO_SUMMARY', 'Page %d : %s');

// Added in version 1.51
define('_MD_XNEWS_BOOKMARK_TO_BLINKLIST', 'Mettre en favoris sur Blinklist');
define('_MD_XNEWS_BOOKMARK_TO_DELICIOUS', 'Mettre en favoris sur del.icio.us');
define('_MD_XNEWS_BOOKMARK_TO_DIGG', 'Mettre en favoris sur Digg');
define('_MD_XNEWS_BOOKMARK_TO_FARK', 'Mettre en favoris sur Fark');
define('_MD_XNEWS_BOOKMARK_TO_FURL', 'Mettre en favoris sur Furl');
define('_MD_XNEWS_BOOKMARK_TO_NEWSVINE', 'Mettre en favoris sur Newsvine');
define('_MD_XNEWS_BOOKMARK_TO_REDDIT', 'Mettre en favoris sur Reddit');
define('_MD_XNEWS_BOOKMARK_TO_SIMPY', 'Mettre en favoris surSimpy');
define('_MD_XNEWS_BOOKMARK_TO_SPURL', 'Mettre en favoris sur Spurl');
define('_MD_XNEWS_BOOKMARK_TO_YAHOO', 'Mettre en favoris sur Yahoo');

// Added in version 1.56
define('_MD_XNEWS_NOTYETSTORY', "L'article sélectionné n'a pas encore été publié. Veuillez réessayer ultérieurement.");
define('_MD_XNEWS_SELECT_IMAGE', "Sélectionner une image à joindre à l'article.");
define('_MD_XNEWS_CURENT_PICTURE', 'Image actuelle');

// Added in version 1.68 BETA
define('_MD_XNEWS_SP', ':');
define('_MD_XNEWS_POSTED', 'Publié');

// Added in version 1.68 RC1
define('_MD_XNEWS_NO_COMMENT', 'Aucun commentaire');
define('_MD_XNEWS_METASIZE', "Vous ne devez pas ajouter plus de '+len+' caractères dans le champs de saisie que vous venez de compléter.");

// Added in version 1.68 RC3
define('_MD_XNEWS_SEO_TOPICS', 'Catégories');
define('_MD_XNEWS_SEO_ARTICLES', 'Articles');
define('_MD_XNEWS_SEO_PRINT', 'Impression');
define('_MD_XNEWS_SEO_PDF', 'pdf');/**
 * @translation     Communauté Francophone des Utilisateurs de Xoops
 * @specification   _LANGCODE: fr
 * @specification   _CHARSET: UTF-8 sans Bom
 *
 * @version         $Id $
 **/;
