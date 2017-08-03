<?php
// $Id: main.php 1243 2010-08-29 18:59:52Z kris_fr $
//%%%%%%		File Name index.php 		%%%%%
define('_MA_NW_PRINTER', 'Imprimer cette page');
define('_MA_NW_SENDSTORY', 'Transmettre cet article � un(e) ami(e)');
define('_MA_NW_READMORE', 'En savoir plus...');
define('_MA_NW_COMMENTS', 'Commentaires ?');
define('_MA_NW_ONECOMMENT', '1 comment');
define('_MA_NW_BYTESMORE', '%s bytes suppl�mentaires');
define('_MA_NW_NUMCOMMENTS', '%s commentaires');
define('_MA_NW_MORERELEASES', 'Plus de publications dans ');

//%%%%%%		File Name submit.php		%%%%%
define('_MA_NW_SUBMITNEWS', 'Proposer un article');
define('_MA_NW_TITLE', 'Titre');
define('_MA_NW_TOPIC', 'Cat�gorie');
define('_MA_NW_THESCOOP', 'Introduction');
define('_MA_NW_NOTIFYPUBLISH', "M'alerter par email lorsque l'article est publi�");
define('_MA_NW_POST', 'Transmettre');
define('_MA_NW_GO', 'Envoyer');
define('_MA_NW_THANKS', 'Merci pour votre contribution.'); //submission of news article

define('_MA_NW_NOTIFYSBJCT', "Nouvelle proposition d'article"); // Notification mail subject
define('_MA_NW_NOTIFYMSG', 'Un nouvel article a �t� propos� pour votre site.'); // Notification mail message

//%%%%%%		File Name archive.php		%%%%%
define('_MA_NW_NEWSARCHIVES', 'Archives');
define('_MA_NW_ARTICLES', 'Articles');
define('_MA_NW_VIEWS', 'Consultations');
define('_MA_NW_DATE', 'Date');
define('_MA_NW_ACTIONS', 'Actions');
define('_MA_NW_PRINTERFRIENDLY', 'Imprimer');

define('_MA_NW_THEREAREINTOTAL', 'Il y a %s article(s) publi�s');

// %s is your site name
define('_MA_NW_INTARTICLE', 'Article int�ressant � lire sur %s');
define('_MA_NW_INTARTFOUND', 'Voici un article dont je vous conseille la lecture : %s');

define('_MA_NW_TOPICC', 'Cat�gorie :');
define('_MA_NW_URL', 'URL :');
define('_MA_NW_NOSTORY', "L'article s�lectionn� n'existe pas.");

//%%%%%%	File Name print.php 	%%%%%

define('_MA_NW_URLFORSTORY', "L'url pour cet article est :");

// %s represents your site name
define('_MA_NW_THISCOMESFROM', 'Cet article provient de %s');

// Added by Herv�
define('_MA_NW_ATTACHEDFILES', 'Fichiers attach�s:');
define('_MA_NW_ATTACHEDLIB', 'Des fichiers attach�s sont disponibles avec cet article');
define('_MA_NW_NEWSSAMEAUTHORLINK', 'Articles du m�me auteur');
define('_MA_NW_NO_TOPICS', "Aucune cat�gorie n'a �t� trouv�e. Veuillez cr�er au moins une cat�gorie avant de proposer des articles.");
define('_MA_NW_PREVIOUS_ARTICLE', 'Article pr�c�dent');
define('_MA_NW_NEXT_ARTICLE', 'Article suivant');
define('_MA_NW_OTHER_ARTICLES', 'Autres articles');

