<?php
// $Id: modinfo.php,v 1.70 2010/09/01 17:48:07 dnprossi Exp $
// Module Info

// The name of this module
define('_MI_XNEWS_NAME', 'Notizie');

// A brief description of this module
define('_MI_XNEWS_DESC', 'Crea una sezione di notizie in cui gli utenti possono inviare e commentare notizie.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_XNEWS_BNAME1', 'Argomenti delle notizie');
define('_MI_XNEWS_BNAME3', 'Notizia del giorno');
define('_MI_XNEWS_BNAME4', 'Notizie più lette');
define('_MI_XNEWS_BNAME5', 'Notizie recenti');
define('_MI_XNEWS_BNAME6', 'Modera Notizie');
define('_MI_XNEWS_BNAME7', 'Naviga tra gli Argomenti');

// Sub menus in main menu block
define('_MI_XNEWS_SMNAME1', 'Scrivi una notizia');
define('_MI_XNEWS_SMNAME2', 'Archivio');

// Names of admin menu items
define('_MI_XNEWS_ADMENU2', 'Gestione Argomenti');
define('_MI_XNEWS_ADMENU3', 'Gestione Notizie');
define('_MI_XNEWS_GROUPPERMS', 'Permessi');
// Added by Hervé for prune option
define('_MI_XNEWS_PRUNENEWS', 'Elimina Notizie');
// Added by Hervé
define('_MI_XNEWS_EXPORT', 'Esporta Notizie');

// Title of config items
define('_MI_XNEWS_STORYHOME', 'Quante notizie sulla pagina principale?');
define('_MI_XNEWS_NOTIFYSUBMIT', 'Notifica via email ogni nuova notizia inviata?');
define('_MI_XNEWS_DISPLAYNAV', 'Mostra il box di navigazione?');
define('_MI_XNEWS_AUTOAPPROVE', "Approva automaticamente le nuove notizie senza l'intervento dell'amministratore?");
define('_MI_XNEWS_ALLOWEDSUBMITGROUPS', 'Gruppi che possono inviare notizie');
define('_MI_XNEWS_ALLOWEDAPPROVEGROUPS', 'Gruppi che possono approvare notizie');
define('_MI_XNEWS_NEWSDISPLAY', 'Layout di visualizzazione notizie');
define('_MI_XNEWS_NAMEDISPLAY', "Nome dell'Autore");
define('_MI_XNEWS_COLUMNMODE', 'Colonne');
define('_MI_XNEWS_STORYCOUNTADMIN', "Numero di nuovi articoli da visualizzare nell'area admin (usata anche per limitare il numero di topic mostrati nell'area admin e nelle statistiche): ");
define('_MI_XNEWS_UPLOADFILESIZE', 'MAX Dimensione file per caricamento (KB) 1048576 = 1 Meg');
define('_MI_XNEWS_UPLOADGROUPS', 'Gruppi autorizzati a caricare file');

// Description of each config items
define('_MI_XNEWS_STORYHOMEDSC', 'Seleziona il numero di notizie da visualizzare sulla pagina principale.');
define('_MI_XNEWS_NOTIFYSUBMITDSC', 'Scegli Sì per inviare un messaggio di notifica al webmaster per ogni nuova notizia inviata.');
define('_MI_XNEWS_DISPLAYNAVDSC', 'Scegli Sì per mostrare il box di navigazione in cima a ogni pagina di notizie.');
define('_MI_XNEWS_AUTOAPPROVEDSC', '');
define('_MI_XNEWS_ALLOWEDSUBMITGROUPSDESC', 'I gruppi selezionati saranno autorizzati a inviare nuove notizie');
define('_MI_XNEWS_ALLOWEDAPPROVEGROUPSDESC', 'I gruppi selezionati saranno autorizzati ad approvare le notizie inserite');
define('_MI_XNEWS_NEWSDISPLAYDESC', 'Classico mostra tutte le notizie ordinate per data di pubblicazione. Per Argomento raggruppa le notizie per argomento, con la prima completa e delle altre solo il titolo');
define('_MI_XNEWS_ADISPLAYNAMEDSC', "Scegli come visualizzare il nome dell'autore");
define('_MI_XNEWS_COLUMNMODE_DESC', 'You can choose the number of columns to display articles list');
define('_MI_XNEWS_STORYCOUNTADMIN_DESC', '');
define('_MI_XNEWS_UPLOADFILESIZE_DESC', '');
define('_MI_XNEWS_UPLOADGROUPS_DESC', 'Seleziona i gruppi che possono caricare sul server');

