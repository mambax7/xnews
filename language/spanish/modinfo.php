<?php
// $Id: modinfo.php,v 1.21 2004/09/01 17:48:07 hthouzard Exp $
// Module Info

// The name of this module
define('_MI_XNEWS_NAME', 'Noticias');

// A brief description of this module
define('_MI_XNEWS_DESC', 'Crear una sección de noticias, donde los usuarios puedan enviar las suyas propias y comentarlas.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_XNEWS_BNAME1', 'Temas');
define('_MI_XNEWS_BNAME3', 'Noticia del día');
define('_MI_XNEWS_BNAME4', 'Noticias populares');
define('_MI_XNEWS_BNAME5', 'Noticias recientes');
define('_MI_XNEWS_BNAME6', 'Moderar noticias');
define('_MI_XNEWS_BNAME7', 'Navegar por los temas');

// Sub menus in main menu block
define('_MI_XNEWS_SMNAME1', 'Enviar noticia');
define('_MI_XNEWS_SMNAME2', 'Historial');

// Names of admin menu items
define('_MI_XNEWS_ADMENU2', 'Gestionar temas');
define('_MI_XNEWS_ADMENU3', 'Enviar/Editar noticias');
define('_MI_XNEWS_GROUPPERMS', 'Administrar Permisos');
// Added by Hervé for prune option
define('_MI_XNEWS_PRUNENEWS', 'Purgar noticias');
// Added by Hervé
define('_MI_XNEWS_EXPORT', 'Exportar noticias');

// Title of config items
define('_MI_XNEWS_STORYHOME', 'Seleccione el número de noticias a mostrar en la página principal');
define('_MI_XNEWS_NOTIFYSUBMIT', 'Seleccione SI para enviar un mensaje de notificación al administrador acerca de nuevos envíos');
define('_MI_XNEWS_DISPLAYNAV', 'Seleccione SI para mostrar la barra de navegación al tope de cada página de noticias');
define('_MI_XNEWS_AUTOAPPROVE', 'Auto aprobar las nuevas noticias sin la intervención del administrador');
define('_MI_XNEWS_ALLOWEDSUBMITGROUPS', 'Grupos que pueden enviar noticias');
define('_MI_XNEWS_ALLOWEDAPPROVEGROUPS', 'Grupos que pueden aprobar noticias');
define('_MI_XNEWS_NEWSDISPLAY', 'Aspecto de las noticias');
define('_MI_XNEWS_NAMEDISPLAY', 'Nombre del autor');
define('_MI_XNEWS_COLUMNMODE', 'Columnas');
define('_MI_XNEWS_STORYCOUNTADMIN', 'Número de noticias nuevas que se mostrarán en el área de administración: (Esta opción se utiliza también para limitar el número de temas mostrados en el área de administración y que será utilizado en las estadísticas) : ');
define('_MI_XNEWS_UPLOADFILESIZE', 'Támaño máximo permitido del contenido a subir(en KB) 1048576 = 1 Meg');
define('_MI_XNEWS_UPLOADGROUPS', 'Grupos autorizados para subir archivos');

// Description of each config items
define('_MI_XNEWS_STORYHOMEDSC', '');
define('_MI_XNEWS_NOTIFYSUBMITDSC', '');
define('_MI_XNEWS_DISPLAYNAVDSC', '');
define('_MI_XNEWS_AUTOAPPROVEDSC', '');
define('_MI_XNEWS_ALLOWEDSUBMITGROUPSDESC', 'Los grupos seleccionados podrán enviar noticias.');
define('_MI_XNEWS_ALLOWEDAPPROVEGROUPSDESC', 'Los grupos seleccionados podrán aprobar noticias.');
define('_MI_XNEWS_NEWSDISPLAYDESC', 'El modo clásico mostrará los documentos ordenados por fecha de publicación. Las noticias por tema serán agrupadas por tema con la última noticia completa y las otras como artículos.');
define('_MI_XNEWS_ADISPLAYNAMEDSC', 'Elija cómo mostrar el nombre del redactor');
define('_MI_XNEWS_COLUMNMODE_DESC', 'Puede elegir qué número de columnas habrá en la lista de noticias mostrada.');
define('_MI_XNEWS_STORYCOUNTADMIN_DESC', '');
define('_MI_XNEWS_UPLOADFILESIZE_DESC', '');
define('_MI_XNEWS_UPLOADGROUPS_DESC', 'Elija qué grupos serán autorizados para subir archivos al servidor.');

