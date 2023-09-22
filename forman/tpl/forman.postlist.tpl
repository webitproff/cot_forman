<!-- BEGIN: MAIN -->
<ul class="list-unstyled posts">
<!-- BEGIN: PAGE_ROW -->
	<li class="{PAGE_ROW_ODDEVEN} px-3 py-2">
		<figure class="me-3 mb-1 float-start">
			{PAGE_ROW_USER_AVATAR}
		</figure>

		<p class="fw-bold lh-sm mb-1">
			<a href="{PAGE_ROW_TOPIC_URL}">{PAGE_ROW_TOPIC_TITLE}</a>
		</p>

		<div class="text small lh-sm mb-2">
			{PAGE_ROW_TEXT_PLAIN|cot_cutstring($this, '160')}
		</div>

		<p class="text-end small mb-0">
			{PAGE_ROW_POSTERNAME} @ {PAGE_ROW_UPDATED|cot_date('j F Y', $this)}{PAGE_ROW_UPDATE_STATUS}
		</p>
	</li>
<!-- END: PAGE_ROW -->
<!-- BEGIN: NONE -->
	<li>
		{PHP.L.None}
	</li>
<!-- END: NONE -->
</ul>

<!-- IF {PAGE_TOP_PAGINATION} -->
<nav aria-label="Posts Pagination">
	<ul class="pagination pagination-sm justify-content-center">
		{PAGE_TOP_PAGEPREV}{PAGE_TOP_PAGINATION}{PAGE_TOP_PAGENEXT}
	</ul>
</nav>
<!-- ENDIF -->
<!-- END: MAIN -->