// Name of config item values
define('_MI_XNEWS_NEWSCLASSIC', 'Classico');
define('_MI_XNEWS_NEWSBYTOPIC', 'Per Argomento');
define('_MI_XNEWS_DISPLAYNAME1', 'Nome Utente');
define('_MI_XNEWS_DISPLAYNAME2', 'Vero Nome');
define('_MI_XNEWS_DISPLAYNAME3', 'Non mostrare autore');
define('_MI_XNEWS_UPLOAD_GROUP1', 'Chi invia e chi può approvare');
define('_MI_XNEWS_UPLOAD_GROUP2', 'Solo chi può approvare');
define('_MI_XNEWS_UPLOAD_GROUP3', 'Caricamento disabilitato');

// Text for notifications
define('_MI_XNEWS_GLOBAL_NOTIFY', 'Globale');
define('_MI_XNEWS_GLOBAL_NOTIFYDSC', 'Opzioni globali di notifica delle notizie.');

define('_MI_XNEWS_STORY_NOTIFY', 'Articolo');
define('_MI_XNEWS_STORY_NOTIFYDSC', 'Opzioni di notifica applicati a questo articolo');

define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFY', 'Nuovo argomento');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notificami per ogni nuovo argomento creato.');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Ricevi una notifica quando un nuovo argomento viene creato.');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica : Nuovo argomento delle notizie');

define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFY', 'Nuovo articolo inviato');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYCAP', 'Notificami per ogni nuovo articolo inviato (in attesa di approvazione).');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYDSC', 'Ricevi una notifica quando un nuovo articolo viene inviato (in attesa di approvazione).');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica : Nuovo articolo inviato');

define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFY', 'Nuovo articolo');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYCAP', 'Notificami per ogni nuovo articolo inviato.');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYDSC', 'Ricevi una notifica quando un nuovo articolo viene inviato.');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica : Nuovo articolo');

define('_MI_XNEWS_STORY_APPROVE_NOTIFY', 'Articolo approvato');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYCAP', 'Notificami quando questo articolo viene approvato.');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYDSC', 'Ricevi una notifica quando questo articolo viene approvato.');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notifica automatica : Articolo approvato');

define('_MI_XNEWS_RESTRICTINDEX', 'Limita gli argomenti sulla pagina Indice?');
define('_MI_XNEWS_RESTRICTINDEXDSC', "Se impostato a sì, gli utenti potranno vedere nell'indice solo le notizie a cui hanno access, come settato in Permessi");

define('_MI_XNEWS_NEWSBYTHISAUTHOR', 'Notizie dello stesso Autore');
define('_MI_XNEWS_NEWSBYTHISAUTHORDSC', "Se impostato a sì, comparirà un link 'Articoli di questo autore'.");

