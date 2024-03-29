<?php
// $Id: admin.php 1243 2010-08-29 18:59:52Z kris_fr $
//%%%%%%    Admin Module Name  Articles     %%%%%
define('_AM_XNEWS_DBUPDATED', 'La Base de données a été mise à jour');
define('_AM_XNEWS_CONFIG', 'Gestion du module');
define('_AM_XNEWS_AUTOARTICLES', 'Articles à publication automatisée');
define('_AM_XNEWS_STORYID', "ID de l'article");
define('_AM_XNEWS_TITLE', 'Titre');
define('_AM_XNEWS_TOPIC', 'Catégorie');
define('_AM_XNEWS_POSTER', 'Auteur');
define('_AM_XNEWS_PROGRAMMED', 'Date / heure programmée');
define('_AM_XNEWS_ACTION', 'Action');
define('_AM_XNEWS_EDIT', 'Modifier');
define('_AM_XNEWS_DELETE', 'Effacer');
define('_AM_XNEWS_LAST10ARTS', 'Les %d articles');
define('_AM_XNEWS_PUBLISHED', 'Publié'); // Published Date
define('_AM_XNEWS_GO', 'Envoyer');
define('_AM_XNEWS_EDITARTICLE', "Modifier l'article");
define('_AM_XNEWS_POSTNEWARTICLE', 'Créer un nouvel article');
define('_AM_XNEWS_ARTPUBLISHED', 'Votre article a été publié');
define('_AM_XNEWS_HELLO', 'Bonjour %s, ');
define('_AM_XNEWS_YOURARTPUB', "L'article que vous avez proposé vient d'être publié sur notre site.");
define('_AM_XNEWS_TITLEC', 'Titre : ');
define('_AM_XNEWS_URLC', 'URL : ');
define('_AM_XNEWS_PUBLISHEDC', 'Publié : ');
define('_AM_XNEWS_RUSUREDEL', 'AVERTISSEMENT : êtes-vous certain de vouloir supprimer cet article ainsi que tous les commentaires associés ?');
define('_AM_XNEWS_YES', 'Oui');
define('_AM_XNEWS_NO', 'Non');
define('_AM_XNEWS_INTROTEXT', "Texte d'introduction");
define('_AM_XNEWS_EXTEXT', 'Article');
define('_AM_XNEWS_ALLOWEDHTML', 'HTML autorisé:');
define('_AM_XNEWS_DISAMILEY', 'Emoticones autorisés:');
define('_AM_XNEWS_DISHTML', 'HTML interdit:');
define('_AM_XNEWS_APPROVE', 'Approuver');
define('_AM_XNEWS_MOVETOTOP', 'Déplacer cet article au sommet');
define('_AM_XNEWS_CHANGEDATETIME', "Modifier la date / l'heure de publication");
define('_AM_XNEWS_NOWSETTIME', 'La date / heure de publication est désormais fixée à : %s'); // %s is datetime of publish
define('_AM_XNEWS_CURRENTTIME', 'La date / heure actuelle est : %s');  // %s is the current datetime
define('_AM_XNEWS_SETDATETIME', "Paramétrer la date / l'heure de publication");
define('_AM_XNEWS_MONTHC', 'Mois:');
define('_AM_XNEWS_DAYC', 'Jour:');
define('_AM_XNEWS_YEARC', 'Année:');
define('_AM_XNEWS_TIMEC', 'Heure:');
define('_AM_XNEWS_PREVIEW', 'Prévisualisation');
define('_AM_XNEWS_SAVE', 'Sauvegarder');
define('_AM_XNEWS_PUBINHOME', "Afficher en page d'accueil du module ?");
define('_AM_XNEWS_ADD', 'Ajouter');

//%%%%%%    Admin Module Name  Topics   %%%%%

