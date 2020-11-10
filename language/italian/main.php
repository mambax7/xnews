<?php
// $Id: main.php,v 1.70 2010/09/01 17:48:07 dnprossi Exp $
//%%%%%%        File Name index.php         %%%%%
define('_MD_XNEWS_PRINTER', 'Pagina stampabile');
define('_MD_XNEWS_SENDSTORY', 'Invia questa notizia a un amico');
define('_MD_XNEWS_READMORE', 'Leggi tutto...');
define('_MD_XNEWS_COMMENTS', 'Commenti?');
define('_MD_XNEWS_ONECOMMENT', '1 commento');
define('_MD_XNEWS_BYTESMORE', 'altri %s bytes');
define('_MD_XNEWS_NUMCOMMENTS', '%s commenti');
define('_MD_XNEWS_MORERELEASES', 'Ulteriori rilasci in ');

//%%%%%%        File Name submit.php        %%%%%
define('_MD_XNEWS_SUBMITNEWS', 'Invia Notizia');
define('_MD_XNEWS_TITLE', 'Titolo');
define('_MD_XNEWS_TOPIC', 'Argomento');
define('_MD_XNEWS_THESCOOP', 'La notizia');
define('_MD_XNEWS_NOTIFYPUBLISH', 'Notifica via email nel momento in cui verrà pubblicata');
define('_MD_XNEWS_POST', 'Invia');
define('_MD_XNEWS_GO', 'Vai!');
define('_MD_XNEWS_THANKS', 'Grazie del tuo contributo!'); //submission of news article

define('_MD_XNEWS_NOTIFYSBJCT', 'Notizia per il mio sito'); // Notification mail subject
define('_MD_XNEWS_NOTIFYMSG', 'Hey! Hai ricevuto un nuova notizia per il tuo sito.'); // Notification mail message

//%%%%%%        File Name archive.php       %%%%%
define('_MD_XNEWS_NEWSARCHIVES', 'Archivo Notizie');
define('_MD_XNEWS_ARTICLES', 'Notizie');
define('_MD_XNEWS_VIEWS', 'Letture');
define('_MD_XNEWS_DATE', 'Data');
define('_MD_XNEWS_ACTIONS', 'Azione');
define('_MD_XNEWS_PRINTERFRIENDLY', 'Pagina stampabile');

define('_MD_XNEWS_THEREAREINTOTAL', 'Ci sono in tutto %s notizie');

// %s is your site name
define('_MD_XNEWS_INTARTICLE', 'Ho trovato una notizia interessante su %s');
define('_MD_XNEWS_INTARTFOUND', 'Ecco una notizia interessante che ho trovato su %s');

define('_MD_XNEWS_TOPICC', 'Argomento:');
define('_MD_XNEWS_URL', 'Indirizzo:');
define('_MD_XNEWS_NOSTORY', "Spiacenti, ma l'articolo selezionato non esiste.");

//%%%%%%    File Name print.php     %%%%%

define('_MD_XNEWS_URLFORSTORY', "L'indirizzo di questa notizia è:");

// %s represents your site name
define('_MD_XNEWS_THISCOMESFROM', 'Questa notizia proviene da %s');

// Added by Hervé
define('_MD_XNEWS_ATTACHEDFILES', 'File allegati:');
define('_MD_XNEWS_ATTACHEDLIB', 'Questo articolo ha dei file allegati');
define('_MD_XNEWS_NEWSSAMEAUTHORLINK', 'Notizie dallo stesso autore');
define('_MD_XNEWS_NO_TOPICS', 'Spiacente, ma non esiste alcun argomento, creane uno prima di inserire una notizia');
define('_MD_XNEWS_PREVIOUS_ARTICLE', 'Articolo Precedente');
define('_MD_XNEWS_NEXT_ARTICLE', 'Articolo Successivo');
define('_MD_XNEWS_OTHER_ARTICLES', 'Altri Articoli');