define('_MI_XNEWS_PREVNEX_LINK', 'Mostra link Precedente e Successivo?');
define('_MI_XNEWS_PREVNEX_LINK_DESC', "Quando quest'opzione è impostata a 'sì', due nuovi link sono visibili alla fine di ogni articolo. Questi link sono utilizzati per navigare verso l'articolo precedente o successivo in ordine di data");
define('_MI_XNEWS_SUMMARY_SHOW', 'Mostra tabella sommario?');
define('_MI_XNEWS_SUMMARY_SHOW_DESC', 'Quando usi questa opzione, un sommario contenente link a tutti gli articoli recentemente pubblicati è visibile alla fine di ogni articolo');
define('_MI_XNEWS_AUTHOR_EDIT', 'Permetti agli autori di modificare i loro invii?');
define('_MI_XNEWS_AUTHOR_EDIT_DESC', 'Con questa opzione, gli autori possono modificare le loro notizie.');
define('_MI_XNEWS_RATE_NEWS', 'Permetti agli utenti di votare le notizie ?');
define('_MI_XNEWS_TOPICS_RSS', "Permetti RSS Feed dell'argomento?");
define('_MI_XNEWS_TOPICS_RSS_DESC', "Se imposti questa opzione a 'sì', il contenuto dell'argomento sarà disponibile come RSS Feed");
define('_MI_XNEWS_DATEFORMAT', 'Formato Data');
define('_MI_XNEWS_DATEFORMAT_DESC', 'Riferisciti alla documentazione PHP per maggiorin informazioni su come selezionare il formato. Se non inserisci nulla il formato di default sarà utilizzato.');
define('_MI_XNEWS_META_DATA', 'Permetti di inserire meta data (keywords e description)?');
define('_MI_XNEWS_META_DATA_DESC', "Se imposti questa opzione a 'sì', chi approva una notizia potrà inserire i meta dati keyword e description");
define('_MI_XNEWS_BNAME8', 'Notizie Casuali');
define('_MI_XNEWS_NEWSLETTER', 'Newsletter');
define('_MI_XNEWS_STATS', 'Statistiche');
define('_MI_XNEWS_FORM_OPTIONS', 'Opzioni Form');
define('_MI_XNEWS_FORM_COMPACT', 'Compact');
define('_MI_XNEWS_FORM_DHTML', 'DHTML');
define('_MI_XNEWS_FORM_SPAW', 'Editor Spaw');
define('_MI_XNEWS_FORM_HTMLAREA', 'Editor HtmlArea');
define('_MI_XNEWS_FORM_FCK', 'Editor FCK');
define('_MI_XNEWS_FORM_KOIVI', 'Editor Koivi');
define('_MI_XNEWS_FORM_OPTIONS_DESC', "Scegli l'editor da utilizzare. Se hai una installazione 'semplice' (usi solo le classi editor di core contenute nel package standard di xoops), puoi scegliere tra DHTML e Compact");
define('_MI_XNEWS_KEYWORDS_HIGH', 'Evidenzia parole chiave?');
define('_MI_XNEWS_KEYWORDS_HIGH_DESC', 'Se usi questa opzione, le parole chiave inserite nella ricerca verranno evidenziate negli articoli');
define('_MI_XNEWS_HIGH_COLOR', 'Colore utilizzato per evidenzare le parole chiave?');
define('_MI_XNEWS_HIGH_COLOR_DES', "Usa questa opzione solo se hai scelto 'sì' per la precedente.");
define('_MI_XNEWS_INFOTIPS', 'Lunghezza Tooltips');
define('_MI_XNEWS_INFOTIPS_DES', "Se usi questa opzione, i link relativi alle notizie conterranno i primi (n) caratteri dell'articolo. Se imposti questo valore a 0, gli infotips saranno vuoti.");
define('_MI_XNEWS_SITE_NAVBAR', 'Usa la barra di navigazione di Mozilla e Opera?');
define('_MI_XNEWS_SITE_NAVBAR_DESC', "Se imposti questa opzione a 'sì' i visitatori del tuo sito potranno utilizzare la barra di navigazione per muoversi tra i tuoi articoli.");
define('_MI_XNEWS_TABS_SKIN', 'Scegli la skin da utilizzare nelle cartelle');
define('_MI_XNEWS_TABS_SKIN_DESC', 'Questa skin verrà utilizzata in ogni blocco che usa le cartelle');
define('_MI_XNEWS_SKIN_1', 'Bar Style');
define('_MI_XNEWS_SKIN_2', 'Beveled');
define('_MI_XNEWS_SKIN_3', 'Classic');
define('_MI_XNEWS_SKIN_4', 'Folders');
define('_MI_XNEWS_SKIN_5', 'MacOs');
define('_MI_XNEWS_SKIN_6', 'Plain');
define('_MI_XNEWS_SKIN_7', 'Rounded');
define('_MI_XNEWS_SKIN_8', 'ZDnet style');