define('_AM_XNEWS_ADDMTOPIC', 'Ajouter une catégorie principale');
define('_AM_XNEWS_TOPICNAME', 'Intitulé de la catégorie');
// Warning, changed from 40 to 255 characters.
define('_AM_XNEWS_MAX40CHAR', '(max: 255 caractères)');
define('_AM_XNEWS_TOPICIMG', 'Illustration de la catégorie');
define('_AM_XNEWS_IMGNAEXLOC', "l'illustration + extension sont situées dans %s");
define('_AM_XNEWS_FEXAMPLE', 'Par exemple: games.gif');
define('_AM_XNEWS_ADDSUBTOPIC', 'Ajouter une sous-catégorie');
define('_AM_XNEWS_IN', 'dans');
define('_AM_XNEWS_MODIFYTOPIC', 'Modifier la catégorie');
define('_AM_XNEWS_MODIFY', 'Modifier');
define('_AM_XNEWS_PARENTTOPIC', 'Catégorie parente');
define('_AM_XNEWS_SAVECHANGE', 'Sauvegarder les modifications');
define('_AM_XNEWS_DEL', 'Supprimer');
define('_AM_XNEWS_CANCEL', 'Annuler');
define('_AM_XNEWS_WAYSYWTDTTAL', 'AVERTISSEMENT : êtes-vous certain de vouloir supprimer cette catégorie ainsi que tous les articles contenus et les commentaires associés ?');

// Added in Beta6
define('_AM_XNEWS_TOPICSMNGR', 'Gestion des catégories');
define('_AM_XNEWS_PEARTICLES', 'Gestion des articles');
define('_AM_XNEWS_NEWSUB', "Nouvelles propositions d'articles");
define('_AM_XNEWS_POSTED', 'Publiés');
define('_AM_XNEWS_GENERALCONF', 'Configuration générale');

// Added in RC2
define('_AM_XNEWS_TOPICDISPLAY', "Afficher l'illustration de la Catégorie ?");
define('_AM_XNEWS_TOPICALIGN', 'Position');
define('_AM_XNEWS_RIGHT', 'Droite');
define('_AM_XNEWS_LEFT', 'Gauche');

define('_AM_XNEWS_EXPARTS', 'Articles dont la date de diffusion a expiré');
define('_AM_XNEWS_EXPIRED', 'Expirés');
define('_AM_XNEWS_CHANGEEXPDATETIME', "Modifier la date / l'heure d'expiration");
define('_AM_XNEWS_SETEXPDATETIME', "Paramétrer la date / l'heure d'expiration");
define('_AM_XNEWS_NOWSETEXPTIME', "La date / l'heure est désormais fixée à : %s");

// Added in RC3
define('_AM_XNEWS_ERRORTOPICNAME', "L'intitulé de la catégorie est requis");
define('_AM_XNEWS_EMPTYNODELETE', 'Aucune ressource ne peut être supprimée');

// Added 240304 (Mithrandir)
define('_AM_XNEWS_GROUPPERM', 'Permissions');
define('_AM_XNEWS_SELFILE', 'Sélectionner le fichier à téléverser');

// Added by Hervé
define('_AM_XNEWS_UPLOAD_DBERROR_SAVE', "Une erreur est survenue lors de l'ajout du fichier en Base de données");
define('_AM_XNEWS_UPLOAD_ERROR', 'Une erreur est survenur lors du téléversement du fichier');
define('_AM_XNEWS_UPLOAD_ATTACHFILE', 'Fichier(s) attaché(s)');
define('_AM_XNEWS_APPROVEFORM', 'Permission de gérer la diffusion des articles');
define('_AM_XNEWS_SUBMITFORM', 'Permission de proposer un nouvel article');
define('_AM_XNEWS_VIEWFORM', "Permission de visualiser une catégorie d'articles");
define('_AM_XNEWS_APPROVEFORM_DESC', "Sélectionner les groupes d'utilisateurs autorisés à gérer la diffusion des articles (publication, suspension, suppression)");
define('_AM_XNEWS_SUBMITFORM_DESC', "Sélectionner les groupes d'utilisateurs autorisés à proposer un nouvel article");
define('_AM_XNEWS_VIEWFORM_DESC', "Pour chaque catégorie, sélectionner les groupes d'utilisateurs autorisés à visualiser les articles");
define('_AM_XNEWS_DELETE_SELFILES', 'Supprimer les fichiers sélectionnés');
define('_AM_XNEWS_TOPIC_PICTURE', 'Téléverser une image');
define('_AM_XNEWS_UPLOAD_WARNING', "AVERTISSEMENT. Ne pas oublier d'attribuer les droits d'écriture (CHMOD) aux dossiers suivants : %s");

