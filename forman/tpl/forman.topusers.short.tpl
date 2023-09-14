<!-- BEGIN: MAIN -->
<table class="table table-bordered mb-0">
	<tbody>
<!-- BEGIN: PAGE_ROW -->
		<tr class="{PAGE_ROW_ODDEVEN}">
			<td class="text-nowrap bg-transparent">{PAGE_ROW_NUM}. {PAGE_ROW_USER_NAME}</td>
			<td class="w-75 align-middle bg-transparent">
				<div class="progress short">
					<div class="progress-bar" role="progressbar" aria-valuenow="{PAGE_ROW_PERCENT}" aria-valuemin="0" aria-valuemax="100" style="width:{PAGE_ROW_PERCENT}%;">
						<span>{PAGE_ROW_POSTS}</span>
					</div>
				</div>
			</td>
		</tr>
<!-- END: PAGE_ROW -->
	</tbody>
</table>
<!-- BEGIN: NONE -->
	<p>
		{PHP.L.None}
	</p>
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
