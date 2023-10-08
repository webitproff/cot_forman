<!-- BEGIN: MAIN -->
<ul class="list-unstyled posts">
<!-- BEGIN: PAGE_ROW -->
	<li class="{PAGE_ROW_ODDEVEN} px-3 py-2 overflow-hidden">
		<figure class="me-3 mb-1 float-start">
			{PAGE_ROW_AVATAR_FIRSTPOSTER}
			{PAGE_ROW_AVATAR_LASTPOSTER}
		</figure>

		<p class="fw-bold mb-0">
			<a href="{PAGE_ROW_URL}" class="fs-5a fw-bold lh-sm d-block">{PAGE_ROW_TITLE}</a>
		</p>
		<div class="text small lh-sm mb-2">
			{PAGE_ROW_PREVIEW_PLAIN}
		</div>
		<p class="text-end small mb-0">
			First: {PAGE_ROW_FIRSTPOSTER} @ {PAGE_ROW_CREATIONDATE_STAMP|cot_date('H:i d.m.Y', $this)} <br />
			Last: {PAGE_ROW_LASTPOSTER} @ {PAGE_ROW_UPDATED_STAMP|cot_date('H:i d.m.Y', $this)}
		</p>
	</li>
<!-- END: PAGE_ROW -->
</ul>

<!-- IF {PAGE_TOP_PAGINATION} -->
<nav aria-label="Posts Pagination">
	<ul class="pagination pagination-sm justify-content-center">
		{PAGE_TOP_PAGEPREV}{PAGE_TOP_PAGINATION}{PAGE_TOP_PAGENEXT}
	</ul>
</nav>
<!-- ENDIF -->
<!-- END: MAIN -->
