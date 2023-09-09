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
					{PHP|sedby_topiclist('forman.topiclist', '3', '', '', '0', '0', 'ftp', 'topics2list')}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">

				<div class="block mb-3 bg-light bg-gradient border rounded">
					{FILE "{PHP.cfg.plugins_dir}/forman/tpl/inc/forums-help.tpl"}
				</div>

				<div class="block mb-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">Топ авторов</span>
					<div id="top2list">
						{PHP|sedby_forman_topusers('forman.topusers', '0', '', '', '0', '0', '', '')}
					</div>
				</div>

				<div class="block mb-3 bg-light bg-gradient border rounded">
					<span class="fw-bold fs-5 mb-2 d-block">Статистика форумов</span>
					<ul class="list-unstyled mb-0">
						<li>
							Сообщений: {PHP|sedby_forman_count('posts')}, тем: {PHP|sedby_forman_count('topics')}, пользователей: {PHP|sedby_forman_count('users')}
						</li>
						<li>
							Последнее сообщение: {PHP|sedby_postlist('forman.postlist.basic', 1, 'fp_updated DESC')}
						</li>
						<li>
							<a href="{PHP|cot_url('forums', '&a=recent')}">Последние сообщения на форумах</a>
						</li>
					</ul>
				</div>

				<div class="alert alert-primary mb-4 px-4">
					<span class="fw-bold fs-5 mb-1 d-block">Плагин Forman &ndash; дополнительный функционал и минихаки для форумов:</span>
					<ul class="mb-0">
						<li>
							линейный вид главной страницы форумов &ndash; вывод топиков по убыванию даты через свой шаблон;
						</li>
						<li>
							локация Recent Posts &ndash; вывод постов по убыванию даты через свой шаблон;
						</li>
						<li>
							функция sedby_topiclist() &ndash; формирование списка топиков по условиям;
						</li>
						<li>
							функция sedby_postlist() &ndash; формирование списка постов по условиям;
						</li>
						<li>
							функция sedby_forman_count() &ndash; подсчет топиков, постов и пользователей;
						</li>
						<li>
							функция sedby_forman_topusers() &ndash; вывод списка пользователей по активности постов.
						</li>
					</ul>
				</div>

			</div>
		</div>

	</div>
</main>
<!-- END: MAIN -->
