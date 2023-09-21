<!-- BEGIN: MAIN -->
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="columns_icon"></th>
				<th>
					{PHP.L.forums_topics}
				</th>
				<th class="text-center columns_posts_views">
					{PHP.L.forums_posts}
				</th>
				<th class="text-center columns_posts_views">
					{PHP.L.Views}
				</th>
				<th class="text-center columns_last_post">
					{PHP.L.Lastpost}
				</th>
			</tr>
		</thead>
		<tbody>
<!-- BEGIN: PAGE_ROW -->
			<tr class="{PAGE_ROW_ODDEVEN}">
				<td class="text-center align-middle">
					{PAGE_ROW_ICON}
				</td>
				<td class="align-middle">
					<a href="{PAGE_ROW_URL}" class="fs-5a fw-bold lh-sm d-block">{PAGE_ROW_TITLE}</a>
<!-- IF {PAGE_ROW_DESC} -->
					<p class="small mb-0">
						{PAGE_ROW_DESC}
					</p>
<!-- ENDIF -->
					<p class="small text-nowrap mb-0">
						{PAGE_ROW_CRUMBS} {PHP.L.forman_by} {PAGE_ROW_FIRSTPOSTER} @ {PAGE_ROW_CREATIONDATE_STAMP|cot_date('H:i d.m.Y', $this)}
					</p>
				</td>
				<td class="text-center align-middle">
					{PAGE_ROW_POSTCOUNT}
				</td>
				<td class="text-center align-middle">
					{PAGE_ROW_VIEWCOUNT}
				</td>
				<td class="text-center align-middle">
					<span class="small text-nowrap d-block">{PAGE_ROW_LASTPOSTER} @ {PAGE_ROW_UPDATED_STAMP|cot_date('H:i d.m.Y', $this)}</span>
					<span class="small d-block">{PAGE_ROW_TIMEAGO} {PHP.L.Ago}</span>
				</td>
			</tr>
<!-- END: PAGE_ROW -->
		</tbody>
	</table>
</div>

<!-- IF {PAGE_TOP_PAGINATION} -->
<nav aria-label="Topics Pagination">
	<ul class="pagination pagination-sm justify-content-center">
		{PAGE_TOP_PAGEPREV}{PAGE_TOP_PAGINATION}{PAGE_TOP_PAGENEXT}
	</ul>
</nav>
<!-- ENDIF -->
<!-- END: MAIN -->
