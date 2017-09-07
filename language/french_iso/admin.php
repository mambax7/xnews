<?php
// $Id: admin.php 1243 2010-08-29 18:59:52Z kris_fr $
//%%%%%%	Admin Module Name  Articles 	%%%%%
define('_AM_NW_DBUPDATED', 'La Base de donn�es a �t� mise � jour');
define('_AM_NW_CONFIG', 'Gestion du module');
define('_AM_NW_AUTOARTICLES', 'Articles � publication automatis�e');
define('_AM_NW_STORYID', "ID de l'article");
define('_AM_NW_TITLE', 'Titre');
define('_AM_NW_TOPIC', 'Cat�gorie');
define('_AM_NW_POSTER', 'Auteur');
define('_AM_NW_PROGRAMMED', 'Date / heure programm�e');
define('_AM_NW_ACTION', 'Action');
define('_AM_NW_EDIT', 'Modifier');
define('_AM_NW_DELETE', 'Effacer');
define('_AM_NW_LAST10ARTS', 'Les %d articles');
define('_AM_NW_PUBLISHED', 'Publi�'); // Published Date
define('_AM_NW_GO', 'Envoyer');
define('_AM_NW_EDITARTICLE', "Modifier l'article");
define('_AM_NW_POSTNEWARTICLE', 'Cr�er un nouvel article');
define('_AM_NW_ARTPUBLISHED', 'Votre article a �t� publi�');
define('_AM_NW_HELLO', 'Bonjour %s, ');
define('_AM_NW_YOURARTPUB', "L'article que vous avez propos� vient d'�tre publi� sur notre site.");
define('_AM_NW_TITLEC', 'Titre : ');
define('_AM_NW_URLC', 'URL : ');
define('_AM_NW_PUBLISHEDC', 'Publi� : ');
define('_AM_NW_RUSUREDEL', 'AVERTISSEMENT : �tes-vous certain de vouloir supprimer cet article ainsi que tous les commentaires associ�s ?');
define('_AM_NW_YES', 'Oui');
define('_AM_NW_NO', 'Non');
define('_AM_NW_INTROTEXT', "Texte d'introduction");
define('_AM_NW_EXTEXT', 'Article');
define('_AM_NW_ALLOWEDHTML', 'HTML autoris�:');
define('_AM_NW_DISAMILEY', 'Emoticones autoris�s:');
define('_AM_NW_DISHTML', 'HTML interdit:');
define('_AM_NW_APPROVE', 'Approuver');
define('_AM_NW_MOVETOTOP', 'D�placer cet article au sommet');
define('_AM_NW_CHANGEDATETIME', "Modifier la date / l'heure de publication");
define('_AM_NW_NOWSETTIME', 'La date / heure de publication est d�sormais fix�e � : %s'); // %s is datetime of publish
define('_AM_NW_CURRENTTIME', 'La date / heure actuelle est : %s');  // %s is the current datetime
define('_AM_NW_SETDATETIME', "Param�trer la date / l'heure de publication");
define('_AM_NW_MONTHC', 'Mois:');
define('_AM_NW_DAYC', 'Jour:');
define('_AM_NW_YEARC', 'Ann�e:');
define('_AM_NW_TIMEC', 'Heure:');
define('_AM_NW_PREVIEW', 'Pr�visualisation');
define('_AM_NW_SAVE', 'Sauvegarder');
define('_AM_NW_PUBINHOME', "Afficher en page d'accueil du module ?");
define('_AM_NW_ADD', 'Ajouter');

//%%%%%%	Admin Module Name  Topics 	%%%%%

define('_AM_NW_ADDMTOPIC', 'Ajouter une cat�gorie principale');
define('_AM_NW_TOPICNAME', 'Intitul� de la cat�gorie');
// Warning, changed from 40 to 255 characters.
define('_AM_NW_MAX40CHAR', '(max: 255 caract�res)');
define('_AM_NW_TOPICIMG', 'Illustration de la cat�gorie');
define('_AM_NW_IMGNAEXLOC', "l'illustration + extension sont situ�es dans %s");
define('_AM_NW_FEXAMPLE', 'Par exemple: games.gif');
define('_AM_NW_ADDSUBTOPIC', 'Ajouter une sous-cat�gorie');
define('_AM_NW_IN', 'dans');
define('_AM_NW_MODIFYTOPIC', 'Modifier la cat�gorie');
define('_AM_NW_MODIFY', 'Modifier');
define('_AM_NW_PARENTTOPIC', 'Cat�gorie parente');
define('_AM_NW_SAVECHANGE', 'Sauvegarder les modifications');
define('_AM_NW_DEL', 'Supprimer');
define('_AM_NW_CANCEL', 'Annuler');
define('_AM_NW_WAYSYWTDTTAL', 'AVERTISSEMENT : �tes-vous certain de vouloir supprimer cette cat�gorie ainsi que tous les articles contenus et les commentaires associ�s ?');