// Added by Herv� in version 1.3 for rating
define('_MA_NW_RATETHISNEWS', 'Noter cet article');
define('_MA_NW_RATEIT', 'Noter');
define('_MA_NW_TOTALRATE', 'Total des votes');
define('_MA_NW_RATINGLTOH', 'Notation (de la note la plus basse � la plus �lev�e)');
define('_MA_NW_RATINGHTOL', 'Notation (de la note la plus �lev�e � la plus basse)');
define('_MA_NW_RATINGC', 'Evaluation: ');
define('_MA_NW_RATINGSCALE', "L'�chelle est de 1 � 10, 10 �tant la meilleure note possible.");
define('_MA_NW_BEOBJECTIVE', "Veuillez �valuer l'article de mani�re objective.");
define('_MA_NW_DONOTVOTE', 'Ne votez pas pour vos propres articles.');
define('_MA_NW_RATING', 'Evaluation');
define('_MA_NW_VOTE', 'Voter');
define('_MA_NW_NORATING', 'Aucune note s�lectionn�e.');
define('_MA_NW_USERAVG', "Notation moyenne de l'utilisateur");
define('_MA_NW_DLRATINGS', "Evalusation de l'article (total des votes: %s)");
define('_MA_NW_ONEVOTE', '1 vote');
define('_MA_NW_NUMVOTES', '%u votes');        // Warning
define('_MA_NW_CANTVOTEOWN', "Vous n'�tes pas autoris� � voter pour vos propres articles.<br>Toutes les �valuations sont enregistr�es.");
define('_MA_NW_VOTEDELETED', 'Vote supprim�.');
define('_MA_NW_VOTEONCE', 'Veuillez ne pas voter plusieurs fois pour un m�me article.');
define('_MA_NW_VOTEAPPRE', 'Merci pour votre �valusation.');
define('_MA_NW_THANKYOU', "Merci d'avoir pris le temps de voter sur %s"); // %s is your site name
define('_MA_NW_RSSFEED', 'Flux RSS'); // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MA_NW_AUTHOR', 'Auteur');
define('_MA_NW_META_DESCRIPTION', 'Meta description');
define('_MA_NW_META_KEYWORDS', 'Meta keywords');
define('_MA_NW_MAKEPDF', 'G�n�rer un PDF � partir de cet article');
define('_MA_NW_POSTEDON', 'Publi� le : ');
define('_MA_NW_AUTHOR_ID', "ID de l'auteur");
define('_MA_NW_POST_SORRY', "Soit aucune cat�gorie n'est disponible soit vous ne disposez pas des autorisation requises. Si vous �tes administrateur, veuillez v�rifier les autorisations.");

// Added in v 1.50
define('_MA_NW_LINKS', 'Liens');
define('_MA_NW_PAGE', 'Page');
define('_MA_NW_BOOKMARK_ME', 'Signets sociaux');
define('_AM_NW_TOTAL', 'Total sur %u articles');
define('_AM_NW_WHOS_WHO', 'Annuaire des auteurs');
define('_MA_NW_LIST_OF_AUTHORS', 'Liste des auteurs ayant contribu� sur le site : cliquer sur leur nom pour acc�der � leurs articles.');
define('_AM_NW_TOPICS_DIRECTORY', 'R�pertoire des cat�gories');
define('_MA_NW_PAGE_AUTO_SUMMARY', 'Page %d : %s');

// Added in version 1.51
define('_MA_NW_BOOKMARK_TO_BLINKLIST', 'Mettre en favoris sur Blinklist');
define('_MA_NW_BOOKMARK_TO_DELICIOUS', 'Mettre en favoris sur del.icio.us');
define('_MA_NW_BOOKMARK_TO_DIGG', 'Mettre en favoris sur Digg');
define('_MA_NW_BOOKMARK_TO_FARK', 'Mettre en favoris sur Fark');
define('_MA_NW_BOOKMARK_TO_FURL', 'Mettre en favoris sur Furl');
define('_MA_NW_BOOKMARK_TO_NEWSVINE', 'Mettre en favoris sur Newsvine');
define('_MA_NW_BOOKMARK_TO_REDDIT', 'Mettre en favoris sur Reddit');
define('_MA_NW_BOOKMARK_TO_SIMPY', 'Mettre en favoris surSimpy');
define('_MA_NW_BOOKMARK_TO_SPURL', 'Mettre en favoris sur Spurl');
define('_MA_NW_BOOKMARK_TO_YAHOO', 'Mettre en favoris sur Yahoo');

// Added in version 1.56
define('_MA_NW_NOTYETSTORY', "L'article s�lectionn� n'a pas encore �t� publi�. Veuillez r�essayer ult�rieurement.");
define('_MA_NW_SELECT_IMAGE', "S�lectionner une image � joindre � l'article.");
define('_MA_NW_CURENT_PICTURE', 'Image actuelle');

// Added in version 1.68 BETA
define('_MA_NW_SP', ':');
define('_MA_NW_POSTED', 'Publi�');

// Added in version 1.68 RC1
define('_MA_NW_NO_COMMENT', 'Aucun commentaire');
define('_MA_NW_METASIZE', "Vous ne devez pas ajouter plus de '+len+' caract�res dans le champs de saisie que vous venez de compl�ter.");

// Added in version 1.68 RC3
define('_MA_NW_SEO_TOPICS', 'Cat�gories');
define('_MA_NW_SEO_ARTICLES', 'Articles');
define('_MA_NW_SEO_PRINT', 'Impression');
define('_MA_NW_SEO_PDF', 'pdf');/**
 * @translation     Communaut� Francophone des Utilisateurs de Xoops
 * @specification   _LANGCODE: fr
 * @specification   _CHARSET: ISO-8859-1
 *
 * @version         $Id $
 **/;