define('_AM_XNEWS_UPGRADECOMPLETE', 'Mise à jour terminée');
define('_AM_XNEWS_UPDATEMODULE', 'Mettre à jour les templates du module');
define('_AM_XNEWS_UPGRADEFAILED', 'La mise à jour a échoué');
define('_AM_XNEWS_UPGRADE', 'Mettre à jour');
define('_AM_XNEWS_ADD_TOPIC', 'Ajouter une catégorie');
define('_AM_XNEWS_ADD_TOPIC_ERROR', 'Erreur : cette catégorie existe déjà');
define('_AM_XNEWS_ADD_TOPIC_ERROR1', 'Erreur : il est impossible de sélectionner cette catégorie comme catégorie parente');
define('_AM_XNEWS_SUB_MENU', 'Afficher cette catégorie dans le menu principal ?');
define('_AM_XNEWS_SUB_MENU_YESNO', 'Sous-menu ?');
define('_AM_XNEWS_HITS', 'Visualisations :');
define('_AM_XNEWS_CREATED', 'Créé :');

define('_AM_XNEWS_TOPIC_DESCR', 'Description de la catégorie');
define('_AM_XNEWS_USERS_LIST', 'Liste des utilisateurs');
define('_AM_XNEWS_PUBLISH_FRONTPAGE', "Publier en page d'accueil du module ?");
define('_AM_XNEWS_UPGRADEFAILED1', 'Impossible de créer la table stories_files');
define('_AM_XNEWS_UPGRADEFAILED2', 'Impossible de modifier la longueur du titre de la catégorie');
define('_AM_XNEWS_UPGRADEFAILED21', "Impossible d'ajouter de nouveaux champs dans la table des catégories");
define('_AM_XNEWS_UPGRADEFAILED3', 'Impossible de créer la table stories_votedata');
define('_AM_XNEWS_UPGRADEFAILED4', "Impossible de créer les deux champs 'rating' et 'votes' dans la tables des articles");
define('_AM_XNEWS_UPGRADEFAILED0', "Veuillez prendre note du message d'erreur et essayez de corriger le problème en utilisant phpMyAdmin et les instructions disponibles dans le dossier /sql du module News");
define('_AM_XNEWS_UPGR_ACCESS_ERROR', "Erreur : l'utilisation du script de mise à jour requiert des droits d'administration complets");
define('_AM_XNEWS_PRUNE_BEFORE', 'Supprimer les articles publiés avant');
define('_AM_XNEWS_PRUNE_EXPIREDONLY', 'Supprimer uniquement les articles dont la date de diffusion a expiré');
define('_AM_XNEWS_PRUNE_CONFIRM', 'AVERTISSEMENT : vous êtes sur le point de supprimer définitivement les articles dont la date de publication est antérieure à : %s. %s articles seront supprimés.<br>Etes-vous certain de vouloir supprimer définitivement ces articles ?');
define('_AM_XNEWS_PRUNE_TOPICS', 'Limiter aux catégories suivantes');
define('_AM_XNEWS_PRUNENEWS', 'Suppression par lots');
define('_AM_XNEWS_EXPORT_NEWS', 'Export des articles (XML)');
define('_AM_XNEWS_EXPORT_NOTHING', "Aucune donnée n'a été trouvée pour l'export. Veuillez vérifier votre paramétrage.");
define('_AM_XNEWS_PRUNE_DELETED', '%d articles ont été supprimés');
define('_AM_XNEWS_PERM_WARNING', '<h2>AVERTISSEMENT : vous disposez de trois formulaires donc de trois boutons de validation</h2>');
define('_AM_XNEWS_EXPORT_BETWEEN', 'Exporter les articles publié entre');
define('_AM_XNEWS_EXPORT_AND', ' et ');
define('_AM_XNEWS_EXPORT_PRUNE_DSC', "Si aucune catégorie n'est sélectionnée, toutes les catégories seront concernées");
define('_AM_XNEWS_EXPORT_INCTOPICS', 'Inclure les descriptions de catégories ?');
define('_AM_XNEWS_EXPORT_ERROR', "Une erreur est survenue lors de la création du fichier %s. L'opération a été arrêtée.");
define('_AM_XNEWS_EXPORT_READY', "Le fichier XML est prêt pour le téléchargement.<br><a href='%s'>Cliquer sur ce lien pour le télécharger</a>.<br>Ne pas oublier de <a href='%s'>le supprimer</a> une fois le téléchargement effectué.");
define('_AM_XNEWS_RSS_URL', 'URL du flux RSS');
define('_AM_XNEWS_NEWSLETTER', 'Newsletter');
define('_AM_XNEWS_NEWSLETTER_BETWEEN', 'Sélectionner les articles publiés entre');
define('_AM_XNEWS_NEWSLETTER_READY', "Le fichier pour votre Newsletter est prêt pour le tléchargement.<br><a href='%s'>Cliquer sur ce lien pour le télécharger</a>.<br>Ne pas oublier de <a href='%s'>le supprimer</a> une fois le téléchargement effectué.");
define('_AM_XNEWS_DELETED_OK', 'Fichier supprimé');
define('_AM_XNEWS_DELETED_PB', 'Une erreur est survenue lors de la suppression du fichier');
define('_AM_XNEWS_STATS0', 'Statistiques des catégories');
define('_AM_XNEWS_STATS', 'Statistiques');
define('_AM_XNEWS_STATS1', 'Auteurs uniques');
define('_AM_XNEWS_STATS2', 'Totaux');
define('_AM_XNEWS_STATS3', 'Statistiques des articles');
define('_AM_XNEWS_STATS4', 'Articles les plus consultés');
define('_AM_XNEWS_STATS5', 'Articles les moins consultés');
define('_AM_XNEWS_STATS6', 'Articles les mieux notés');
define('_AM_XNEWS_STATS7', 'Auteurs les plus lus');
define('_AM_XNEWS_STATS8', 'Auteurs les mieux notés');
define('_AM_XNEWS_STATS9', 'Contributeurs les plus actifs');
define('_AM_XNEWS_STATS10', 'Statistiques des auteurs');
define('_AM_XNEWS_STATS11', 'Décompte des articles');
define('_AM_XNEWS_HELP', 'Aide');
define('_AM_XNEWS_MODULEADMIN', " - Interface d'administration");
define('_AM_XNEWS_GENERALSET', 'Préférences');
define('_AM_XNEWS_GOTOMOD', 'Afficher le module côté public');
define('_AM_XNEWS_NOTHING', 'Aucune ressource ne peut être téléchargée. Veuillez vérifier votre paramétrage.');
define('_AM_XNEWS_NOTHING_PRUNE', "Aucun article ne correspond à vos critères, aucune suppression n'a été réalisée.");
define('_AM_XNEWS_TOPIC_COLOR', 'Couleur de la catégorie');
define('_AM_XNEWS_COLOR', 'Couleur');
define('_AM_XNEWS_REMOVE_BR', 'Convertir la balise Html &lt;br&gt; en une nouvelle ligne ?');
// Added in 1.3 RC2
define('_AM_XNEWS_PLEASE_UPGRADE', "<a href='upgrade.php'><font color='#FF0000'>Veuillez mettre à jour le module</font></a>");