// Name of config item values
define('_MI_XNEWS_NEWSCLASSIC', 'Clásico');
define('_MI_XNEWS_NEWSBYTOPIC', 'Por tema');
define('_MI_XNEWS_DISPLAYNAME1', 'Usuario');
define('_MI_XNEWS_DISPLAYNAME2', 'Nombre real');
define('_MI_XNEWS_DISPLAYNAME3', 'No mostrar redactor');
define('_MI_XNEWS_UPLOAD_GROUP1', 'Quenes envían y aprueban');
define('_MI_XNEWS_UPLOAD_GROUP2', 'Solo los que aprueban');
define('_MI_XNEWS_UPLOAD_GROUP3', 'Deshabilitar la subida de archivos');

// Text for notifications
define('_MI_XNEWS_GLOBAL_NOTIFY', 'Global');
define('_MI_XNEWS_GLOBAL_NOTIFYDSC', 'Opciones globales de notificación.');

define('_MI_XNEWS_STORY_NOTIFY', 'Noticia');
define('_MI_XNEWS_STORY_NOTIFYDSC', 'Opciones de notificación que se aplican a la noticia actual.');

define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFY', 'Nuevo tema');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notificarme cuando un nuevo tema sea creado.');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Recibir notificación cuando un nuevo tema es creado.');
define('_MI_XNEWS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notificación : Nuevo tema.');

define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFY', 'Nueva noticia enviada');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYCAP', 'Notificarme cuando cualquier nueva noticia sea enviada (aguardando aprobación).');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYDSC', 'Recibir una notificación cuando cualquier nueva noticia es enviado  (aguardando aprobación).');
define('_MI_XNEWS_GLOBAL_STORYSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notificación : Nueva noticia enviada.');

define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFY', 'Nueva noticia');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYCAP', 'Notificarme cuando cualquier nueva noticia sea publicada.');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYDSC', 'Recibir una notificación cuando cualquier nueva noticia es publicada.');
define('_MI_XNEWS_GLOBAL_NEWSTORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notificación : Nueva noticia publicada.');

define('_MI_XNEWS_STORY_APPROVE_NOTIFY', 'Noticia aprobada');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYCAP', 'Notificarme cuando esta noticia sea aprobada.');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYDSC', 'Recibir una notificación cuando esta noticia sea aprobada.');
define('_MI_XNEWS_STORY_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notificación : Noticia aprobada.');

define('_MI_XNEWS_RESTRICTINDEX', 'Restringir los temas a la página de Inicio');
define('_MI_XNEWS_RESTRICTINDEXDSC', 'Si es activado, los usuarios sólo podrán ver el contenido de las noticias listado en los temas, que tengan acceso a los permisos establecidos en Noticias');

define('_MI_XNEWS_NEWSBYTHISAUTHOR', 'Noticias del mismo redactor');
define('_MI_XNEWS_NEWSBYTHISAUTHORDSC', 'Si se selecciona, un enlace será mostrado para ver las demás noticias publicadas por el redactor.');