// Added in Beta6
define('_AM_NW_TOPICSMNGR', 'Gestion des cat�gories');
define('_AM_NW_PEARTICLES', 'Gestion des articles');
define('_AM_NW_NEWSUB', "Nouvelles propositions d'articles");
define('_AM_NW_POSTED', 'Publi�s');
define('_AM_NW_GENERALCONF', 'Configuration g�n�rale');

// Added in RC2
define('_AM_NW_TOPICDISPLAY', "Afficher l'illustration de la Cat�gorie ?");
define('_AM_NW_TOPICALIGN', 'Position');
define('_AM_NW_RIGHT', 'Droite');
define('_AM_NW_LEFT', 'Gauche');

define('_AM_NW_EXPARTS', 'Articles dont la date de diffusion a expir�');
define('_AM_NW_EXPIRED', 'Expir�s');
define('_AM_NW_CHANGEEXPDATETIME', "Modifier la date / l'heure d'expiration");
define('_AM_NW_SETEXPDATETIME', "Param�trer la date / l'heure d'expiration");
define('_AM_NW_NOWSETEXPTIME', "La date / l'heure est d�sormais fix�e � : %s");

// Added in RC3
define('_AM_NW_ERRORTOPICNAME', "L'intitul� de la cat�gorie est requis");
define('_AM_NW_EMPTYNODELETE', 'Aucune ressource ne peut �tre supprim�e');

// Added 240304 (Mithrandir)
define('_AM_NW_GROUPPERM', 'Permissions');
define('_AM_NW_SELFILE', 'S�lectionner le fichier � t�l�verser');

// Added by Herv�
define('_AM_NW_UPLOAD_DBERROR_SAVE', "Une erreur est survenue lors de l'ajout du fichier en Base de donn�es");
define('_AM_NW_UPLOAD_ERROR', 'Une erreur est survenur lors du t�l�versement du fichier');
define('_AM_NW_UPLOAD_ATTACHFILE', 'Fichier(s) attach�(s)');
define('_AM_NW_APPROVEFORM', 'Permission de g�rer la diffusion des articles');
define('_AM_NW_SUBMITFORM', 'Permission de proposer un nouvel article');
define('_AM_NW_VIEWFORM', "Permission de visualiser une cat�gorie d'articles");
define('_AM_NW_APPROVEFORM_DESC', "S�lectionner les groupes d'utilisateurs autoris�s � g�rer la diffusion des articles (publication, suspension, suppression)");
define('_AM_NW_SUBMITFORM_DESC', "S�lectionner les groupes d'utilisateurs autoris�s � proposer un nouvel article");
define('_AM_NW_VIEWFORM_DESC', "Pour chaque cat�gorie, s�lectionner les groupes d'utilisateurs autoris�s � visualiser les articles");
define('_AM_NW_DELETE_SELFILES', 'Supprimer les fichiers s�lectionn�s');
define('_AM_NW_TOPIC_PICTURE', 'T�l�verser une image');
define('_AM_NW_UPLOAD_WARNING', "AVERTISSEMENT. Ne pas oublier d'attribuer les droits d'�criture (CHMOD) aux dossiers suivants : %s");

define('_AM_NW_UPGRADECOMPLETE', 'Mise � jour termin�e');
define('_AM_NW_UPDATEMODULE', 'Mettre � jour les templates du module');
define('_AM_NW_UPGRADEFAILED', 'La mise � jour a �chou�');
define('_AM_NW_UPGRADE', 'Mettre � jour');
define('_AM_NW_ADD_TOPIC', 'Ajouter une cat�gorie');
define('_AM_NW_ADD_TOPIC_ERROR', 'Erreur : cette cat�gorie existe d�j�');
define('_AM_NW_ADD_TOPIC_ERROR1', 'Erreur : il est impossible de s�lectionner cette cat�gorie comme cat�gorie parente');
define('_AM_NW_SUB_MENU', 'Afficher cette cat�gorie dans le menu principal ?');
define('_AM_NW_SUB_MENU_YESNO', 'Sous-menu ?');
define('_AM_NW_HITS', 'Visualisations :');
define('_AM_NW_CREATED', 'Cr�� :');

