<table cellspacing="0">
<tr>
    <td id="mainmenu">
    <{foreach item=topic from=$block.topics}>
        <a class="menuMain" href="<{$block.newsmodule_url}>/index.php?topic_id=<{$topic.id}>"><{$topic.title}> <{$topic.nw_count}></a>
    <{/foreach}>
    </td>
</tr>
</table>
