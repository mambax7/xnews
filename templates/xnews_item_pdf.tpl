<table cellpadding="0" cellspacing="0" class="item" width="100%">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="98%">
				<tr>
					<td class="itemHead">
						<span class="itemTitle">
							<{$story.topic_title}> 
							<{$story.topic_separator}> 
							<{$story.news_title}>
						</span>
					</td>
				</tr>
				<tr>
					<td class="itemInfo">
						<{if $story.files_attached}>
							<{$story.attached_link}>
							&nbsp;
						<{/if}>
						<span class="itemPoster">
							<{$lang_postedby}> 
							<{$story.poster}>
						</span> 
						<span class="itemPostDate">
							<{$lang_on}> 
							<{$story.posttime}>
						</span> 
						(<span class="itemStats">
							<{$story.hits}> 
							<{$lang_reads}>
						</span>) 
						<{$news_by_the_same_author_link}>
					</td>
				</tr>
				<tr>
					<td class="itemBody">
						<{$story.imglink}>
						<{if $articlePicture != ''}>
							<img src="<{$articlePicture}>" alt="" align="left" style="margin-right: 5px; margin-bottom: 5px;">
						<{/if}>
						<p class="itemText"><{$story.text}></p>
					</td>
				</tr>
				<tr>
					<td class="itemFoot">
						<span class="itemAdminLink">
							<{$story.adminlink}>
						</span>
						<{if $rates}>
							<b><{$lang_ratingc}></b> 
							<{$story.rating}> 
							(<{$story.votes}>) - 
							<a href="<{$newsmodule_url}>/ratenews.php?storyid=<{$story.id}>" rel="nofollow">
								<{$lang_ratethisnews}>
							</a> - 
						<{/if}>
						<span class="itemPermaLink">
							<{$story.morelink}>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