define('_AM_NW_TOPIC_DESCR', 'Description de la cat�gorie');
define('_AM_NW_USERS_LIST', 'Liste des utilisateurs');
define('_AM_NW_PUBLISH_FRONTPAGE', "Publier en page d'accueil du module ?");
define('_AM_NW_UPGRADEFAILED1', 'Impossible de cr�er la table stories_files');
define('_AM_NW_UPGRADEFAILED2', 'Impossible de modifier la longueur du titre de la cat�gorie');
define('_AM_NW_UPGRADEFAILED21', "Impossible d'ajouter de nouveaux champs dans la table des cat�gories");
define('_AM_NW_UPGRADEFAILED3', 'Impossible de cr�er la table stories_votedata');
define('_AM_NW_UPGRADEFAILED4', "Impossible de cr�er les deux champs 'rating' et 'votes' dans la tables des articles");
define('_AM_NW_UPGRADEFAILED0', "Veuillez prendre note du message d'erreur et essayez de corriger le probl�me en utilisant phpMyAdmin et les instructions disponibles dans le dossier /sql du module News");
define('_AM_NW_UPGR_ACCESS_ERROR', "Erreur : l'utilisation du script de mise � jour requiert des droits d'administration complets");
define('_AM_NW_PRUNE_BEFORE', 'Supprimer les articles publi�s avant');
define('_AM_NW_PRUNE_EXPIREDONLY', 'Supprimer uniquement les articles dont la date de diffusion a expir�');
define('_AM_NW_PRUNE_CONFIRM', 'AVERTISSEMENT : vous �tes sur le point de supprimer d�finitivement les articles dont la date de publication est ant�rieure � : %s. %s articles seront supprim�s.<br>Etes-vous certain de vouloir supprimer d�finitivement ces articles ?');
define('_AM_NW_PRUNE_TOPICS', 'Limiter aux cat�gories suivantes');
define('_AM_NW_PRUNENEWS', 'Suppression par lots');
define('_AM_NW_EXPORT_NEWS', 'Export des articles (XML)');
define('_AM_NW_EXPORT_NOTHING', "Aucune donn�e n'a �t� trouv�e pour l'export. Veuillez v�rifier votre param�trage.");
define('_AM_NW_PRUNE_DELETED', '%d articles ont �t� supprim�s');
define('_AM_NW_PERM_WARNING', '<h2>AVERTISSEMENT : vous disposez de trois formulaires donc de trois boutons de validation</h2>');
define('_AM_NW_EXPORT_BETWEEN', 'Exporter les articles publi� entre');
define('_AM_NW_EXPORT_AND', ' et ');
define('_AM_NW_EXPORT_PRUNE_DSC', "Si aucune cat�gorie n'est s�lectionn�e, toutes les cat�gories seront concern�es");
define('_AM_NW_EXPORT_INCTOPICS', 'Inclure les descriptions de cat�gories ?');
define('_AM_NW_EXPORT_ERROR', "Une erreur est survenue lors de la cr�ation du fichier %s. L'op�ration a �t� arr�t�e.");
define('_AM_NW_EXPORT_READY', "Le fichier XML est pr�t pour le t�l�chargement.<br><a href='%s'>Cliquer sur ce lien pour le t�l�charger</a>.<br>Ne pas oublier de <a href='%s'>le supprimer</a> une fois le t�l�chargement effectu�.");
define('_AM_NW_RSS_URL', 'URL du flux RSS');
define('_AM_NW_NEWSLETTER', 'Newsletter');
define('_AM_NW_NEWSLETTER_BETWEEN', 'S�lectionner les articles publi�s entre');
define('_AM_NW_NEWSLETTER_READY', "Le fichier pour votre Newsletter est pr�t pour le tl�chargement.<br><a href='%s'>Cliquer sur ce lien pour le t�l�charger</a>.<br>Ne pas oublier de <a href='%s'>le supprimer</a> une fois le t�l�chargement effectu�.");
define('_AM_NW_DELETED_OK', 'Fichier supprim�');
define('_AM_NW_DELETED_PB', 'Une erreur est survenue lors de la suppression du fichier');
define('_AM_NW_STATS0', 'Statistiques des cat�gories');
define('_AM_NW_STATS', 'Statistiques');
define('_AM_NW_STATS1', 'Auteurs uniques');
define('_AM_NW_STATS2', 'Totaux');
define('_AM_NW_STATS3', 'Statistiques des articles');
define('_AM_NW_STATS4', 'Articles les plus consult�s');
define('_AM_NW_STATS5', 'Articles les moins consult�s');
define('_AM_NW_STATS6', 'Articles les mieux not�s');
define('_AM_NW_STATS7', 'Auteurs les plus lus');
define('_AM_NW_STATS8', 'Auteurs les mieux not�s');
define('_AM_NW_STATS9', 'Contributeurs les plus actifs');
define('_AM_NW_STATS10', 'Statistiques des auteurs');
define('_AM_NW_STATS11', 'D�compte des articles');
define('_AM_NW_HELP', 'Aide');
define('_AM_NW_MODULEADMIN', " - Interface d'administration");
define('_AM_NW_GENERALSET', 'Pr�f�rences');
define('_AM_NW_GOTOMOD', 'Afficher le module c�t� public');
define('_AM_NW_NOTHING', 'Aucune ressource ne peut �tre t�l�charg�e. Veuillez v�rifier votre param�trage.');
define('_AM_NW_NOTHING_PRUNE', "Aucun article ne correspond � vos crit�res, aucune suppression n'a �t� r�alis�e.");
define('_AM_NW_TOPIC_COLOR', 'Couleur de la cat�gorie');
define('_AM_NW_COLOR', 'Couleur');
define('_AM_NW_REMOVE_BR', 'Convertir la balise Html &lt;br&gt; en une nouvelle ligne ?');
// Added in 1.3 RC2
define('_AM_NW_PLEASE_UPGRADE', "<a href='upgrade.php'><font color='#FF0000'>Veuillez mettre � jour le module</font></a>");