define('_MI_XNEWS_PREVNEX_LINK', 'Mostrar enlaces de navegación entre noticia anterior y siguiente');
define('_MI_XNEWS_PREVNEX_LINK_DESC', "Si es activada esta opción, se mostrará al final de cada noticia unos enlaces de navegación \'Noticia anterior\' y \'Noticia siguiente\', el criterio usado para el orden es la fecha de publicación.");
define('_MI_XNEWS_SUMMARY_SHOW', 'Mostrar tabla de resumen');
define('_MI_XNEWS_SUMMARY_SHOW_DESC', 'Si es activada esta opción, se mostrará al final de cada noticia, una tabla con una serie de enlaces a las noticias más recientes.');
define('_MI_XNEWS_AUTHOR_EDIT', 'Permitir a los redactores editar sus envíos');
define('_MI_XNEWS_AUTHOR_EDIT_DESC', 'Mediante esta opción, se autoriza a los redactores para que puedan editar sus propias noticias.');
define('_MI_XNEWS_RATE_NEWS', 'Permitir a los usuarios valorar noticias');
define('_MI_XNEWS_TOPICS_RSS', 'Habilitar RSS en los temas');
define('_MI_XNEWS_TOPICS_RSS_DESC', 'Si es activada esta opción, los contenidos de los temas serán accesibles por RSS.');
define('_MI_XNEWS_DATEFORMAT', 'Formato de la fecha');
define('_MI_XNEWS_DATEFORMAT_DESC',
       "Por favor consulte la <a href='http://fr.php.net/manual/es/function.date.php' target='_blank'>documentación Php</a>, para obtener más información sobre como configurar el formato de fecha y hora.<br> Nota: Si no define ningún formato, se usará el formato por defecto.");
define('_MI_XNEWS_META_DATA', 'Habilitar inclusión de meta datos');
define('_MI_XNEWS_META_DATA_DESC', 'Si es activada esta opción, los usuarios con permisos para enviar noticias podrán incluir datos en la etiqueta Meta, estos son: metakeywords y metadescription');
define('_MI_XNEWS_BNAME8', 'Noticias aleatorias');
define('_MI_XNEWS_NEWSLETTER', 'Boletín de noticias');
define('_MI_XNEWS_STATS', 'Estadísticas');
define('_MI_XNEWS_FORM_OPTIONS', 'Tipo de editor');
define('_MI_XNEWS_FORM_COMPACT', 'Compacto');
define('_MI_XNEWS_FORM_DHTML', 'DHTML');
define('_MI_XNEWS_FORM_SPAW', 'Editor spaw');
define('_MI_XNEWS_FORM_HTMLAREA', 'Editor HtmlArea');
define('_MI_XNEWS_FORM_FCK', ' EditorFCK');
define('_MI_XNEWS_FORM_KOIVI', 'Editor Koivi');
define('_MI_XNEWS_FORM_OPTIONS_DESC', 'Seleccione el editor que desea usar. Para una instalación simple o si no ha instalado ningún editor opcional, solo funcionará el DHTML o el Compacto.');
define('_MI_XNEWS_KEYWORDS_HIGH', 'Usar resaltado de texto');
define('_MI_XNEWS_KEYWORDS_HIGH_DESC', 'Si es activada esta opción, el texto que ha sido buscado en la búsqueda global de la página saldrá resaltado en las noticias al consultar los resultados.');
define('_MI_XNEWS_HIGH_COLOR', 'Color del resaltado');
define('_MI_XNEWS_HIGH_COLOR_DES', 'Solo será usado si ha seleccionado usar el resaltado de texto.');
define('_MI_XNEWS_INFOTIPS', 'Longitud de las viñetas de información');
define('_MI_XNEWS_INFOTIPS_DES', 'Si es activada esta opción, los enlaces relativos a las noticias mostrarán, al poner el ratón encima, los primeros n caracteres de la noticia en una viñeta. Si pone 0 como valor, las viñetas de información no mostrarán nada.');
define('_MI_XNEWS_SITE_NAVBAR', 'Usar barra de navegación del sitio para Mozilla y Opera');
define('_MI_XNEWS_SITE_NAVBAR_DESC', 'Si es activada esta opción, los visitantes de su sitio podrán usar la barra de navegación de sitios que poseen estos navegadores.');
define('_MI_XNEWS_TABS_SKIN', 'Selecione el aspecto de las pestañas');
define('_MI_XNEWS_TABS_SKIN_DESC', 'Permite seleccionar el aspecto de las pestañas en los bloques que las usan');
define('_MI_XNEWS_SKIN_1', 'Barra');
define('_MI_XNEWS_SKIN_2', 'Biselado');
define('_MI_XNEWS_SKIN_3', 'Clásico');
define('_MI_XNEWS_SKIN_4', 'Carpetas');
define('_MI_XNEWS_SKIN_5', 'MacOs');
define('_MI_XNEWS_SKIN_6', 'Plano');
define('_MI_XNEWS_SKIN_7', 'Redondeado');
define('_MI_XNEWS_SKIN_8', 'Estilo ZDnet');

