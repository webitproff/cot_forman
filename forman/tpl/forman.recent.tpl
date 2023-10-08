<!-- BEGIN: MAIN -->
<main id="forums_sections">
	<div class="container">

		<div class="row my-5">
			<div class="col">
				<div class="title px-2 px-sm-0">
					<h1 class="lh-1 mb-1">{PHP.L.forman_recentposts}</h1>
					<p class="mb-0">
						{FORMAN_RECENT_BREADCRUMBS}
					</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-8">
				<div id="post2list">
					{PHP|sedby_postlist('forman.postlist.recent', '5', 'fp_updated DESC', '', '0', '0', 'fpp', 'post2list')}
				</div>
			</div>
			<div class="col-lg-4">
				<div class="block mb-3 px-4 py-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">{PHP.L.forman_topauthors}</span>
					<div id="top2list">
						{PHP|sedby_forman_topusers('forman.topusers.short', '0', '', '', '0', '0', '', '')}
					</div>
				</div>
				<div class="block px-4 py-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">{PHP.L.forman_forumstats}</span>
					<ul class="list-unstyled mb-0">
						<li>
							{PHP.L.forums_posts}: {PHP|sedby_forman_count('posts')}, <span class="text-lowercase">{PHP.L.forums_topics}:</span> {PHP|sedby_forman_count('topics')}, <span class="text-lowercase">{PHP.L.Users}:</span> {PHP|sedby_forman_count('users')}
						</li>
					</ul>
				</div>
			</div>
		</div>

	</div>
</main>
<!-- END: MAIN -->