// Added in verisn 1.50
define('_AM_NW_NEWSLETTER_HEADER', 'En-t�te');
define('_AM_NW_NEWSLETTER_FOOTER', 'Pied de page');
define('_AM_NW_NEWSLETTER_HTML_TAGS', 'Supprimer les balises Html ?');
define('_AM_NW_VERIFY_TABLES', 'Maintenir les tables');
define('_AM_NW_METAGEN', 'Metagen');
define('_AM_NW_METAGEN_DESC', 'Metagen vous permet de g�n�rer automatiquement les balises meta associ�es � chaque article.<br>A moins que vous ne pr�f�riez les saisir vous-m�me, le module les ins�rera lui-m�me.');
define('_AM_NW_BLACKLIST', "Liste d'exclusion");
define('_AM_NW_BLACKLIST_DESC', 'Les termes pr�sents dans cette liste seront bannis des mots-cl�s utilis�s par metagen');
define('_AM_NW_BLACKLIST_ADD', 'Ajouter');
define('_AM_NW_BLACKLIST_ADD_DSC', 'Saisir les termes � bannir<br>(un terme par ligne)');
define('_AM_NW_META_KEYWORDS_CNT', 'Nombre maximum de mots-cl�s � g�n�rer automatiquement');
define('_AM_NW_META_KEYWORDS_ORDER', 'Classement des mots-cl�s');
define('_AM_NW_META_KEYWORDS_INTEXT', "Classer les mots-cl�s conform�ment � leur ordre d'apparition dans l'article");
define('_AM_NW_META_KEYWORDS_FREQ1', "Classer les mots-cl�s en fonction de leur r�currence dans l'article");
define('_AM_NW_META_KEYWORDS_FREQ2', "Classer les mots-cl�s dans l'ordre inverse de leur r�currence dans l'article");

// Added in version 1.67 Beta
define('_AM_XNEWS_SUBPREFIX', 'Sous-pr�fixe');

