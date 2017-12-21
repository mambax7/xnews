<{if $pagenav}><div style="text-align: left; margin: 10px;"><{$smarty.const._MD_XNEWS_PAGE}> <{$pagenav}></div><{/if}>
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

<div style="padding: 3px; margin-right:3px;"><{include file="db:nw_news_item_pdf.tpl" story=$story}></div>

</script>


<{if $pagenav}><div style="text-align: left; margin: 10px;"><{$smarty.const._MD_XNEWS_PAGE}> <{$pagenav}></div><{/if}>

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
        <a href="<{$onefile.visitlink}>" target='_blank'><{$onefile.file_realname}></a>&nbsp;
    <{/foreach}>
    </div>
<{/if}>

<{if $showsummary == true && $summary_count>0}>
<br>
<br>
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
