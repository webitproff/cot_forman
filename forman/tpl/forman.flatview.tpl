<!-- BEGIN: MAIN -->
<main id="forums_sections">
	<div class="container">

		<div class="row my-5">
			<div class="col">
				<div class="title px-2 px-sm-0">
					<h1 class="lh-1 mb-1">{PHP.L.Forums}</h1>
					<p class="mb-2">
						{FORUMS_SECTIONS_PAGETITLE}
					</p>
					<p class="lh-sm mb-0">
						{PHP.L.forman_flatview_desc}
					</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="btn-group mb-3" role="group" aria-label="Forum Buttons">
					<a href="{PHP|cot_url('forums','n=markall')}" rel="nofollow" class="btn btn-primary btn-sm">{PHP.L.forums_markasread}</a>
					{FORMAN_FLATVIEW_TOGGLE}
				</div>
				<div id="topics2list">
					{PHP|sedby_topiclist('forman.topiclist', '5', 'ft_updated DESC', '', '0', '0', 'ftp', 'topics2list')}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">

				<div class="block mb-3 px-4 py-3 bg-light bg-gradient border rounded">
					{FILE "{PHP.cfg.themes_dir}/{PHP.theme}/inc/forums-help.tpl"}
				</div>

				<div class="block mb-3 px-4 py-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">{PHP.L.forman_topauthors}</span>
					<div id="top2list">
						{PHP|sedby_forman_topusers('forman.topusers', '0', '', '', '0', '0', '', '')}
					</div>
				</div>

				<div class="block mb-3 px-4 py-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">{PHP.L.forman_forumstats}</span>
					<ul class="list-unstyled mb-0">
						<li>
							{PHP.L.forums_posts}: {PHP|sedby_forman_count('posts')}, <span class="text-lowercase">{PHP.L.forums_topics}:</span> {PHP|sedby_forman_count('topics')}, <span class="text-lowercase">{PHP.L.Users}:</span> {PHP|sedby_forman_count('users')}
						</li>
						<li>
							{PHP.L.forman_mostrecentpost}: {PHP|sedby_postlist('forman.postlist.basic', 1, 'fp_updated DESC')}
						</li>
						<li>
							<a href="{PHP|cot_url('forums', '&a=recent')}">{PHP.L.forman_recentposts}</a>
						</li>
					</ul>
				</div>

				<div class="alert alert-primary px-4">
					<span class="fw-bold fs-5 mb-1 d-block">{PHP.L.forman_features_title}</span>
					<ul class="mb-0">
						<li>
							{PHP.L.forman_features_item1}
						</li>
						<li>
							{PHP.L.forman_features_item2}
						</li>
						<li>
							{PHP.L.forman_features_item3}
						</li>
						<li>
							{PHP.L.forman_features_item4}
						</li>
						<li>
							{PHP.L.forman_features_item5}
						</li>
						<li>
							{PHP.L.forman_features_item6}
						</li>
						<li>
							{PHP.L.forman_features_item7}
						</li>
					</ul>
				</div>

			</div>
		</div>

	</div>
</main>
<!-- END: MAIN -->