// Added in version 1.50
define('_MI_XNEWS_BNAME9', 'Archivos');
define('_MI_XNEWS_FORM_TINYEDITOR', 'TinyEditor');
define('_MI_XNEWS_FOOTNOTES', '¿Mostrar enlaces en la página de impresión de los noticias?');
define('_MI_XNEWS_DUBLINCORE', '¿Activar Dublin Core Metadata?');
define('_MI_XNEWS_DUBLINCORE_DSC', "Para más información, <a hreh='http://dublincore.org/'>visite este enlace</a>");
define('_MI_XNEWS_BOOKMARK_ME', "¿Mostrar el bloque 'Agregue esta noticia a favoritos'?");
define('_MI_XNEWS_BOOKMARK_ME_DSC', 'Este bloque se mantendrá visible en la página de la noticia');
define('_MI_XNEWS_FF_MICROFORMAT', '¿Activar Firefox 2 Micro Summaries?');
define('_MI_XNEWS_FF_MICROFORMAT_DSC', "Para más información, visiste <a href='http://wiki.mozilla.org/Microsummaries' target='_blank'>esta página</a>");
define('_MI_XNEWS_WHOS_WHO', 'Quién es quién');
define('_MI_XNEWS_METAGEN', 'Metagen');
define('_MI_XNEWS_TOPICS_DIRECTORY', 'Directorio de Temas');
define('_MI_XNEWS_ADVERTISEMENT', 'Publicidad');
define('_MI_XNEWS_ADV_DESCR', 'Inserte el texto o código javascript a mostrar en sus noticias');
define('_MI_XNEWS_MIME_TYPES', 'Introduzca los Mime Types permitidos para subir/upload (separados por líneas)');
define('_MI_XNEWS_ENHANCED_PAGENAV', '¿Utilizar el navegador mejorado de páginas?');
define('_MI_XNEWS_ENHANCED_PAGENAV_DSC', 'Con esta opción podrá separar las páginas con algo así como : [pagrebreak:Page Title], los enlaces a las páginas son reemplazados por un menú desplegalbe. También podrá usar [sumary] para crear un sumario automático de las páginas.');

// Added in version 1.54
define('_MI_XNEWS_CATEGORY_NOTIFY', 'Categoría');
define('_MI_XNEWS_CATEGORY_NOTIFYDSC', 'Opciones de notificación que se aplicarán a la categoría actual');

define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFY', 'Nueva Noticia enviada');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYCAP', 'Recibir notificación sobre los futuros contenidos de la categoría.');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYDSC', 'Recibir notificación sobre la publicación de nuevos contenidos en esta categoría.');
define('_MI_XNEWS_CATEGORY_STORYPOSTED_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notificación : Nuevo contenido añadido');

// Added in version 1.63
define('_MI_XNEWS_TAGS', '¿Usar sistema de etiquetas?');
define('_MI_XNEWS_TAGS_DSC', 'Esto se basa en el módulo de etiquetas de XOOPS phppp');
define('_MI_XNEWS_BNAME10', 'Nube de Etiquetas');
define('_MI_XNEWS_BNAME11', 'Etiquetas destacadas');
define('_MI_XNEWS_INTRO_TEXT', 'Texto introductorio para mostrar en la página de envío');
define('_MI_XNEWS_IMAGE_MAX_WIDTH', 'Ancho máximo de la imagen cuando se redimensiona');
define('_MI_XNEWS_IMAGE_MAX_HEIGHT', 'Altura máxima de la imagen cuando se redimensiona');

// Added in version 1.67
define('_MI_XNEWS_CLONER', 'Gestor de Clones');