// Added in verisn 1.50
define('_AM_XNEWS_NEWSLETTER_HEADER', 'En-tête');
define('_AM_XNEWS_NEWSLETTER_FOOTER', 'Pied de page');
define('_AM_XNEWS_NEWSLETTER_HTML_TAGS', 'Supprimer les balises Html ?');
define('_AM_XNEWS_VERIFY_TABLES', 'Maintenir les tables');
define('_AM_XNEWS_METAGEN', 'Metagen');
define('_AM_XNEWS_METAGEN_DESC', 'Metagen vous permet de générer automatiquement les balises meta associées à chaque article.<br>A moins que vous ne préfériez les saisir vous-même, le module les insèrera lui-même.');
define('_AM_XNEWS_BLACKLIST', "Liste d'exclusion");
define('_AM_XNEWS_BLACKLIST_DESC', 'Les termes présents dans cette liste seront bannis des mots-clés utilisés par metagen');
define('_AM_XNEWS_BLACKLIST_ADD', 'Ajouter');
define('_AM_XNEWS_BLACKLIST_ADD_DSC', 'Saisir les termes à bannir<br>(un terme par ligne)');
define('_AM_XNEWS_META_KEYWORDS_CNT', 'Nombre maximum de mots-clés à générer automatiquement');
define('_AM_XNEWS_META_KEYWORDS_ORDER', 'Classement des mots-clés');
define('_AM_XNEWS_META_KEYWORDS_INTEXT', "Classer les mots-clés conformément à leur ordre d'apparition dans l'article");
define('_AM_XNEWS_META_KEYWORDS_FREQ1', "Classer les mots-clés en fonction de leur récurrence dans l'article");
define('_AM_XNEWS_META_KEYWORDS_FREQ2', "Classer les mots-clés dans l'ordre inverse de leur récurrence dans l'article");

// Added in version 1.67 Beta
define('_AM_XNEWS_SUBPREFIX', 'Sous-préfixe');

