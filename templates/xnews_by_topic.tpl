<div class="item">
<table width='100%' border='0'>
<tr>
<{section name=i loop=$columns}>
	<td width="<{$column_width}>%" valign="top">
	<{foreach item=topic from=$columns[i]}>
    <div class="itemBody">
    	<div class="itemInfo"><span class="itemText"><a href="<{$newsmodule_url}>/index.php?topic_id=<{$topic.id}>"><{$topic.title}></a></span></div>
		<{counter start=0 print=false assign=storynum}>
        <{foreach item=story from=$topic.stories}>
        	<{if $storynum == 0}>
        		<{include file="db:xnews_item.tpl" story=$story}><br>
			<{else}>
        		<{if $storynum == 1}>
            		<ul>
				<{/if}>
					<li><a href="<{$newsmodule_url}>/article.php?storyid=<{$story.id}>"><{$story.title}></a> (<{$story.posttime}>)</li>
				<{/if}>
				<{counter}>
			<{/foreach}>
			<{if $storynum > 1}>
				</ul>
				<a href="<{$newsmodule_url}>/index.php?topic_id=<{$topic.id}>"><{$lang_morereleases}><{$topic.title}></a>
			<{/if}>
	</div><br>
	<{/foreach}>
	</td>
<{/section}>
</tr>
</table>
</div>
<{include file='db:system_notification_select.tpl'}>
