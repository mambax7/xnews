<?php
// $Id: main.php,v 1.9 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%        File Name index.php         %%%%%
define('_MD_XNEWS_PRINTER', 'Druckoptimierte Version');
define('_MD_XNEWS_SENDSTORY', 'Schicke den Artikel an einen Freund');
define('_MD_XNEWS_READMORE', 'mehr...');
define('_MD_XNEWS_COMMENTS', 'Kommentar?');
define('_MD_XNEWS_ONECOMMENT', '1 Kommentar');
define('_MD_XNEWS_BYTESMORE', '%s Bytes mehr');
define('_MD_XNEWS_NUMCOMMENTS', '%s Kommentare');
define('_MD_XNEWS_MORERELEASES', 'Weitere Artikel in: ');

//%%%%%%        File Name submit.php        %%%%%
define('_MD_XNEWS_SUBMITNEWS', 'Artikel schreiben');
define('_MD_XNEWS_TITLE', 'Titel');
define('_MD_XNEWS_TOPIC', 'Thema');
define('_MD_XNEWS_THESCOOP', 'Einleitung');
define('_MD_XNEWS_NOTIFYPUBLISH', 'Per E-Mail benachrichtigen, wenn der Artikel veröffentlicht wird');
define('_MD_XNEWS_POST', 'Veröffentlichen');
define('_MD_XNEWS_GO', 'Los!');
define('_MD_XNEWS_THANKS', 'Danke für die Übermittlung.'); //submission of news article

define('_MD_XNEWS_NOTIFYSBJCT', 'Artikel für deine Seite'); // Notification mail subject
define('_MD_XNEWS_NOTIFYMSG', 'Hallo! Es gibt neue Artikel für deine Website.'); // Notification mail message

//%%%%%%        File Name archive.php        %%%%%
define('_MD_XNEWS_NEWSARCHIVES', 'Archiv');
define('_MD_XNEWS_ARTICLES', 'Artikel');
define('_MD_XNEWS_VIEWS', 'Gelesen');
define('_MD_XNEWS_DATE', 'Datum');
define('_MD_XNEWS_ACTIONS', 'Aktionen');
define('_MD_XNEWS_PRINTERFRIENDLY', 'Druckoptimierte Version');

define('_MD_XNEWS_THEREAREINTOTAL', 'Es gibt insgesamt %s Artikel');

// %s is your site name
define('_MD_XNEWS_INTARTICLE', 'Interessanter Artikel auf %s');
define('_MD_XNEWS_INTARTFOUND', 'Hier ist ein interessanter Artikel den ich auf %s gefunden habe');

define('_MD_XNEWS_TOPICC', 'Thema:');
define('_MD_XNEWS_URL', 'URL:');
define('_MD_XNEWS_NOSTORY', 'Der gewählte Artikel existiert nicht mehr.');

//%%%%%%    File Name print.php     %%%%%

define('_MD_XNEWS_URLFORSTORY', 'Die URL für diesen Artikel lautet:');

// %s represents your site name
define('_MD_XNEWS_THISCOMESFROM', 'Dieser Artikel stammt von %s');

// Added by Herve
define('_MD_XNEWS_ATTACHEDFILES', 'Angehängte Dateien:');
define('_MD_XNEWS_ATTACHEDLIB', 'Dieser Artikel hat angehängte Dateien');
define('_MD_XNEWS_NEWSSAMEAUTHORLINK', 'Artikel vom Autor');
define('_MD_XNEWS_NEWS_NO_TOPICS', 'Kein Themenbereich angelegt, bitte einen anlegen bevor Artikel geschrieben werden.');
define('_MD_XNEWS_PREVIOUS_ARTICLE', 'Voriger Artikel');
define('_MD_XNEWS_NEXT_ARTICLE', 'Nächster Artikel');
define('_MD_XNEWS_OTHER_ARTICLES', 'Weitere Artikel');

