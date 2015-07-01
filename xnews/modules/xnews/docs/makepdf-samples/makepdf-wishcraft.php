<?php

	error_reporting(0);
	include_once 'header.php';
	$myts =& MyTextSanitizer::getInstance();
	
	include_once NW_MODULE_PATH . '/class/class.newsstory.php';
	include_once NW_MODULE_PATH . '/class/class.sfiles.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
	include_once NW_MODULE_PATH . '/include/functions.php';
	include_once NW_MODULE_PATH . '/class/class.newstopic.php';
	include_once NW_MODULE_PATH . '/class/keyhighlighter.class.php';
	include_once NW_MODULE_PATH . '/config.php';
	
	// Verifications on the article
	$storyid = isset($_GET['storyid']) ? intval($_GET['storyid']) : 0;
	
	if (empty($storyid))  {
		redirect_header(NW_MODULE_URL . '/index.php',2,_MA_NW_NOSTORY);
		exit();
	}
	
	$sql = "SELECT a.topic_title, b.title FROM ".$GLOBALS['xoopsDB']->prefix('nw_stories')." b INNER JOIN ".$GLOBALS['xoopsDB']->prefix('nw_topics')." a on b.topicid = a.topic_id where b.storyid = $storyid";
	$ret = $GLOBALS['xoopsDB']->query($sql);
	$row = $GLOBALS['xoopsDB']->fetchArray($ret);
	$url = 	XOOPS_URL."/".$GLOBALS['xoopsModuleConfig']['seopath']."/".xoops_sef($row['topic_title'])."/".xoops_sef($row['title'])."/pdf,".$_REQUEST['storyid'].$GLOBALS['xoopsModuleConfig']['seo_endofurl_pdf'];
	
	if (!strpos($url, $_SERVER['REQUEST_URI'])&&$GLOBALS['xoopsModuleConfig']['seo_enable']==1) {
		header( "HTTP/1.1 301 Moved Permanently" ); 
		header( "Location: $url");
		exit(0);
	}
	
	xoops_loadLanguage('user');
	if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
		include_once $GLOBALS['xoops']->path( "/class/template.php" );
		$GLOBALS['xoopsTpl'] = new xoopsTpl();
	}

	$article = new nw_NewsStory($storyid);
	if ( $article->published() == 0 || $article->published() > time() ) {
		redirect_header(NW_MODULE_URL . '/index.php', 2, _MA_NW_NOTYETSTORY);
		exit();
	}
	// Expired
	if ( $article->expired() != 0 && $article->expired() < time() ) {
		redirect_header(NW_MODULE_URL . '/index.php', 2, _MA_NW_NOSTORY);
		exit();
	}
	
	$gperm_handler =& xoops_gethandler('groupperm');
	if (is_object($xoopsUser)) {
		$groups = $xoopsUser->getGroups();
	} else {
		$groups = array(XOOPS_GROUP_ANONYMOUS => XOOPS_GROUP_ANONYMOUS);
	}
	if (!$gperm_handler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
		redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
		exit();
	}
	
	$storypage = isset($_GET['page']) ? intval($_GET['page']) : 0;
	$dateformat = nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
	$hcontent='';
	
	/**
	 * update counter only when viewing top page and when you are not the author or an admin
	 */
	if (empty($_GET['com_id']) && $storypage == 0) {
		if(is_object($xoopsUser)) {
			if( ($xoopsUser->getVar('uid')==$article->uid()) || nw_is_admin_group()) {
				// nothing ! ;-)
			} else {
				$article->updateCounter();
			}
		} else {
			$article->updateCounter();
		}
	}

	//DNPROSSI - ADDED
	$seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);
	
	$GLOBALS['xoopsTpl']->assign('newsmodule_url', NW_MODULE_URL);
	
	$story['id'] = $storyid;
	$story['posttime'] = formatTimestamp($article->published(),$dateformat);
	$story['news_title'] = $article->title();
	$story['title'] = $article->textlink().'&nbsp;:&nbsp;'.$article->title();
	$story['topic_title'] = $article->textlink();
	$story['topic_separator'] =  ( $article->textlink() != '' ) ? _MA_NW_SP : '';
	
	$story['text'] = $article->hometext();
	$bodytext = $article->bodytext();
	
	if (xoops_trim($bodytext) != '') {
		$articletext = array();
		if(nw_getmoduleoption('enhanced_pagenav', NW_MODULE_DIR_NAME)) {
			$articletext = preg_split('/(\[pagebreak:|\[pagebreak)(.*)(\])/iU', $bodytext);
			$arr_titles = array();
			$auto_summary = $article->auto_summary($bodytext, $arr_titles);
			$bodytext = str_replace('[summary]', $auto_summary, $bodytext);
			$articletext[$storypage] = str_replace('[summary]', $auto_summary, $articletext[$storypage]);
			$story['text'] = str_replace('[summary]', $auto_summary, $story['text']);
		} else {
			$articletext = explode('[pagebreak]', $bodytext);
		}
	
		$story_pages = count($articletext);
	
		if ($story_pages > 1) {
			include_once NW_MODULE_PATH . '/include/pagenav.php';
			$pagenav = new XoopsPageNav($story_pages, 1, $storypage, 'page', 'storyid='.$storyid);
			if(nw_isbot()) { 		// A bot is reading the articles, we are going to show him all the links to the pages
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav($story_pages));
			} else {
				if(nw_getmoduleoption('enhanced_pagenav', NW_MODULE_DIR_NAME)) {
					$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderEnhancedSelect(true, $arr_titles));
				} else {
					$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
				}
			}
	
			if ($storypage == 0) {
				$story['text'] = $story['text'].'<br />'.nw_getmoduleoption('advertisement', NW_MODULE_DIR_NAME).'<br />'.$articletext[$storypage];
			} else {
				$story['text'] = $articletext[$storypage];
			}
		} else {
			$story['text'] = $story['text'].'<br />'.nw_getmoduleoption('advertisement', NW_MODULE_DIR_NAME).'<br />'.$bodytext;
		}
	}
	// Publicité
	$GLOBALS['xoopsTpl']->assign('advertisement', nw_getmoduleoption('advertisement', NW_MODULE_DIR_NAME));
	
	// ****************************************************************************************************************
	function my_highlighter ($matches) {
		$color = nw_getmoduleoption('highlightcolor', NW_MODULE_DIR_NAME);
		if(substr($color,0,1)!='#') {
			$color='#'.$color;
		}
		return '<span style="font-weight: bolder; background-color: '.$color.';">' . $matches[0] . '</span>';
	}
	
	$highlight = false;
	$highlight = nw_getmoduleoption('keywordshighlight', NW_MODULE_DIR_NAME);
	
	if($highlight && isset($_GET['keywords']))
	{
		$keywords=$myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
		$h= new nw_keyhighlighter ($keywords, true , 'my_highlighter');
		$story['text'] = $h->highlight($story['text']);
	}
	// ****************************************************************************************************************
	
	$story['poster'] = $article->uname();
	if ( $story['poster'] ) {
		$story['posterid'] = $article->uid();
		$story['poster'] = '<a href="'.XOOPS_URL.'/userinfo.php?uid='.$story['posterid'].'">'.$story['poster'].'</a>';
		$tmp_user = new XoopsUser($article->uid());
		$story['poster_avatar'] = XOOPS_UPLOAD_URL.'/'.$tmp_user->getVar('user_avatar');
		$story['poster_signature'] = $tmp_user->getVar('user_sig');
		$story['poster_email'] = $tmp_user->getVar('email');
		$story['poster_url'] = $tmp_user->getVar('url');
		$story['poster_from'] = $tmp_user->getVar('user_from');
		unset($tmp_user);
	} else {
		$story['poster'] = '';
		$story['posterid'] = 0;
		$story['poster_avatar'] = '';
		$story['poster_signature'] = '';
		$story['poster_email'] = '';
		$story['poster_url'] = '';
		$story['poster_from'] = '';
		if(nw_getmoduleoption('displayname', NW_MODULE_DIR_NAME)!=3) {
			$story['poster'] = $xoopsConfig['anonymous'];
		}
	}
	$story['morelink'] = '';
	$story['adminlink'] = '';
	unset($isadmin);
	
	if(is_object($xoopsUser)) {
		if( $xoopsUser->isAdmin($xoopsModule->getVar('mid')) || (nw_getmoduleoption('authoredit', NW_MODULE_DIR_NAME) && $article->uid() == $xoopsUser->getVar('uid')) ) {
			$isadmin = true;
			$story['adminlink'] = $article->adminlink();
		}
	}
	$story['topicid'] = $article->topicid();
	$story['topic_color'] = '#'.$myts->displayTarea($article->topic_color);
	
	$story['imglink'] = '';
	$story['align'] = '';
	if ( $article->topicdisplay() ) {
		$story['imglink'] = $article->imglink();
		$story['align'] = $article->topicalign();
	}
	$story['hits'] = $article->counter();
	$story['mail_link'] = 'mailto:?subject='.sprintf(_MA_NW_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_MA_NW_INTARTFOUND, $xoopsConfig['sitename']).':  '.NW_MODULE_URL . '/article.php?storyid='.$article->storyid();
	$GLOBALS['xoopsTpl']->assign('lang_printerpage', _MA_NW_PRINTERFRIENDLY);
	$GLOBALS['xoopsTpl']->assign('lang_sendstory', _MA_NW_SENDSTORY);
	$GLOBALS['xoopsTpl']->assign('lang_pdfstory', _MA_NW_MAKEPDF);
	
	if ( $article->uname() != '' ) 
	{
		$GLOBALS['xoopsTpl']->assign('lang_on', _ON);
		$GLOBALS['xoopsTpl']->assign('lang_postedby', _POSTEDBY);
	} else 
	{
		$GLOBALS['xoopsTpl']->assign('lang_on', ''._MA_NW_POSTED.' '._ON.' ');
		$GLOBALS['xoopsTpl']->assign('lang_postedby', '');
	}
	
	$GLOBALS['xoopsTpl']->assign('lang_reads', _READS);
	$GLOBALS['xoopsTpl']->assign('mail_link', 'mailto:?subject='.sprintf(_MA_NW_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_MA_NW_INTARTFOUND, $xoopsConfig['sitename']).':  '.NW_MODULE_URL . '/article.php?storyid='.$article->storyid());
	
	$GLOBALS['xoopsTpl']->assign('lang_attached_files',_MA_NW_ATTACHEDFILES);

	$sfiles = new nw_sFiles();
	$filesarr = $newsfiles = array();
	$filesarr=$sfiles->getAllbyStory($storyid);
	$filescount=count($filesarr);
	$GLOBALS['xoopsTpl']->assign('attached_files_count',$filescount);
	if($filescount>0) {
		foreach ($filesarr as $onefile)	{
			if ( strstr($onefile->getMimetype(), 'image') ) {
				$mime = 'image';
				$newsfiles[]=Array('visitlink' => NW_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname(), 'file_realname'=>$onefile->getFileRealName(), 'file_mimetype'=>$mime);
				//trigger_error($mime, E_USER_WARNING); 
			} else {
				$newsfiles[]=Array('file_id'=>$onefile->getFileid(), 'visitlink' => NW_MODULE_URL . '/visit.php?fileid='.$onefile->getFileid(),'file_realname'=>$onefile->getFileRealName(), 'file_attacheddate'=>formatTimestamp($onefile->getDate(),$dateformat), 'file_mimetype'=>$onefile->getMimetype(), 'file_downloadname'=>NW_ATTACHED_FILES_URL.'/'.$onefile->getDownloadname());
			}
		}
		$GLOBALS['xoopsTpl']->assign('attached_files',$newsfiles);
	}
	
	/**
	 * Create page's title
	*/
	$complement = '';
	if(nw_getmoduleoption('enhanced_pagenav', NW_MODULE_DIR_NAME) && (isset($arr_titles) && is_array($arr_titles) && isset($arr_titles,$storypage) && $storypage>0)) {
		$complement = ' - '.$arr_titles[$storypage];
	}
	
	if(nw_getmoduleoption('newsbythisauthor', NW_MODULE_DIR_NAME)) {
		$GLOBALS['xoopsTpl']->assign('news_by_the_same_author_link',sprintf("<a href='%s?uid=%d'>%s</a>",NW_MODULE_URL . '/newsbythisauthor.php',$article->uid(),_MA_NW_NEWSSAMEAUTHORLINK));
	}
	
	/**
	 * Create a clickable path from the root to the current topic (if we are viewing a topic)
	 * Actually this is not used in the default's templates but you can use it as you want
	 * Uncomment the code to be able to use it
	 */
	if($cfg['create_clickable_path']) {
		$mytree = new XoopsTree($GLOBALS['xoopsDB']->prefix('nw_topics'),'topic_id','topic_pid');
		$topicpath = $mytree->getNicePathFromId($article->topicid(), 'topic_title', 'index.php?op=1');
		$GLOBALS['xoopsTpl']->assign('topic_path', $topicpath);
		unset($mytree);
	}
	
	/**
	 * Summary table
	 *
	 * When you are viewing an article, you can see a summary table containing
	 * the first n links to the last published news.
	 * This summary table is visible according to a module's option (showsummarytable)
	 * The number of items is equal to the module's option "storyhome" ("Select the number
	 * of news items to display on top page")
	 * We also use the module's option "restrictindex" ("Restrict Topics on Index Page"), like
	 * this you (the webmaster) select if users can see restricted stories or not.
	 */
	if (nw_getmoduleoption('showsummarytable', NW_MODULE_DIR_NAME)) {
		$GLOBALS['xoopsTpl']->assign('showsummary', true);
		$GLOBALS['xoopsTpl']->assign('lang_other_story',_MA_NW_OTHER_ARTICLES);
		$count=0;
		$tmparticle = new nw_NewsStory();
		$infotips=nw_getmoduleoption('infotips', NW_MODULE_DIR_NAME);
		$sarray = $tmparticle->getAllPublished($cfg['article_summary_items_count'], 0, $xoopsModuleConfig['restrictindex']);
		if(count($sarray)>0) {
			foreach ($sarray as $onearticle) {
				$count++;
				$htmltitle='';
				$tooltips='';
				$htmltitle='';
				if($infotips>0) {
					$tooltips = nw_make_infotips($onearticle->hometext());
					$htmltitle=' title="'.$tooltips.'"';
				}
				//DNPROSSI SEO
				$story_path = '';
				if ( $seo_enabled != 0 ) {
					$story_path = nw_remove_accents($onearticle->title());
					$storyTitle = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $onearticle->storyid(), $story_path) . "'>" . $onearticle->title() . "</a>";
					$GLOBALS['xoopsTpl']->append('summary', array('story_id'=>$onearticle->storyid(), 'htmltitle'=>$htmltitle, 'infotips'=>$tooltips, 'story_title'=>$storyTitle, 'story_hits'=>$onearticle->counter(), 'story_published'=>formatTimestamp($onearticle->published,$dateformat)));
				} else {
					$GLOBALS['xoopsTpl']->append('summary', array('story_id'=>$onearticle->storyid(), 'htmltitle'=>$htmltitle, 'infotips'=>$tooltips, 'story_title'=>$onearticle->title(), 'story_hits'=>$onearticle->counter(), 'story_published'=>formatTimestamp($onearticle->published,$dateformat)));
				}
			}
		}
		$GLOBALS['xoopsTpl']->assign('summary_count',$count);
		unset($tmparticle);
	} else {
		$GLOBALS['xoopsTpl']->assign('showsummary', false);
	}
	
	/**
	 * Show a link to go to the previous article and to the next article
	 *
	 * According to a module's option "showprevnextlink" ("Show Previous and Next link ?")
	 * you can display, at the bottom of each article, two links used to navigate thru stories.
	 * This feature uses the module's option "restrictindex" so that we can, or can't see
	 * restricted stories
	 */
	if (nw_getmoduleoption('showprevnextlink', NW_MODULE_DIR_NAME)) {
		$GLOBALS['xoopsTpl']->assign('nav_links', nw_getmoduleoption('showprevnextlink', NW_MODULE_DIR_NAME));
		$tmparticle = new nw_NewsStory();
		$nextId = $previousId = -1;
		$next = $previous = array();
	
		$previousTitle = $nextTitle = '';
	
		$next = $tmparticle->getNextArticle($storyid, $xoopsModuleConfig['restrictindex']);
		if(count($next) > 0) {
			$nextId = $next['storyid'];
			$nextTitle = $next['title'];
		}
	
		$previous = $tmparticle->getPreviousArticle($storyid, $xoopsModuleConfig['restrictindex']);
		if(count($previous) > 0) {
			$previousId = $previous['storyid'];
			$previousTitle = $previous['title'];
		}
	
		$GLOBALS['xoopsTpl']->assign('previous_story_id',$previousId);
		$GLOBALS['xoopsTpl']->assign('next_story_id',$nextId);
		if($previousId > 0) {
			$GLOBALS['xoopsTpl']->assign('previous_story_title',$previousTitle);
			$hcontent.=sprintf("<link rel=\"Prev\" title=\"%s\" href=\"%s/\" />\n", $previousTitle, NW_MODULE_URL . '/article.php?storyid='.$previousId);
		
			//DNPROSSI SEO
			$item_path = '';
			if ( $seo_enabled != 0 ) $item_path = nw_remove_accents($previousTitle); 
			$prevStory = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $previousId, $item_path) . "' title='" . _MA_NW_PREVIOUS_ARTICLE . "'>";
			$prevStory .= "<img src='" . NW_MODULE_URL . "/images/leftarrow22.png' border='0' alt='" . _MA_NW_PREVIOUS_ARTICLE . "'/></a>";
			$GLOBALS['xoopsTpl']->assign('previous_story', $prevStory);
		}
	
		if($nextId > 0) {
			//DNPROSSI SEO
			$item_path = '';
			if ( $seo_enabled != 0 ) $item_path = nw_remove_accents($nextTitle);		
			$nextStory = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $nextId, $item_path) . "' title='" . _MA_NW_NEXT_ARTICLE . "'>";
			$nextStory .= "<img src='" . NW_MODULE_URL . "/images/rightarrow22.png' border='0' alt='" . _MA_NW_NEXT_ARTICLE . "'/></a>";
			$GLOBALS['xoopsTpl']->assign('next_story', $nextStory); 		 		
			
			$hcontent.=sprintf("<link rel=\"Next\" title=\"%s\" href=\"%s/\" />\n", $nextTitle, NW_MODULE_URL . '/article.php?storyid='.$nextId);
		}
		
		$GLOBALS['xoopsTpl']->assign('lang_previous_story',_MA_NW_PREVIOUS_ARTICLE);
		$GLOBALS['xoopsTpl']->assign('lang_next_story',_MA_NW_NEXT_ARTICLE);
		unset($tmparticle);
	} else {
		$GLOBALS['xoopsTpl']->assign('nav_links', 0);
	}
	
	/**
	 * Manage all the meta datas
	 */
	nw_CreateMetaDatas($article);
	
	/**
	 * Show a "Bookmark this article at these sites" block ?
	 */
	if(nw_getmoduleoption('bookmarkme', NW_MODULE_DIR_NAME)) {
		$GLOBALS['xoopsTpl']->assign('bookmarkme', true);
		$GLOBALS['xoopsTpl']->assign('encoded_title', rawurlencode($article->title()));
	} else {
		$GLOBALS['xoopsTpl']->assign('bookmarkme', false);
	}
	
	
	/**
	 * Enable users to vote
	 *
	 * According to a module's option, "ratenews", you can display a link to rate the current news
	 * The actual rate in showed (and the number of votes)
	 * Possible modification, restrict votes to registred users
	 */
	$other_test = true;
	if($cfg['config_rating_registred_only']) {
		if(isset($xoopsUser) && is_object($xoopsUser)) {
			$other_test = true;
		} else {
			$other_test = false;
		}
	}
	
	if (nw_getmoduleoption('ratenews', NW_MODULE_DIR_NAME) && $other_test) {
		$GLOBALS['xoopsTpl']->assign('rates', true);
		$GLOBALS['xoopsTpl']->assign('lang_ratingc', _MA_NW_RATINGC);
		$GLOBALS['xoopsTpl']->assign('lang_ratethisnews', _MA_NW_RATETHISNEWS);
		$story['rating'] = number_format($article->rating(), 2);
		if ($article->votes == 1) {
			$story['votes'] = _MA_NW_ONEVOTE;
		} else {
			$story['votes'] = sprintf(_MA_NW_NUMVOTES,$article->votes);
		}
	} else {
		$GLOBALS['xoopsTpl']->assign('rates', false);
	}
	
	$GLOBALS['xoopsTpl']->assign('story', $story);
	
	//DNPROSSI - ADDED
	$GLOBALS['xoopsTpl']->assign('display_icons', nw_getmoduleoption('displaylinkicns', NW_MODULE_DIR_NAME));
	
	//DNPROSSI SEO
	$item_path = '';
	if ( $seo_enabled != 0 ) $item_path = nw_remove_accents($article->title());		
	$storyURL = nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $storyid, $item_path);
	$GLOBALS['xoopsTpl']->assign('story_url', $storyURL); 
	
	$print_item = '';
	if ( $seo_enabled != 0 ) { $print_item = nw_remove_accents(_MA_NW_PRINTERFRIENDLY); }
	$printLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MA_NW_SEO_PRINT, $storyid, $print_item) . "' title='" . _MA_NW_PRINTERFRIENDLY . "'>";
	$printLink .= "<img src='" . NW_MODULE_URL . "/images/print.png' width='28px' height='28px' border='0' alt='" . _MA_NW_PRINTERFRIENDLY . "'/></a>";
	$GLOBALS['xoopsTpl']->assign('print_link', $printLink);
		
	$pdf_item = '';
	if ( $seo_enabled != 0 ) { $pdf_item = nw_remove_accents($article->title()); }
	$pdfLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MA_NW_SEO_PDF, $storyid, $pdf_item) . "' title='" . _MA_NW_MAKEPDF . "'>";
	$pdfLink .= "<img src='" . NW_MODULE_URL . "/images/acrobat.png' width='28px' height='28px' border='0' alt='" . _MA_NW_MAKEPDF . "'/></a>";
	$GLOBALS['xoopsTpl']->assign('pdf_link', $pdfLink);   	
	
	if ( $seo_enabled != 0 ) {
		$GLOBALS['xoopsTpl']->assign('urlrewrite', true);
	} else {
		$GLOBALS['xoopsTpl']->assign('urlrewrite', false);
	}
	
	// Added in version 1.63, TAGS
	if(nw_getmoduleoption('tags', NW_MODULE_DIR_NAME)) {
		require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
		$GLOBALS['xoopsTpl']->assign('tags', true);
		$GLOBALS['xoopsTpl']->assign('tagbar', tagBar($storyid, 0));
	} else {
		$GLOBALS['xoopsTpl']->assign('tags', false);
	}
	$pdf_data['title'] = $article->title();
	$pdf_data['subtitle'] = $article->topic_title();
	
	$pdf_data['subsubtitle'] = $xoopsModule->name();
	$pdf_data['date'] = ': '.date(_DATESTRING, $article->published());
	
	$member_handler =& xoops_gethandler('member');
	$author = $member_handler->getUser($article->uid());
	if ($author->getVar('name'))
		$pdf_data['author'] = $author->getVar('name') . '('.$author->getVar('uname').')';
	else
		$pdf_data['author'] = $author->getVar('uname');
		
	ob_start();
	$GLOBALS['xoopsTpl']->display('db:nw_news_article_pdf.html');
	$content = ob_get_contents();	
	ob_end_clean();

	$pdf_data['content'] = $content;
	
	define('PDF_CREATOR', $GLOBALS['xoopsConfig']['sitename']);
	define('PDF_AUTHOR', $pdf_data['author']);
	define('PDF_HEADER_TITLE', $pdf_data['title']);
	define('PDF_HEADER_STRING', $pdf_data['subtitle']);
	define('PDF_HEADER_LOGO', 'logo.png');
	define('K_PATH_IMAGES', XOOPS_ROOT_PATH.'/images/');
	
	require_once XOOPS_ROOT_PATH.'/Frameworks/tcpdf/tcpdf.php';
	
	$filename = XOOPS_ROOT_PATH.'/Frameworks/tcpdf/config/lang/'._LANGCODE.'.php';
	if(file_exists($filename)) {
		include_once $filename;
	} else {
		include_once XOOPS_ROOT_PATH.'/Frameworks/tcpdf/config/lang/en.php';
	}


	//DNPROSSI Added - xlanguage installed and active 
	$module_handler =& xoops_gethandler('module');
	$xlanguage = $module_handler->getByDirname('xlanguage');
	if ( is_object($xlanguage) && $xlanguage->getVar('isactive') == true ) 
	{ $xlang = true; } else { $xlang = false; }  	
	
	$content = '';
	$content .= '<b><i><u>'.$myts->undoHtmlSpecialChars($pdf_data['title']).'</u></i></b><br /><b>'.$myts->undoHtmlSpecialChars($pdf_data['subtitle']).'</b><br />'._POSTEDBY.' : '.$myts->undoHtmlSpecialChars($pdf_data['author']).'<br />'._OBJS_POSTEDON.' '.$pdf_data['date'].'<br /><br /><br />';
	//$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br /><br /><br />' . $myts->undoHtmlSpecialChars($article->bodytext());
	//$content = str_replace('[pagebreak]','<br />',$content);
	$content .= $myts->undoHtmlSpecialChars($pdf_data['content']);

	//DNPROSSI Added - Get correct language and remove tags from text to be sent to PDF
	if ( $xlang == true ) { 
	   include_once XOOPS_ROOT_PATH.'/modules/xlanguage/include/functions.php';
	   $content = xlanguage_ml($content);
	}

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$doc_title = $myts->undoHtmlSpecialChars($pdf_data['title']);
	$doc_keywords = 'XOOPS';

	//DNPROSSI ADDED gbsn00lp chinese to tcpdf fonts dir
	if (_LANGCODE == "cn") { $pdf->SetFont('gbsn00lp', '', 10); } 

	// set document information
	$pdf->SetCreator($pdf_data['author']);
	$pdf->SetAuthor($pdf_data['author']);
	$pdf->SetTitle($pdf_data['title']);
	$pdf->SetSubject($pdf_data['subtitle']);
	$pdf->SetKeywords($doc_keywords);

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	//$pdf->SetHeaderData('', '', $firstLine, $secondLine);
	//$pdf->SetHeaderData('logo_example.png', '25', $firstLine, $secondLine);
	//UTF-8 char sample
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, '25', 'Éèéàùìò', $article->title());
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->setImageScale(1); //set image scale factor
	
	//DNPROSSI ADDED FOR SCHINESE
	if (_LANGCODE == "cn") 
	{ 
		$pdf->setHeaderFont(Array('gbsn00lp', '', 10));
		$pdf->setFooterFont(Array('gbsn00lp', '', 10));
	}
	else 
	{
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	}	
	
	$pdf->setLanguageArray($l); //set language items
	
	//initialize document
	$pdf->AliasNbPages();
	
	// ***** For Testing Purposes
	/*$pdf->AddPage();
	
	// print a line using Cell()
	*$pdf->Cell(0, 10, K_PATH_URL. '  ---- Path Url', 1, 1, 'C');
	$pdf->Cell(0, 10, K_PATH_MAIN. '  ---- Path Main', 1, 1, 'C');
	$pdf->Cell(0, 10, K_PATH_FONTS. '  ---- Path Fonts', 1, 1, 'C');
	$pdf->Cell(0, 10, K_PATH_IMAGES. '  ---- Path Images', 1, 1, 'C');
	*/
	// ***** End Test
	
	$pdf->AddPage();
	$pdf->writeHTML($content, true, 0);
	//Added for buffer error in TCPDF when using chinese charset
	  ob_end_clean();
	$pdf->Output();
?>