// Added in version 1.50
define('_MI_XNEWS_BNAME9', 'Archivi');
define('_MI_XNEWS_FORM_TINYEDITOR', 'TinyEditor');
define('_MI_XNEWS_FOOTNOTES', 'Visualizza i links degli articoli in versione stampabile?');
define('_MI_XNEWS_DUBLINCORE', 'Attiva Dublin Core Metadata ?');
define('_MI_XNEWS_DUBLINCORE_DSC', "Per maggiori informazioni, <a hreh='http://dublincore.org/'>visit this link</a>");
define('_MI_XNEWS_BOOKMARK_ME', "Visualizza un 'Segnalibro di questo articolo in questo blocco' ?");
define('_MI_XNEWS_BOOKMARK_ME_DSC', "Questo blocco sarà visibile nella pagina dell'articolo");
define('_MI_XNEWS_FF_MICROFORMAT', 'Attiva Firefox 2 Micro Summaries ?');
define('_MI_XNEWS_FF_MICROFORMAT_DSC', "Per maggiori informazioni vai su <a href='http://wiki.mozilla.org/Microsummaries' target='_blank'>questa pagina</a>");
define('_MI_XNEWS_WHOS_WHO', 'Chi di Chi');
define('_MI_XNEWS_METAGEN', 'Metagen');
define('_MI_XNEWS_TOPICS_DIRECTORY', 'Lista degli argomenti');
define('_MI_XNEWS_ADVERTISEMENT', 'Pubblicità');
define('_MI_XNEWS_ADV_DESCR', 'Inserisci testo o javascript code da visualizzare nei tuoi articoli');
define('_MI_XNEWS_MIME_TYPES', "Inserisci Mime Types per l'upload autorizzato (uno per ogni riga)");
define('_MI_XNEWS_ENHANCED_PAGENAV', 'Usa il blocco di navigazione?');
define('_MI_XNEWS_ENHANCED_PAGENAV_DSC', 'Con questa opzione puoi separare le tue pagine con qualcosa tipo questo  : [pagrebreak:Page Title], i links alle pagine verrano sovrascritte da una lista a discesa e potrai usare [summary] per creare un sommario per le pagine automatico');

// Added in version 1.54
define('_MI_XNEWS_CATEGORY_NOTIFY', 'Categoria');
define('_MI_XNEWS_CATEGORY_NOTIFYDSC', 'Opzioni di notifica per la categoria corrente');

define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFY', 'Caricata una nuova notizia');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYCAP', 'Notificami quando una nuova notizia viene caricata in questa categoria.');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYDSC', 'Ricevi notifiche quando qualsiasi nuova storia viene caricata in questa categoria.');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notifica : Nuova Notizia');

// Added in version 1.63
define('_MI_XNEWS_TAGS', 'Vuoi usare i TAG ?');
define('_MI_XNEWS_TAGS_DSC', 'Questo è basato sul modulo XOOPS Tag di phppp');
define('_MI_XNEWS_BNAME10', 'Tags a nuvola');
define('_MI_XNEWS_BNAME11', 'Migliori Tags');
define('_MI_XNEWS_INTRO_TEXT', 'Testo introduttivo da visualizzare sulla pagina di invio');
define('_MI_XNEWS_IMAGE_MAX_WIDTH', 'Massima larghezza immagine quando ridimensionata');
define('_MI_XNEWS_IMAGE_MAX_HEIGHT', 'Massima altezza immagine quando ridimensionata');

// Added in version 1.67
define('_MI_XNEWS_CLONER', 'Gestore Cloni');

