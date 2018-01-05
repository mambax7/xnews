<table>
<tr>
    <td>
        <ul>
        <{foreach item=news from=$block.stories}>
            <li>
            <{if $block.sort=='counter'}>
                [<{$news.hits}>]
            <{elseif $block.sort=='published'}>
                [<{$news.date}>]
            <{else}>
                [<{$news.rating}>]
            <{/if}>
            <{$news.topic_title}> - <a href="<{$block.newsmodule_url}>/article.php?storyid=<{$news.id}>" <{$news.infotips}>><{$news.title}></a> <br><{$news.teaser}></li>
        <{/foreach}>
        </ul>
    </td>
</tr>
</table>
