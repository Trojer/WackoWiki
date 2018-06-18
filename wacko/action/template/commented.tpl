[ === main === ]
	[ ' message ' ]
	[= mark =
		<small><a href="[ ' href ' ]">[ ' _t: MarkRead ' ]</a></small>
	=]
	[= xml =
		<span class="desc_rss_feed">
			<a href="[ ' href ' ]">
				<img src="[ ' db: theme_url ' ]icon/spacer.png" title="[ ' _t: CommentsXMLTip ' ]" alt="XML" class="btn-feed">
			</a>
		</span>
		<br><br>
	=]
	[= nopages _ =
		[ ' _t: NoRecentlyCommented ' ]
	=]
	[''' pagination ''']
	<ul class="ul_list">
		[= page _ =
			<li><strong>[ ' day ' ]</strong>
				<ul>
					[= l _ =
						<li class="lined[ ' viewed ' ]">
							<span class="dt">[ ' time ' ]</span> &mdash; [ ' page ' ]
							 . . . . . . . . . . . . . . . . 
							<small>
								[ ' _t: LatestCommentBy ' ]
								[ ' user ' ]
							</small>
						</li>
					=]
				</ul>
			<br></li>
		=]
	</ul>

	[''' pagination ''']


[= pagination =]
<nav class="pagination">[ ' text ' ]</nav>