define('_MI_XNEWS_LATESTNEWS_BLOCK', 'Blocco Latest News');

// Added in version 1.68 BETA
define('_MI_XNEWS_TOPICDISPLAY', 'Visualizza Argomenti');
define('_MI_XNEWS_TOPICDISPLAYDESC', 'Abilita/disabilita la visualizzazione del titolo degli argomenti nelle intestazioni dei blocchi');

define('_MI_XNEWS_SEOENABLE', 'Abilita SEO ');
define('_MI_XNEWS_SEOENABLEDESC',
       'This will enable/disable SEO activity.<br> If <b>htaccess</b> is selected you will get: <br> http://your.site.com/<b>xnews</b>/topics.1/your-topic-title.html <br> If path-info is selected you will get: <br> http://your.site.com/modules/xnews/index.php/topics.1/your-topic-title.html');

// Added in version 1.68 RC1
define('_MI_XNEWS_EXTEND_META_DATA', 'Estendi input meta-data');
define('_MI_XNEWS_EXTEND_META_DATA_DESC', "Alterna tra textbox e textarea per l'insrimento testi dei meta-data<br> sia per la descrizione che per le parole chiave.");

define('_MI_XNEWS_NONE', 'Nessuno');
define('_MI_XNEWS_TOPONLY', 'Solo sopra');
define('_MI_XNEWS_BOTTOMONLY', 'Solo sotto');
define('_MI_XNEWS_BOTH', 'Entrambi');
define('_MI_XNEWS_DISPLAYLINKICNS', 'Visualizza Icone Link');
define('_MI_XNEWS_DISPLAYLINKICNSDESC', 'Visualizza le icone di stampa, friend e pdf nessuno-sopra-sotto-entrambi');

define('_MI_XNEWS_SEOPATH', 'Percorso SEO ');
define('_MI_XNEWS_SEOPATHDESC',
       'Questo aggiunge un titolo al url SEO sia per <b>htaccess</b> che per <b>path-info</b>. <br> Se lasciato vuoto si ottiene: <br> http://tuo.sito.com/argomenti.1/il-tuo-titolo-argomento.html <br> Se si scrive <b>notizie</b> si otterrà: <br> http://tuo.sito.com/<b>notizie</b>/argomenti.1/il-tuo-titolo-argomento.html <br><br> <b>sono accettati i caratteri a-z e il carattere meno. es. notizie-del-giorno</b>');
define('_MI_XNEWS_SEOLEVEL', 'Livello SEO ');
define('_MI_XNEWS_SEOLEVELDESC',
       "Quì si può scegliere se avere l'htaccess nella cartella root di xoops o in quella del modulo. <br> Questo cambierà l'url visualizzato. <br> A livello root: <br> http://tuo.sito.com/notizie/argomenti.1/il-tuo-titolo-argomento.html  <br> A livello modulo: <br> http://tuo.sito.com/modules/xnews/notizie.argomenti.1/il-tuo-titolo-argomento.html<br><br> Solo nella modalità htaccess e i file dovranno essere installati di conseguenza.");
define('_MI_XNEWS_MODULE_LEVEL', 'Livello modulo');
define('_MI_XNEWS_ROOT_LEVEL', 'Livello root');

//ADDED wishcraft 1.89 RC2
define('_MI_XNEWS_SEOENDOFURL', 'Fine URL');
define('_MI_XNEWS_SEOENDOFURL_DESC', 'Estensione file per i file HTML');
define('_MI_XNEWS_SEOENDOFURLRSS', 'Fine URL');
define('_MI_XNEWS_SEOENDOFURLRSS_DESC', 'Estensione file per le pagine RSS');
define('_MI_XNEWS_SEOENDOFURLPDF', 'Fine URL');
define('_MI_XNEWS_SEOENDOFURLPDF_DESC', 'Estensione file per file Adobe Acrobat (PDF)');

define('_MI_XNEWS_HOME', 'Home');
define('_MI_XNEWS_ABOUT', 'About');
