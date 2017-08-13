<{if $pagenav}>
<div style="text-align: left; margin: 10px;"><{$smarty.const._MA_NW_PAGE}> <{$pagenav}></div>
<{/if}>
<table width="100%" border="0" style="padding: 5px;">
    <tr>
    <{if $nav_links != 0}>
    <{if $nav_links == 1 || $nav_links == 3}>
        <td align="left">
        <{if $previous_story_id != -1}>
            <{$previous_story}>
        <{/if}>
        <{if $next_story_id!= -1}>
            <{$next_story}>
        <{/if}>
        </td>
    <{/if}>
    <{/if}>
    <{if $display_icons != 0}>
    <{if $display_icons == 1 || $display_icons == 3}>
        <td align='right'>
        <{$print_link}>
        <a target="_top" href="<{$mail_link}>" title="<{$lang_sendstory}>">
            <img src="<{$newsmodule_url}>/assets/images/friend.png" width="28px" height="28px" border="0" alt="<{$lang_sendstory}>">
        </a>
        <{$pdf_link}>
        </td>
    <{/if}>
    <{/if}>
    </tr>
</table>

<div style="padding: 3px; margin-right:3px;"><{include file="db:nw_news_item.tpl" story=$story}></div>

</script>



<{if $display_images == 1 && $images_count > 0}>
    <table width="100%" border="0" style="padding: 5px;">
    <{section name = i loop = $attached_images}>
        <tr>
        <{foreach item = onefile from = $attached_images[i]}>
            <td align="center">
                <a href="<{$onefile.visitlink}>" target='_blank' title="<{$onefile.file_realname}>">
                    <img src="<{$onefile.thumbname}>" border="0" alt="<{$onefile.file_realname}>">
                </a>
            </td>
        <{/foreach}>
        </tr>
    <{/section}>
    </table>
<{/if}>



<{if $diplay_pdf == 1 && $has_adobe == 1 && $pdf_count > 0}>
    <table width="98%" border="0" style="padding: 5px;">
    <{section name = i loop = $attached_pdf}>
        <tr>
        <{foreach item = onefile from = $attached_pdf[i]}>
            <td>
                <iframe src="<{$onefile.file_downloadname}>#Toolbar=0&statusbar=0&navpanes=0&messages=0"  width="100%"  style="height: 15em">
                </iframe>
            </td>
        <{/foreach}>
        </tr>
    <{/section}>
    </table>
<{/if}>



<{if $pagenav}><div style="text-align: left; margin: 10px;"><{$smarty.const._MA_NW_PAGE}> <{$pagenav}></div><{/if}>



<{if $tags}>
    <br><{include file="db:tag_bar.tpl"}>
<{/if}>



<table width="100%" border="0" style="padding: 5px;">
    <tr>
    <{if $nav_links != 0 }>
    <{if $nav_links == 2 || $nav_links == 3}>
    <td align="left">
    <{if $previous_story_id != -1}>
        <{$previous_story}>
    <{/if}>
    <{if $next_story_id!= -1}>
        <{$next_story}>
    <{/if}>
    </td>
    <{/if}>
    <{/if}>
    <{if $display_icons != 0}>
    <{if $display_icons == 2 || $display_icons == 3}>
        <td align='right'>
            <{$print_link}>
            <a target="_top" href="<{$mail_link}>" title="<{$lang_sendstory}>">
                <img src="<{$newsmodule_url}>/assets/images/friend.png" width="28px" height="28px" border="0" alt="<{$lang_sendstory}>">
            </a>
            <{$pdf_link}>
        </td>
        <{/if}>
    <{/if}>
    </tr>
</table>



<{if $attached_files_count > 0}>
    <div class="itemInfo"><{$lang_attached_files}>
    <{foreach item=onefile from=$attached_files}>
        <!--<a href="<{$onefile.visitlink}>" target='_blank'><{$onefile.file_realname}></a>&nbsp;-->
        <a href="<{$onefile.visitlink}>" target='_blank'><{$onefile.file_realname}></a><br>
    <{/foreach}>
    </div>
<{/if}>



<{if $showsummary == true && $summary_count>0}>
<br><br>
<table width='50%' cellspacing='0' cellpadding='1'>
    <tr>
        <th><{$lang_other_story}></th>
    </tr>
