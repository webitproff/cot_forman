<!-- BEGIN: MAIN -->
<ul class="list-unstyled posts">
<!-- BEGIN: PAGE_ROW -->
	<li class="{PAGE_ROW_ODDEVEN} px-3 py-2">
		<figure class="me-3 mb-1 float-start">
			{PAGE_ROW_USER_AVATAR}
		</figure>

		<ul class="list-unstyled mb-0 overflow-hidden ">
			<li class="fw-bold">
				{PAGE_ROW_CRUMBS}
			</li>
			<li class="small">
				{PHP.L.forman_lastreply} {PAGE_ROW_POSTERNAME} @ {PAGE_ROW_UPDATED|cot_date('j F Y', $this)}{PAGE_ROW_UPDATE_STATUS}
			</li>
			<li class="mb-2 overflow-hidden">
				<div class="text small lh-sm mb-2">
					{PAGE_ROW_TEXT}
				</div>
			</li>
			<li class="text-end small mb-0">
				{PAGE_ROW_USER_NAME} @ {PAGE_ROW_DATE}
			</li>
		</ul>
	</li>
<!-- END: PAGE_ROW -->
</ul>

<!-- IF {PAGE_TOP_PAGINATION} -->
<nav aria-label="Postlist Pagination">
	<ul class="pagination pagination-sm justify-content-center mb-0">
		{PAGE_TOP_PAGEPREV}{PAGE_TOP_PAGINATION}{PAGE_TOP_PAGENEXT}
	</ul>
</nav>
<!-- ENDIF -->
<!-- END: MAIN -->
