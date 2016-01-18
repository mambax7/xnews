<?php
// $Id: modinfo.php 1243 2010-08-29 18:59:52Z kris_fr $
// Module Info

// The name of this module
define("_MI_NW_NAME", "x"."News");

// A brief description of this module
define("_MI_NW_DESC", "Module de gestion d'articles, ouverts aux contributions des utilisateurs.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_NW_BNAME1", "Cat�gories d'articles");
define("_MI_NW_BNAME3", "Article du jour");
define("_MI_NW_BNAME4", "Articles les plus populaires");
define("_MI_NW_BNAME5", "Articles les plus r�cents");
define("_MI_NW_BNAME6", "Mod�ration des articles");
define("_MI_NW_BNAME7", "Navigation dans les cat�gories");

// Sub menus in main menu block
define("_MI_NW_SMNAME1", "Proposer un article");
define("_MI_NW_SMNAME2", "Archives");

// Names of admin menu items
define("_MI_NW_ADMENU2", "Cat�gories");
define("_MI_NW_ADMENU3", "Articles");
define("_MI_NW_GROUPPERMS", "Permissions");
// Added by Herv� for prune option
define("_MI_NW_PRUNENEWS", "Gestion par lot");
// Added by Herv�
define("_MI_NW_EXPORT", "Exportation");

// Title of config items
define("_MI_NW_STORYHOME", "S�lectionner le nombre d'articles � afficher sur la page d'accueil du module");
define("_MI_NW_NOTIFYSUBMIT", "Choisir Oui pour alerter l'administrateur lorsqu'un nouvel article est propos�");
define("_MI_NW_DISPLAYNAV", "Choisir Oui pour afficher la liste de navigation dans les cat�gories au sommet de chaque article");
define("_MI_NW_AUTOAPPROVE", "Diffuser automatiquement - sans l'approbation pr�alable d'un administrateur - les articles propos�s ?");
define("_MI_NW_ALLOWEDSUBMITGROUPS", "Groupes autoris�s � proposer des articles");
define("_MI_NW_ALLOWEDAPPROVEGROUPS", "Groupes autoris�s � diffuser un article");
define("_MI_NW_NEWSDISPLAY", "Mise en page des articles");
define("_MI_NW_NAMEDISPLAY", "Citation de l'auteur");
define("_MI_NW_COLUMNMODE", "Nombre de colonnes pour lister les articles");
define("_MI_NW_STORYCOUNTADMIN", "Nombre de nouveaux articles � afficher c�t� administration");
define("_MI_NW_UPLOADFILESIZE", "Poids maximal du fichier attach� (KB) 1048576 = 1 Mo");
define("_MI_NW_UPLOADGROUPS", "Groupes autoris�s � attacher un document");

// Description of each config items
define("_MI_NW_STORYHOMEDSC", "");
define("_MI_NW_NOTIFYSUBMITDSC", "");
define("_MI_NW_DISPLAYNAVDSC", "");
define("_MI_NW_AUTOAPPROVEDSC", "");
define("_MI_NW_ALLOWEDSUBMITGROUPSDESC", "Les groupes s�lectionn�s seront autoris�s � proposer des articles");
define("_MI_NW_ALLOWEDAPPROVEGROUPSDESC", "Les groupes s�lectionn�s seront autoris�s � diffuser des articles");
define("_MI_NW_NEWSDISPLAYDESC", "L'affichage par Cat�gories ordonne les articles par cat�gorie d'appartenance (l'article le plus r�cent est accompagn� de son introduction, seul le titre appara�t pour les articles suivants)");
define("_MI_NW_ADISPLAYNAMEDSC", "");
define("_MI_NW_COLUMNMODE_DESC", "");
define("_MI_NW_STORYCOUNTADMIN_DESC", "Cette valeur sera �galement employ�e pour limiter le nombre de cat�gories affich�es par page et pour l'affichage des statistiques");
define("_MI_NW_UPLOADFILESIZE_DESC", "");
define("_MI_NW_UPLOADGROUPS_DESC", "Les groupes s�lectionn�s seront autoris�s � placer des fichiers joints sur le serveur");

// Name of config item values
define("_MI_NW_NEWSCLASSIC", "Liste ante-chronologique");
define("_MI_NW_NEWSBYTOPIC", "Affichage par Cat�gories");
define("_MI_NW_DISPLAYNAME1", "Nom d'utilisateur");
define("_MI_NW_DISPLAYNAME2", "Nom r�el");
define("_MI_NW_DISPLAYNAME3", "Ne pas citer d'auteur");
define("_MI_NW_UPLOAD_GROUP1", "Contributeurs et mod�rateurs");
define("_MI_NW_UPLOAD_GROUP2", "Mod�rateurs seulement");
define("_MI_NW_UPLOAD_GROUP3", "T�l�versement d�sactiv�");