<{if $urlrewrite == true}>
<{foreach item=onesummary from=$summary}>
    <tr class="<{cycle values="even,odd}>">
        <td align='left'><{$onesummary.story_published}> - <{$onesummary.story_title}></td>
    </tr>
<{/foreach}>
<{else}>
<{foreach item=onesummary from=$summary}>
    <tr class="<{cycle values="even,odd}>">
        <td align='left'><{$onesummary.story_published}> - <a href="<{$newsmodule_url}>/article.php?storyid=<{$onesummary.story_id}>"<{$onesummary.htmltitle}>><{$onesummary.story_title}></a></td>
    </tr>
<{/foreach}>
<{/if}>
</table>
<br>
<{/if}>



<{if $bookmarkme == true}>
<br>
<br>
<table width='50%' cellspacing='0' cellpadding='1'>
    <tr>
        <th><{$smarty.const._MA_NW_BOOKMARK_ME}></th>
    </tr>
    <tr>
        <td>
            <br>
        <{if $urlrewrite == true}>
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_BLINKLIST}>" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&Description=&Url=<{$story_url}>&Title=<{$encoded_title}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/blinklist.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_DELICIOUS}>" href="http://del.icio.us/post?url=<{$story_url}>&title=<{$encoded_title}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/delicious.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_DIGG}>" href="http://digg.com/submit?phase=2&url=<{$story_url}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/diggman.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_FARK}>" href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=<{$story_url}>&new_comment=<{$encoded_title}>&new_link_other=<{$encoded_title}>&linktype=Misc" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/fark.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_FURL}>" href="http://www.furl.net/storeIt.jsp?t=<{$encoded_title}>&u=<{$story_url}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/furl.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_NEWSVINE}>" href="http://www.newsvine.com/_tools/seed&save?u=<{$story_url}>&h=<{$encoded_title}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/newsvine.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_REDDIT}>" href="http://reddit.com/submit?url=<{$story_url}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/reddit.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_SIMPY}>" href="http://www.simpy.com/simpy/LinkAdd.do?href=<{$story_url}>&title=<{$encoded_title}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/simpy.png"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_SPURL}>" href="http://www.spurl.net/spurl.php?title=<{$encoded_title}>&url=<{$story_url}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/spurl.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_YAHOO}>" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<{$encoded_title}>&u=<{$story_url}>" title="" rel="nofollw"><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/yahoomyweb.gif"></a>&nbsp;
        <{else}>
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_BLINKLIST}>" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&Description=&Url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&Title=<{$encoded_title}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/blinklist.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_DELICIOUS}>" href="http://del.icio.us/post?url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&title=<{$encoded_title}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/delicious.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_DIGG}>" href="http://digg.com/submit?phase=2&url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/diggman.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_FARK}>" href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&new_comment=<{$encoded_title}>&new_link_other=<{$encoded_title}>&linktype=Misc" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/fark.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_FURL}>" href="http://www.furl.net/storeIt.jsp?t=<{$encoded_title}>&u=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/furl.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_NEWSVINE}>" href="http://www.nwvine.com/_tools/seed&save?u=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&h=<{$encoded_title}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/nwvine.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_REDDIT}>" href="http://reddit.com/submit?url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&title=<{$encoded_title}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/reddit.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_SIMPY}>" href="http://www.simpy.com/simpy/LinkAdd.do?href=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>&title=<{$encoded_title}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/simpy.png"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_SPURL}>" href="http://www.spurl.net/spurl.php?title=<{$encoded_title}>&url=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/spurl.gif"></a>&nbsp;
            <a target="_blank" title="<{$smarty.const._MA_NW_BOOKMARK_TO_YAHOO}>" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<{$encoded_title}>&u=<{$newsmodule_url}>/article.php?storyid=<{$story.id}>" title=""><img border="0" alt="" src="<{$newsmodule_url}>/assets/images/yahoomyweb.gif"></a>&nbsp;
           <{/if}>
        </td>
    </tr>
</table>
<br>
<{/if}>



<div style="text-align: center; padding: 3px; margin:3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div style="margin:3px; padding: 3px;">
<{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.tpl"}>
<{/if}>
</div>
<{include file='db:system_notification_select.tpl'}>
