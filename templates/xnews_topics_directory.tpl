<h2><{$smarty.const._AM_XNEWS_TOPICS_DIRECTORY}></h2>
<br>
<ul>
<{foreach item=topic from=$topics}>
	<li><{$topic.prefix}><{$topic.topic_link}> (<{$topic.nw_count}>)</li>
<{/foreach}>
</ul>