// Text for notifications
define("_MI_NW_GLOBAL_NOTIFY", "Globale");
define("_MI_NW_GLOBAL_NOTIFYDSC", "Options de notification globales.");

define("_MI_NW_STORY_NOTIFY", "Articles");
define("_MI_NW_STORY_NOTIFYDSC", "Options de notification applicables � l'article affich�.");

define("_MI_NW_GLOBAL_NEWCATEGORY_NOTIFY", "Nouvelle cat�gorie");
define("_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYCAP", "Me notifier lorsque une nouvelle cat�gorie d'articles est cr��e.");
define("_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYDSC", "Recevoir une alerte lorsqu'une nouvelle cat�gorie est cr��e.");
define("_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : nouvelle cat�gorie d'articles");

define("_MI_NW_GLOBAL_STORYSUBMIT_NOTIFY", "Nouvel article propos�");
define("_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYCAP", "Me notifier lorsque un nouvel article est propos� et en attente de diffusion.");
define("_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYDSC", "Recevoir une alerte lorsque un nouvel article est propos� et en attente de diffusion.");
define("_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : nouvel article propos�");

define("_MI_NW_GLOBAL_NEWSTORY_NOTIFY", "Nouvel article");
define("_MI_NW_GLOBAL_NEWSTORY_NOTIFYCAP", "Me notifier lorsque un nouvel article est publi�.");
define("_MI_NW_GLOBAL_NEWSTORY_NOTIFYDSC", "Recevoir une alerte lorsqu'un nouvel article est publi�.");
define("_MI_NW_GLOBAL_NEWSTORY_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : nouvel article publi�");

define("_MI_NW_STORY_APPROVE_NOTIFY", "Article approuv�");
define("_MI_NW_STORY_APPROVE_NOTIFYCAP", "Me notifier lorsque la publication d'un nouvel article est approuv�e.");
define("_MI_NW_STORY_APPROVE_NOTIFYDSC", "Recevoir une alerte lorsque la publication d'un nouvel article est approuv�e.");
define("_MI_NW_STORY_APPROVE_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : publication d'article approuv�e");

define("_MI_NW_RESTRICTINDEX", "Restreindre la consultation des articles ?");
define("_MI_NW_RESTRICTINDEXDSC", "En choisissant Oui la consultation des articles est r�serv�e � certains groupes d'utilisateurs, conform�ment � la distribution des droits op�r�e � la rubrique Permissions. Les autres utilisateurs ne pourront aller plus loin que la page de la Cat�gorie.");

define("_MI_NW_NEWSBYTHISAUTHOR", "Afficher le lien 'Articles du m�me auteur' ?");
define("_MI_NW_NEWSBYTHISAUTHORDSC", "");

