<h2><{$smarty.const._AM_XNEWS_WHOS_WHO}></h2>
<br>
<br>
<h3><{$smarty.const._MD_XNEWS_LIST_OF_AUTHORS}></h3>
<br>
<ul>
<{foreach item=who from=$whoswho}>
    <li><a href="<{$newsmodule_url}>/newsbythisauthor.php?uid=<{$who.uid}>"><{$who.name}></a></li>
<{/foreach}>
</ul>