// Added by Herve in version 1.3 for rating
define('_MD_XNEWS_RATETHISNEWS', 'Diesen Artikel bewerten');
define('_MD_XNEWS_RATEIT', 'Bewerten');
define('_MD_XNEWS_TOTALRATE', 'Gesamtbewertungen');
define('_MD_XNEWS_RATINGLTOH', 'Bewertung (niedrigste zu höchster)');
define('_MD_XNEWS_RATINGHTOL', 'Bewertung (höchste zu niedrigste)');
define('_MD_XNEWS_RATINGC', 'Bewertung: ');
define('_MD_XNEWS_RATINGSCALE', 'Die Skala reicht von 1 - 10, wobei 1 die schlechteste und 10 die beste ist.');
define('_MD_XNEWS_BEOBJECTIVE', 'Bitte objektiv sein. Wenn alle eine 1 oder 10 erhalten, ist das wenig aussagekräftig.');
define('_MD_XNEWS_DONOTVOTE', 'Bitte nicht für eigene Artikel abstimmen.');
define('_MD_XNEWS_RATING', 'Bewertung');
define('_MD_XNEWS_VOTE', 'Stimme');
define('_MD_XNEWS_NORATING', 'Keine Bewertung ausgewählt.');
define('_MD_XNEWS_USERAVG', 'Durchschnittliche Bewertung');
define('_MD_XNEWS_DLRATINGS', 'Neue Bewertungen (Gesamtstimmen: %s)');
define('_MD_XNEWS_ONEVOTE', '1 Stimme');
define('_MD_XNEWS_NUMVOTES', '%u Stimmen');        // Warning
define('_MD_XNEWS_CANTVOTEOWN', 'Es ist nicht mehr möglich hierfür abzustimmen.<br>Alle Stimmen wurden registriert und ausgewertet.');
define('_MD_XNEWS_VOTEDELETED', 'Abstimmungsdaten gelöscht.');
define('_MD_XNEWS_VOTEONCE', 'Bitte nicht mehrmals für den gleichen Artikel stimmen.');
define('_MD_XNEWS_VOTEAPPRE', 'Die Stimme wurde gezählt.');
define('_MD_XNEWS_THANKYOU', 'Danke für die Teilnahme an der Abstimmung hier bei %s'); // %s is your site name
define('_MD_XNEWS_RSSFEED', 'RSS Feed');    // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MD_XNEWS_AUTHOR', 'Autor');
define('_MD_XNEWS_META_DESCRIPTION', 'Meta Beschreibung');
define('_MD_XNEWS_META_KEYWORDS', 'Meta Schlüsselworte');
define('_MD_XNEWS_MAKEPDF', 'PDF Dokument vom Artikel anfertigen');
define('_MD_POSTEDON', 'Geschrieben am: ');
define('_MD_XNEWS_AUTHOR_ID', 'Autor ID');
define('_MD_XNEWS_POST_SORRY', 'Entweder sind keine Themen definiert, oder es sind nicht die erforderlichen Rechte zum Schreiben vorhanden.');

// Added in v 1.50
define('_MD_XNEWS_LINKS', 'Links');
define('_MD_XNEWS_PAGE', 'Seite');
define('_MD_XNEWS_BOOKMARK_ME', 'Bookmark Artikel auf eine der nachstehenden Seiten');
define('_AM_NEWS_TOTAL', 'Maximal %u Artikel');
define('_AM_NEWS_WHOS_WHO', "Who's Who");
define('_MD_XNEWS_NEWS_LIST_OF_AUTHORS', 'Liste der Autoren auf dieser Website');
define('_AM_NEWS_TOPICS_DIRECTORY', 'Themenverzeichnis');
define('_MD_XNEWS_PAGE_AUTO_SUMMARY', 'Seite %d : %n');

// Added in version 1.51
define('_MD_XNEWS_BOOKMARK_TO_BLINKLIST', 'Bookmark bei Blinklist');
define('_MD_XNEWS_BOOKMARK_TO_DELICIOUS', 'Bookmark bei del.icio.us');
define('_MD_XNEWS_BOOKMARK_TO_DIGG', 'Bookmark bei Digg');
define('_MD_XNEWS_BOOKMARK_TO_FARK', 'Bookmark bei Fark');
define('_MD_XNEWS_BOOKMARK_TO_FURL', 'Bookmark bei Furl');
define('_MD_XNEWS_BOOKMARK_TO_NEWSVINE', 'Bookmark bei Newsvine');
define('_MD_XNEWS_BOOKMARK_TO_REDDIT', 'Bookmark bei Reddit');
define('_MD_XNEWS_BOOKMARK_TO_SIMPY', 'Bookmark bei Simpy');
define('_MD_XNEWS_BOOKMARK_TO_SPURL', 'Bookmark bei Spurl');
define('_MD_XNEWS_BOOKMARK_TO_YAHOO', 'Bookmark bei Yahoo');

// Added in version 1.56
define('_MD_XNEWS_NOTYETSTORY', 'Entschuldigung, der gewählte Artikel wurde noch nicht veröffentlicht. Bitte kommen Sie später wieder und versuche es erneut.');
define('_MD_XNEWS_SELECT_IMAGE', 'Wählen Sie ein Bild aus um dies an die Nachricht anzuhängen');
define('_MD_XNEWS_CURENT_PICTURE', 'Aktuelles Bild');
