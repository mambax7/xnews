<{if $topic_rssfeed_link != ""}>
<div align='right'><{$topic_rssfeed_link}></div>
<{/if}>

<{if $displaynav === true}>
  <div style="text-align: center;">
    <form name="form1" action="<{$newsmodule_url}>/index.php" method="get">
    <{$topic_select}> <select name="storynum"><{$storynum_options}></select> <input type="submit" value="<{$lang_go}>" class="formButton"></form>
  <hr>
  </div>
<{/if}>

<{if $topic_description != ''}>
    <div style="text-align: center;"><{$topic_description}></div>
<{/if}>

<div style="margin: 10px;"><{$pagenav}></div>
<table width='100%' border='0'>
<tr>
    <{section name=i loop=$columns}>
    <td width="<{$column_width}>%"><{foreach item=story from=$columns[i]}><{include file="db:xnews_item.tpl" story=$story}><{/foreach}></td>
    <{/section}>
</tr>
</table>

<div style="text-align: right; margin: 10px;"><{$pagenav}></div>
<{include file='db:system_notification_select.tpl'}>