define('_AM_NW_CLONER', 'Clonage');
define('_AM_NW_CLONER_CLONES', 'Clones');
define('_AM_NW_CLONER_ADD', 'Ajouter un Clone');
define('_AM_NW_CLONER_ID', 'ID');
define('_AM_NW_CLONER_NAME', 'Nom');
define('_AM_NW_CLONER_DIRFOL', 'R�pertoire');
define('_AM_NW_CLONER_VERSION', 'Version');

define('_AM_NW_CLONER_NEWNAME', 'Nom du nouveau module');
define(
    '_AM_NW_CLONER_NEWNAMEDESC',
       "Cette information affecte �galement le nom du r�pertoire du module. <br>La saisie est insensible � la casse et les espaces sont automatiquement supprim�s.<br><br>Exemples :<br><strong>Library</strong> devient <strong>library</strong> dans le r�pertoire des modules<br><strong>My Library</strong> devient <strong>mylibrary</strong> dans le r�pertoire des modules<br><br> Le module d'origine est : <font color='#008400'><strong> %s </strong></font><br>"
);
define('_AM_NW_CLONER_NEWNAMELABEL', 'Nouveau module :');

define('_AM_NW_CLONER_DIREXISTS', "Le r�pertoire '%s' existe d�j�");
define('_AM_NW_CLONER_CREATED', "Le module '%s' a �t� clon�");
define('_AM_NW_CLONER_UPRADED', "Le module '%s' a �t� mis � jour");
define('_AM_NW_CLONER_NOMODULEID', "L'ID du module n'a pas �t� d�fini");

define('_AM_NW_CLONER_UPDATE', 'Mise � jour');
define('_AM_NW_CLONER_INSTALL', 'Installation');
define('_AM_NW_CLONER_UNINSTALL', 'D�sinstallation');
define('_AM_NW_CLONER_ACTION_INSTALL', 'Installer / d�sinstaller');

define('_AM_NW_CLONER_IMPORTNEWS', 'Importer les donn�es depuis le module News');
define('_AM_NW_CLONER_IMPORTNEWSDESC1', 'Le module News a �t� trouv�. Voulez-vous importer les donn�es ?');
define('_AM_NW_CLONER_IMPORTNEWSDESC2', "Le bouton d'import n'est disponible que si la table des artciles du module xNews est vierge.<br>
                                         Si vous avez d�j� cr�� des articles dans xNews<br>
                                         veuillez le d�sinstaller compl�tement et le r�installer.<br>
                                         Si vous avez d�j� import� des donn�es depuis le module News, un nouvel import est impossible.");
define('_AM_NW_CLONER_IMPORTNEWSSUB', 'Importer');
define('_AM_NW_CLONER_NEWSIMPORTED', 'Les donn�es du module News ont �t� import�es.');

// Added in version 1.68 Beta
define('_AM_NW_DESCRIPTION', "<h3>xNews est une version clonable du module News</h3> 
							  Les utilisateur peuvent poster des articles et les commenter.<br>Le module peut �tre clon� et ainsi couvrir tous vos besoins en mati�re de gestion d'articles.");

// Added in version 1.68 RC1
define('_AM_NW_CLONER_CLONEDELETED', "Le clone '%s' a �t� supprim�.");
define('_AM_NW_CLONER_CLONEDELETEDERR', "Le clone '%s' ne peut pas �tre supprim�. Veuillez v�rifier les permissions.");
define('_AM_NW_CLONER_CLONEUPGRADED', 'Mis � jour');
define('_AM_NW_CLONER_UPGRADEFORCE', 'Forcer la mise � jour');
define('_AM_NW_CLONER_CLONEDELETION', 'Suppression du clone');
define('_AM_NW_CLONER_SUREDELETE', "AVERTISSEMENT : �tes-vous certain de vouloir effacer  le clone <font color='#000000'>'%s'</font> ?<br>");
define('_AM_NW_CLONER_CLONEID', "L'ID du clone ID n'a pas �t� d�fini");

// Added in version 1.68 RC2
define('_AM_NW_INDEX', 'Index');

// Added in version 1.68 RC3
define('_AM_NW_DOLINEBREAK', 'Activer le saut de ligne');

define('_AM_NW_TOPICS', 'Cat�gories');/**
 * @translation     Communaut� Francophone des Utilisateurs de Xoops
 * @specification   _LANGCODE: fr
 * @specification   _CHARSET: ISO-8859-1
 *
 * @version         $Id $
 **/;