// Added by Hervé in version 1.3 for rating
define('_MD_XNEWS_RATETHISNEWS', 'Vota questa notizia');
define('_MD_XNEWS_RATEIT', 'Votala!');
define('_MD_XNEWS_TOTALRATE', 'Voti totali');
define('_MD_XNEWS_RATINGLTOH', 'Rango (Ascendente)');
define('_MD_XNEWS_RATINGHTOL', 'Rango (Discendente)');
define('_MD_XNEWS_RATINGC', 'Rango: ');
define('_MD_XNEWS_RATINGSCALE', 'La scala è da 1 a 10, 1 è pessimo e 10 eccellente.');
define('_MD_XNEWS_BEOBJECTIVE', 'Per favore, cerca di essere oggettivo, se tutti votano 1 o 10 il rango non serve a molto.');
define('_MD_XNEWS_DONOTVOTE', 'Non votare per le risorse proposte da te.');
define('_MD_XNEWS_RATING', 'Rango');
define('_MD_XNEWS_VOTE', 'Vota');
define('_MD_XNEWS_NORATING', 'Nessun rango selezionato.');
define('_MD_XNEWS_USERAVG', 'Voto Medio Utente');
define('_MD_XNEWS_DLRATINGS', 'Rango Notizia (voti totali: %s)');
define('_MD_XNEWS_ONEVOTE', '1 voto');
define('_MD_XNEWS_NUMVOTES', '%u voti');        // Warning
define('_MD_XNEWS_CANTVOTEOWN', 'Non puoi votare sulle risorse inserite da te.<br>Tutti i voti sono memorizzati e verificati.');
define('_MD_XNEWS_VOTEDELETED', 'Dati sul voto cancellati.');
define('_MD_XNEWS_VOTEONCE', 'Per favore, non votare due volte la stessa risorsa.');
define('_MD_XNEWS_VOTEAPPRE', 'Apprezziamo il tuo voto.');
define('_MD_XNEWS_THANKYOU', 'Grazie per aver speso il tempo di votare su %s'); // %s is your site name
define('_MD_XNEWS_RSSFEED', 'RSS Feed');    // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MD_XNEWS_AUTHOR', 'Autore');
define('_MD_XNEWS_META_DESCRIPTION', 'Meta description');
define('_MD_XNEWS_META_KEYWORDS', 'Meta keywords');
define('_MD_XNEWS_MAKEPDF', "Crea un PDF dall'articolo");
define('_MD_XNEWS_POSTEDON', 'Pubblicato il : ');
define('_MD_XNEWS_AUTHOR_ID', 'ID Autore');
define('_MD_XNEWS_POST_SORRY', "Spiacente, ma o non ci sono argomenti, oppure non hai i permessi di inviare in nessun argomento. Se sei il webmaster, vai in 'permessi' e controlla i permessi di invio.");

// Added in v 1.50
define('_MD_XNEWS_LINKS', 'Links');
define('_MD_XNEWS_PAGE', 'Pagina');
define('_MD_XNEWS_BOOKMARK_ME', 'Inserisci questo articolo come segnalibro');
define('_AM_XNEWS_TOTAL', 'Totale articoli di %u');
define('_AM_XNEWS_WHOS_WHO', 'Chi è chi');
define('_MD_XNEWS_LIST_OF_AUTHORS', 'Questa è una lista degli autori del sito, clicca sul nome per visualizzare i suoi articoli');
define('_AM_XNEWS_TOPICS_DIRECTORY', 'Lista degli argomenti');
define('_MD_XNEWS_PAGE_AUTO_SUMMARY', 'Pagina %d : %s');

// Added in version 1.51
define('_MD_XNEWS_BOOKMARK_TO_BLINKLIST', 'Bookmark to Blinklist');
define('_MD_XNEWS_BOOKMARK_TO_DELICIOUS', 'Bookmark to del.icio.us');
define('_MD_XNEWS_BOOKMARK_TO_DIGG', 'Bookmark to Digg');
define('_MD_XNEWS_BOOKMARK_TO_FARK', 'Bookmark to Fark');
define('_MD_XNEWS_BOOKMARK_TO_FURL', 'Bookmark to Furl');
define('_MD_XNEWS_BOOKMARK_TO_NEWSVINE', 'Bookmark to Newsvine');
define('_MD_XNEWS_BOOKMARK_TO_REDDIT', 'Bookmark to Reddit');
define('_MD_XNEWS_BOOKMARK_TO_SIMPY', 'Bookmark to Simpy');
define('_MD_XNEWS_BOOKMARK_TO_SPURL', 'Bookmark to Spurl');
define('_MD_XNEWS_BOOKMARK_TO_YAHOO', 'Bookmark to Yahoo');

// Added in version 1.56
define('_MD_XNEWS_NOTYETSTORY', 'La notizia selezionata non è stata pubblicata. Prego provare più tardi.');
define('_MD_XNEWS_SELECT_IMAGE', 'Immagine per la notizia');
define('_MD_XNEWS_CURENT_PICTURE', 'Immagine corrente');

// Added in version 1.68
define('_MD_XNEWS_SP', ':');
define('_MD_XNEWS_POSTED', 'Inviato');

// Added in version 1.68 RC1
define('_MD_XNEWS_NO_COMMENT', 'Nessun Commento');
define('_MD_XNEWS_METASIZE', "Sorry, you may not add more than '+len+' characters into the text area box you just completed.");

// Added in version 1.68 RC3
define('_MD_XNEWS_SEO_TOPICS', 'argomenti');
define('_MD_XNEWS_SEO_ARTICLES', 'articoli');
define('_MD_XNEWS_SEO_PRINT', 'stampa');
define('_MD_XNEWS_SEO_PDF', 'pdf');
