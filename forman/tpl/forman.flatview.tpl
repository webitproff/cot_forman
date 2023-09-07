<!-- BEGIN: MAIN -->
<main id="forums_sections">
	<div class="container">

		<div class="row my-5">
			<div class="col">
				<div class="title px-2 px-sm-0">
					<h1 class="lh-1 mb-1">{PHP.L.Forums}</h1>
					<p class="mb-0">
						{FORUMS_SECTIONS_PAGETITLE}
					</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div id="topics2list">
					{PHP|cot_topiclist('forman.topiclist', '0', '', '', '0', '0', '', '', 'topics2list', 0)}
				</div>
			</div>
		</div>

		{FILE "themes/{PHP.theme}/inc/forums-help.tpl"}

	</div>
</main>

	<!-- END: MAIN -->