define("_MI_NW_PREVNEX_LINK", "Afficher les liens 'Pr�c�dent' et 'Suivant' ?");
define("_MI_NW_PREVNEX_LINK_DESC", "En choisissant Oui deux liens sont affich�s au pied de chaque article et permettent de consulter un � un les articles selon leur ordre chronologique");
define("_MI_NW_SUMMARY_SHOW", "Afficher la liste des articles r�cents ?");
define("_MI_NW_SUMMARY_SHOW_DESC", "En choisissant Oui, le visiteur dispose de la liste des publications r�centes au pied de chaque article. Il peut consulter celui de son choix en cliquant sur le titre.");
define("_MI_NW_AUTHOR_EDIT", "Autoriser les auteurs � modifier leurs contributions ?");
define("_MI_NW_AUTHOR_EDIT_DESC", "");
define("_MI_NW_RATE_NEWS", "Autoriser les utilisateur � �valuer les articles ?");
define("_MI_NW_TOPICS_RSS", "Activer un flux RSS pour chaque cat�gorie ?");
define("_MI_NW_TOPICS_RSS_DESC", "");
define("_MI_NW_DATEFORMAT", "Format de la date");
define("_MI_NW_DATEFORMAT_DESC", "Pour plus d'informations, veuillez vous r�f�rer � la documentation PHP(http://fr.php.net/manual/en/function.date.php). Note : si vous ne saisissez aucune information, le format par d�faut sera utilis�.");
define("_MI_NW_META_DATA", "Activer la saisie manuelle des Metas (description et mot-cl�s) ?");
define("_MI_NW_META_DATA_DESC", "En choisissant Oui le formulaire de cr�ation d'articles comporte deux champs de saisie suppl�mentaires.");
define("_MI_NW_BNAME8", "Articles au hasard");
define("_MI_NW_NEWSLETTER", "Newsletter");
define("_MI_NW_STATS", "Statistique");
define("_MI_NW_FORM_OPTIONS", "Options de formulaire");
define("_MI_NW_FORM_COMPACT", "Compact");
define("_MI_NW_FORM_DHTML", "DHTML");
define("_MI_NW_FORM_SPAW", "Spaw Editor");
define("_MI_NW_FORM_HTMLAREA", "Editeur HtmlArea");
define("_MI_NW_FORM_FCK", "FCKEditor");
define("_MI_NW_FORM_KOIVI", "Koivi Editor");
define("_MI_NW_FORM_OPTIONS_DESC", "S�lectionner l'�diteur : DHTML et Compact sont disponibles par d�faut. Les �diteurs plus avanc�s sont des applications tierces � mettre en place par vos soins.");
define("_MI_NW_KEYWORDS_HIGH", "Utiliser le surlignement des mot-cl�s ?");
define("_MI_NW_KEYWORDS_HIGH_DESC", "En choisissant Oui, les termes trouv�s seront mis en �vidence.");
define("_MI_NW_HIGH_COLOR", "Couleur de surlignement");
define("_MI_NW_HIGH_COLOR_DES", "A renseigner si la pr�c�dente option est activ�e");
define("_MI_NW_INFOTIPS", "Nombre de caract�res affich�s dans les infobulles");
define("_MI_NW_INFOTIPS_DES", "En activant cette option les 'n' premiers caract�res de l'article seront affich�s dans l'infobulle affich�e au survol du titre. Note : en indiquant 0, vous masquez les infobulles.");
define("_MI_NW_SITE_NAVBAR", "Utiliser la barre de navigation Mozilla et Opera ?");
define("_MI_NW_SITE_NAVBAR_DESC", "En choisissant Oui les visiteurs pourront utiliser les barres de navigation �tendues de leur navigateur favori.");
define("_MI_NW_TABS_SKIN", "S�lectionner l'apparence des onglets");
define("_MI_NW_TABS_SKIN_DESC", "Ces styles sont utilis�s par les blocs disposant d'un affichage par onglets");
define("_MI_NW_SKIN_1", "Style barre");
define("_MI_NW_SKIN_2", "Style inclin�");
define("_MI_NW_SKIN_3", "Classique");
define("_MI_NW_SKIN_4", "Dossiers");
define("_MI_NW_SKIN_5", "MacOs");
define("_MI_NW_SKIN_6", "Plat");
define("_MI_NW_SKIN_7", "Arorndis");
define("_MI_NW_SKIN_8", "Style ZDnet");

// Added in version 1.50
define("_MI_NW_BNAME9", "Archives");
define("_MI_NW_FORM_TINYEDITOR", "TinyEditor");
define("_MI_NW_FOOTNOTES", "Voir les liens sur les versions imprimables ?");
define("_MI_NW_DUBLINCORE", "Activer les Dublin Core Metadata ?");
define("_MI_NW_DUBLINCORE_DSC", "Pour plus d'informations, <a href='http://dublincore.org/'>veuiller consulter ce lien</a>");
define("_MI_NW_BOOKMARK_ME", "Afficher un bloc 'Ajouter cette page sur ces sites' ?");
define("_MI_NW_BOOKMARK_ME_DSC", "Ce bloc sera visible sur la page des articles");
define("_MI_NW_FF_MICROFORMAT", "Activate Firefox 2 Micro Summaries ?");
define("_MI_NW_FF_MICROFORMAT_DSC", "Pour plus d'informations, <a href='http://wiki.mozilla.org/Microsummaries' target='_blank'>veuiller consulter ce lien</a>");
define("_MI_NW_WHOS_WHO", "Annuaire des auteurs");
define("_MI_NW_METAGEN", "Metagen");
define("_MI_NW_TOPICS_DIRECTORY", "R�pertoire des cat�gories");
define("_MI_NW_ADVERTISEMENT", "Publicit�");
define("_MI_NW_ADV_DESCR", "Saisir le texte ou le code javascript de votre publicit�");
define("_MI_NW_MIME_TYPES", "Entrez les types mime autoris�s pour le t�l�versement des fichiers attach�s (s�parez les par un retour � la ligne)");
define("_MI_NW_ENHANCED_PAGENAV", "Utiliser le s�parateur de pages am�lior� ?");
define("_MI_NW_ENHANCED_PAGENAV_DSC", "Avec cette option vous pouvez d�couper votre article avec des balises [pagrebreak:Titre page], les liens vers les pages sont remplac�s par une liste d�roulante et vous pouvez utiliser [summary] pour cr�er un sommaire automatique des pages");

// Added in version 1.54
define("_MI_NW_CATEGORY_NOTIFY", "Cat�gorie");
define("_MI_NW_CATEGORY_NOTIFYDSC", "Options de notification pour la cat�gorie en cours");