define('_AM_XNEWS_CLONER', 'Clonage');
define('_AM_XNEWS_CLONER_CLONES', 'Clones');
define('_AM_XNEWS_CLONER_ADD', 'Ajouter un Clone');
define('_AM_XNEWS_CLONER_ID', 'ID');
define('_AM_XNEWS_CLONER_NAME', 'Nom');
define('_AM_XNEWS_CLONER_DIRFOL', 'Répertoire');
define('_AM_XNEWS_CLONER_VERSION', 'Version');

define('_AM_XNEWS_CLONER_NEWNAME', 'Nom du nouveau module');
define(
    '_AM_XNEWS_CLONER_NEWNAMEDESC',
    "Cette information affecte également le nom du répertoire du module. <br>La saisie est insensible à la casse et les espaces sont automatiquement supprimés.<br><br>Exemples :<br><strong>Library</strong> devient <strong>library</strong> dans le répertoire des modules<br><strong>My Library</strong> devient <strong>mylibrary</strong> dans le répertoire des modules<br><br> Le module d'origine est : <font color='#008400'><strong> %s </strong></font><br>"
);
define('_AM_XNEWS_CLONER_NEWNAMELABEL', 'Nouveau module :');

define('_AM_XNEWS_CLONER_DIREXISTS', "Le répertoire '%s' existe déjà");
define('_AM_XNEWS_CLONER_CREATED', "Le module '%s' a été cloné");
define('_AM_XNEWS_CLONER_UPRADED', "Le module '%s' a été mis à jour");
define('_AM_XNEWS_CLONER_NOMODULEID', "L'ID du module n'a pas été défini");

define('_AM_XNEWS_CLONER_UPDATE', 'Mise à jour');
define('_AM_XNEWS_CLONER_INSTALL', 'Installation');
define('_AM_XNEWS_CLONER_UNINSTALL', 'Désinstallation');
define('_AM_XNEWS_CLONER_ACTION_INSTALL', 'Installer / désinstaller');

define('_AM_XNEWS_CLONER_IMPORTNEWS', 'Importer les données depuis le module News');
define('_AM_XNEWS_CLONER_IMPORTNEWSDESC1', 'Le module News a été trouvé. Voulez-vous importer les données ?');
define(
    '_AM_XNEWS_CLONER_IMPORTNEWSDESC2',
    "Le bouton d'import n'est disponible que si la table des artciles du module xNews est vierge.<br>
                                         Si vous avez déjà créé des articles dans xNews<br>
                                         veuillez le désinstaller complètement et le réinstaller.<br>
                                         Si vous avez déjà importé des données depuis le module News, un nouvel import est impossible."
);
define('_AM_XNEWS_CLONER_IMPORTNEWSSUB', 'Importer');
define('_AM_XNEWS_CLONER_NEWSIMPORTED', 'Les données du module News ont été importées.');

// Added in version 1.68 Beta
define(
    '_AM_XNEWS_DESCRIPTION',
    "<h3>xNews est une version clonable du module News</h3>
                              Les utilisateur peuvent poster des articles et les commenter.<br>Le module peut être cloné et ainsi couvrir tous vos besoins en matière de gestion d'articles."
);

// Added in version 1.68 RC1
define('_AM_XNEWS_CLONER_CLONEDELETED', "Le clone '%s' a été supprimé.");
define('_AM_XNEWS_CLONER_CLONEDELETEDERR', "Le clone '%s' ne peut pas être supprimé. Veuillez vérifier les permissions.");
define('_AM_XNEWS_CLONER_CLONEUPGRADED', 'Mis à jour');
define('_AM_XNEWS_CLONER_UPGRADEFORCE', 'Forcer la mise à jour');
define('_AM_XNEWS_CLONER_CLONEDELETION', 'Suppression du clone');
define('_AM_XNEWS_CLONER_SUREDELETE', "AVERTISSEMENT : êtes-vous certain de vouloir effacer  le clone <font color='#000000'>'%s'</font> ?<br>");
define('_AM_XNEWS_CLONER_CLONEID', "L'ID du clone ID n'a pas été défini");

// Added in version 1.68 RC2
define('_AM_XNEWS_INDEX', 'Index');

// Added in version 1.68 RC3
define('_AM_XNEWS_DOLINEBREAK', 'Activer le saut de ligne');

define('_AM_XNEWS_TOPICS', 'Catégories'); /**
 * @translation     Communauté Francophone des Utilisateurs de Xoops
 * @specification   _LANGCODE: fr
 * @specification   _CHARSET: UTF-8 sans Bom
 *
 * @version         $Id $
 **/
