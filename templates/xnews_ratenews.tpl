<br><br><br>

<hr size="1" noshade="noshade">
<table border="0" cellpadding="1" cellspacing="0" width="80%" align="center">
<tr>
	<td>
		<h3><{$news.title}></h3>
		<ul>
			<li><{$lang_voteonce}></li>
			<li><{$lang_ratingscale}></li>
			<li><{$lang_beobjective}></li>
			<li><{$lang_donotvote}></li>
		</ul>
	</td>
</tr>
<tr>
	<td align="center">
		<form method="post" action="<{$newsmodule_url}>/ratenews.php">
		<input type="hidden" name="storyid" value="<{$news.storyid}>">
		<select name="rating"><option>--</option><option>10</option><option>9</option><option>8</option><option>7</option><option>6</option><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>&nbsp;&nbsp;
        <input type="submit" name="submit" value="<{$lang_rateit}>"> <input type='button' value="<{$lang_cancel}>" onclick="location='<{$newsmodule_url}>/article.php?storyid=<{$news.storyid}>'">
      	</form>
   	</td>
</tr>
</table>