define("_MI_NW_CATEGORY_STORYPOSTED_NOTIFY", "Nouvel article propos�");
define("_MI_NW_CATEGORY_STORYPOSTED_NOTIFYCAP", "Me notifier lorsque un nouvel article est publi� dans cette cat�gorie.");
define("_MI_NW_CATEGORY_STORYPOSTED_NOTIFYDSC", "Recevoir une alerte lorsqu'un nouvel article est publi� dans cette cat�gorie.");
define("_MI_NW_CATEGORY_STORYPOSTED_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE} notification automatique : nouvel article publi�");

// Added in version 1.63
define("_MI_NW_TAGS", "Utiliser la fonction TAG ?");
define("_MI_NW_TAGS_DSC", "Requiert le module TAG");
define("_MI_NW_BNAME10", "Nuage de tags");
define("_MI_NW_BNAME11", "Tags populaires");
define("_MI_NW_INTRO_TEXT", "Texte d'introduction � afficher au sommet du formulaire de soumission");
define("_MI_NW_IMAGE_MAX_WIDTH", "Largeur maximale de l'image (au-del� de cette limite, l'image est redimensionn�e)");
define("_MI_NW_IMAGE_MAX_HEIGHT", "Hauteur maximale de l'image (au-del� de cette limite, l'image est redimensionn�e)");

// Added in version 1.67
define("_MI_NW_CLONER", "Clonage");

define("_MI_NW_LATESTNEWS_BLOCK", "Bloc des derniers articles");

// Added in version 1.68 BETA
define("_MI_NW_TOPICDISPLAY", "Afficher les cat�gories");
define("_MI_NW_TOPICDISPLAYDESC", "Cette option permet d'afficher / de masquer le nom de la cat�gorie devant le titre des articles.");

define("_MI_NW_SEOENABLE", "Option SEO");
define("_MI_NW_SEOENABLEDESC", "<strong>htaccess</strong> : <br /> http://your.site.com/<strong>xnews</strong>/topics.1/your-topic-title.html <br /><br /><strong>path-info</strong> : <br />http://your.site.com/modules/xnews/index.php/topics.1/your-topic-title.html");

// Added in version 1.68 RC1
define("_MI_NW_EXTEND_META_DATA", "Champ Meta am�lior�");
define("_MI_NW_EXTEND_META_DATA_DESC", "Cette option permet � l'utilisateur de contr�ler les meta (description et mot-cl�s).");

define("_MI_NW_NONE", "None");
define("_MI_NW_TOPONLY", "Au sommet seulement");
define("_MI_NW_BOTTOMONLY", "En pied de page uniquement");
define("_MI_NW_BOTH", "Les deux");
define("_MI_NW_DISPLAYLINKICNS", "Afficher les ic�nes additionnelles");
define("_MI_NW_DISPLAYLINKICNSDESC", "Affiche les fonctions Impression, En parler � un ami et Exporter au format PDF");

define("_MI_NW_SEOPATH", "Chemin SEO ");
define("_MI_NW_SEOPATHDESC", "Cette option ajoute un titre SEO aux urls, g�n�r�es via <strong>htaccess</strong> ou <strong>path-info</strong>. <br /><br />Laisser vide affiche : <br />http://your.site.com/topics.1/your-topic-title.html <br /><br />Saisir <strong>news</strong> affiche : <br />http://your.site.com/<strong>news</strong>/topics.1/your-topic-title.html <br /><br />Les caract�res a-z et - sont autoris�s (ex. article-du-jour)");
define("_MI_NW_SEOLEVEL", "Niveau SEO ");
define("_MI_NW_SEOLEVELDESC", "Cette option permet de modifier l'apparence de l'url<br /><br />Niveau racine : <br />http://your.site.com/news/topics.1/your-topic-title.html <br /><br />Niveau module : <br /> http://your.site.com/modules/xnews/news.topics.1/your-topic-title.html<br /><br />Cette option ne peut fonctionner qu'en mode htaccess et en personnalisant le fichier .htaccess.");
define("_MI_NW_MODULE_LEVEL", "Niveau module");
define("_MI_NW_ROOT_LEVEL", "Niveau racine");

//ADDED wishcraft 1.68 RC3
define("_MI_NW_SEOENDOFURL", "TFin de l'adresse URL");
define("_MI_NW_SEOENDOFURL_DESC", "Extension de fichier pour les HTML");
define("_MI_NW_SEOENDOFURLRSS", "Fin de l'adresse URL");
define("_MI_NW_SEOENDOFURLRSS_DESC", "Extension de fichier pour les pages RSS");
define("_MI_NW_SEOENDOFURLPDF", "Fin de l'adresse URL");
define("_MI_NW_SEOENDOFURLPDF_DESC", "Extension de fichier pour les Adobe Acrobat (PDF");

/**
 * @translation     Communaut� Francophone des Utilisateurs de Xoops
 * @specification   _LANGCODE: fr
 * @specification   _CHARSET: ISO-8859-1
 *
 * @version         $Id $
**/
?>