define('_MI_XNEWS_LATESTNEWS_BLOCK', 'Bloque Últimas Noticias');

// Added in version 1.68 BETA
define('_MI_XNEWS_TOPICDISPLAY', 'Mostrar temas ');
define('_MI_XNEWS_TOPICDISPLAYDESC', 'Esto habilita/desabilita Temas en el título de cabeceras');

define('_MI_XNEWS_SEOENABLE', 'Habilitar SEO');
define('_MI_XNEWS_SEOENABLEDESC',
       'Esto habilita/desabilita la actividad SEO.<br> Si <b>htaccess</b> se selecciona usted obtendrá:<br> http://su.sitio.com/<b>xnews</b>/topics.1/titulo-de-su-tema.html <br> Si selecciona path-info is selected usted obtendrá: <br> http://su.sitio.com/modules/xnews/index.php/topics.1/titulo-de_su-tema.html');

// Added in version 1.68 RC1
define('_MI_XNEWS_EXTEND_META_DATA', 'Amplíe los meta-data de entrada');
define('_MI_XNEWS_EXTEND_META_DATA_DESC', 'Alterna entre el texto y textarea para que el usuario edite la entrada meta-data<br> tanto para la descripción como para las palabras clave.');

define('_MI_XNEWS_NONE', 'Ninguno');
define('_MI_XNEWS_TOPONLY', 'Solo arriba');
define('_MI_XNEWS_BOTTOMONLY', 'Solo abajo');
define('_MI_XNEWS_BOTH', 'Ambos');
define('_MI_XNEWS_DISPLAYLINKICNS', 'Mostrar enlaces de iconos');
define('_MI_XNEWS_DISPLAYLINKICNSDESC', 'Mostrar impresión amigable e iconos de pdf ninguno-arriba-abajo-ambos');

define('_MI_XNEWS_SEOPATH', 'Ruta SEO');
define('_MI_XNEWS_SEOPATHDESC',
       'Esto agregará un título a la URL de SEO para ambos modos <b>htaccess</b> y <b>path-info</b>. <br> Si se deja vacío usted obtendrá: <br> http://su.sitio.com/topics.1/titulo-de-su-tema.html <br> Si escribe <b>noticias</b> usted obtendrá: <br> http://su.sitio.com/<b>noticias</b>/topics.1/titulo-de-su-tema.html <br><br> <b>son aceptados caracteres de a-z y el caracter menos(-). ej. noticia-del-dia</b>');
define('_MI_XNEWS_SEOLEVEL', 'Nivel SEO');
define('_MI_XNEWS_SEOLEVELDESC',
       'Aquí puedes elegir si tener htaccess en la raíz de xoops o en el módulo. <br> Esto cambiará la apariencia de la URL.<br> En el Nivel Raíz: <br> http://su.sitio.com/news/topics.1/titulo-de-su-tema.html <br> En el nivel Módulo: <br> http://su.sitio.com/modules/xnews/news.topics.1/titulo-de-su-tema.html<br><br> Solo trabaja en el modo htaccess y el archivo htaccess tendrá que ser instalado en consecuencia.');
define('_MI_XNEWS_MODULE_LEVEL', 'Nivel Módulo');
define('_MI_XNEWS_ROOT_LEVEL', 'Nivel Raíz');

//ADDED wishcraft 1.68
define('_MI_XNEWS_SEOENDOFURL', 'End of URL');
define('_MI_XNEWS_SEOENDOFURL_DESC', 'File Extension to HTML Files');
define('_MI_XNEWS_SEOENDOFURLRSS', 'End of URL');
define('_MI_XNEWS_SEOENDOFURLRSS_DESC', 'File Extension to RSS Pages');
define('_MI_XNEWS_SEOENDOFURLPDF', 'End of URL');
define('_MI_XNEWS_SEOENDOFURLPDF_DESC', 'File Extension to Adobe Acrobat (PDF) Files');

define('_MI_XNEWS_HOME', 'Home');
define('_MI_XNEWS_ABOUT', 'About');
