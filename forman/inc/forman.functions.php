<?php
/**
* Forman Plugin / Functions
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

// define globals
define('SEDBY_FORMAN_REALM', '[SEDBY] Forman');

require_once cot_incfile('forman', 'plug', 'rc');
require_once cot_incfile('forums', 'module');

/**
 * Encrypts or decrypts string
 *
 * @param		string	$action	01.	Action (encrypt || decrypt)
 * @param		string	$string	02.	String to encrypt / decrypt
 * @param		string	$key		03. Secret key
 * @param		string	$iv			04. Initialization vector
 * @param		string	$method	05. Encryption method (optional)
 * @return	string					Encrypted / decrypted string
 */
 if (!function_exists('cot_encrypt_decrypt')) {
	function cot_encrypt_decrypt($action, $string, $key, $iv, $method = '') {
		$method = empty($method) ? 'AES-256-CBC' : $method;
		$key = hash('sha256', $key);
		$iv = substr(hash('sha256', $iv), 0, 16);

		if ($action == 'encrypt') {
			$output = openssl_encrypt($string, $method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		elseif ($action == 'decrypt') {
			$output = base64_decode($string);
			$output = openssl_decrypt($output, $method, $key, 0, $iv);
		}
		return $output;
	}
 }

/**
 * Generates Post list widget
 * @param  string  $tpl        01. Template code
 * @param  int     $items      02. Number of items to show. 0 - all items
 * @param  string  $order      03. Sorting order (SQL)
 * @param  string  $extra		   04. Custom selection filter (SQL)
 * @param  int     $group      05. Group posts by topics
 * @param  int     $offset     06. Exclude specified number of records starting from the beginning
 * @param  string  $pagination 07. Pagination parameter name for the URL, e.g. 'pld'. Make sure it does not conflict with other paginations
 * @param  string  $ajax_block 08. DOM block ID for ajax pagination
 * @param  string  $cache_name 09. Cache name
 * @param  int     $cache_ttl  10. Cache TTL
 * @return string              Parsed HTML
 */
function cot_postlist($tpl = 'forman.postlist', $items = 0, $order = '', $extra = '', $group = 0, $offset = 0, $pagination = '', $ajax_block = '', $cache_name = '', $cache_ttl = '') {

	$cache_name = (!empty($cache_name)) ? str_replace(' ', '_', $cache_name) : '';

	if (Cot::$cache && !empty($cache_name) && Cot::$cache->db->exists($cache_name, SEDBY_FORMAN_REALM))
		$output = Cot::$cache->db->get($cache_name, SEDBY_FORMAN_REALM);
	else {

		global $L;

		/* === Hook === */
		foreach (array_merge(cot_getextplugins('forman.first')) as $pl) {
			include $pl;
		}
		/* ===== */

		if (Cot::$cfg['plugin']['forman']['encrypt_ajax_urls']) {
			$h = $tpl.','.$items.','.$order.','.$extra.','.$group.','.$offset.','.$pagination.','.$ajax_block.','.$cache_name.','.$cache_ttl;
			$h = cot_encrypt_decrypt('encrypt', $h, Cot::$cfg['plugin']['forman']['encrypt_key'], Cot::$cfg['plugin']['forman']['encrypt_iv']);
			$h = str_replace('=', '', $h);
		}

		$db_forum_posts = Cot::$db->forum_posts;
		$db_forum_topics = Cot::$db->forum_topics;

		// Display the items
    (!isset($tpl) || empty($tpl)) && $tpl = 'forman.postlist';
		$t = new XTemplate(cot_tplfile($tpl, 'plug'));

		// Get pagination if necessary
		if (!empty($pagination))
			list($pg, $d, $durl) = cot_import_pagenav($pagination, $items);
		else
			$d = 0;

		// Compile items number
		(!ctype_digit($offset)) && $offset = 0;
		$d = $d + $offset;
		$sql_limit = ($items > 0) ? "LIMIT $d, $items" : "";

		// Compile order
		$sql_order = empty($order) ? "" : " ORDER BY $order";

		// Compile group
		$sql_group = ($group == 1) ? "fp.fp_id = (SELECT MAX(fp_id) FROM " . $db_forum_posts . " AS fp2 WHERE fp2.fp_topicid = fp.fp_topicid)" : '';

		// Compile order
		$sql_extra = (empty($extra)) ? "" : $extra;

		if (!empty($sql_group) && !empty($sql_extra))
			$sql_cond = "WHERE " . $sql_group . " AND " . $sql_extra;
		elseif (!empty($sql_group) && empty($sql_extra))
			$sql_cond = "WHERE " . $sql_group;
		elseif (empty($sql_group) && !empty($sql_extra))
			$sql_cond = "WHERE " . $sql_extra;
		else
			$sql_cond = "";

		$postlist_join_columns = "";
		$postlist_join_tables = "";

		// Users Module Support
		if (Cot::$cfg['plugin']['forman']['usertags'] == 1) {
			$db_users = Cot::$db->users;
			$postlist_join_columns .= " , u.* ";
			$postlist_join_tables .= "LEFT JOIN $db_users AS u ON (u.user_id = fp.fp_posterid)";
		}

		/* === Hook === */
		foreach (cot_getextplugins('forman.query') as $pl) {
			include $pl;
		}
		/* ===== */

		$query = "SELECT fp.* $postlist_join_columns FROM $db_forum_posts AS fp $postlist_join_tables $sql_cond $sql_order $sql_limit";
		$res = Cot::$db->query($query);
		$jj = 1;

		/* === Hook - Part 1 === */
		$extp = cot_getextplugins('forman.loop');
		/* ===== */

		while ($row = $res->fetch()) {

			if (Cot::$cfg['plugin']['forman']['usertags'] == 1)
				$t->assign(cot_generate_usertags($row, 'PAGE_ROW_USER_'));

			$topic_title = Cot::$db->query("SELECT ft_title FROM $db_forum_topics WHERE ft_id = ? LIMIT 1", $row['fp_topicid'])->fetchColumn();
			$post_author = htmlspecialchars($row['fp_postername']);

			$t->assign(array(
				'PAGE_ROW_NUM'     => $jj,
				'PAGE_ROW_ODDEVEN' => cot_build_oddeven($jj),
				'PAGE_ROW_RAW'     => $row,

				'PAGE_ROW_CAT_TITLE'	=> Cot::$structure['forums'][$row['fp_cat']]['title'],
				'PAGE_ROW_CAT_URL'		=> cot_url('forums', 'm=topics&s=' . $row['fp_cat']),

				'PAGE_ROW_TOPIC_TITLE'	=> $topic_title,
				'PAGE_ROW_TOPIC_URL'		=> cot_url('forums', 'm=posts&q=' . $row['fp_topicid']),

				'PAGE_ROW_ID'					=> $row['fp_id'],
				'PAGE_ROW_TOPICID'		=> $row['fp_topicid'],
				'PAGE_ROW_CAT'				=> $row['fp_cat'],
				'PAGE_ROW_POSTERID'		=> $row['fp_posterid'],
				'PAGE_ROW_POSTERNAME'	=> $post_author,
				'PAGE_ROW_UPDATER'		=> $row['fp_updater'],
				'PAGE_ROW_POSTERIP'		=> $row['fp_posterip'],

				'PAGE_ROW_CREATION'				=> $row['fp_creation'],
				'PAGE_ROW_UPDATED'				=> $row['fp_updated'],
				'PAGE_ROW_UPDATE_STATUS'	=> ($row['fp_updated'] != $row['fp_creation']) ? cot_rc('post_update_status', array('status' => $L['Updated'])) : '',

				'PAGE_ROW_TEXT'				=> $row['fp_text'],
				'PAGE_ROW_TEXT_PLAIN'	=> strip_tags($row['fp_text']),
			));

			if ($row['fp_posterid'] > 0) {
				$avatar_link = (Cot::$cfg['plugin']['forman']['usertags'] == 1) ? $row['fp_posterid'] : Cot::$db->query("SELECT user_avatar FROM " . Cot::$db->users . " WHERE user_id = ?", $row['fp_posterid'])->fetchColumn();
				$t->assign(array(
					'PAGE_ROW_AVATAR' => (empty($avatar_link)) ? cot_rc('forman_default_avatar') : cot_rc('forman_avatar', array('src' => $avatar_link, 'user' => $post_author, 'class' => 'img-fluid')),
					'PAGE_ROW_AUTHOR' => cot_build_user($row['fp_posterid'], $post_author),
				));
			}
			else {
				require_once cot_incfile('comlist', 'plug', 'rc');
				$t->assign(array(
					'PAGE_ROW_AVATAR' => cot_rc('forman_default_avatar'),
					'PAGE_ROW_AUTHOR' => $post_author,
				));
			}

			/* === Hook - Part 2 === */
			foreach ($extp as $pl) {
				include $pl;
			}
			/* ===== */

			$t->parse("MAIN.PAGE_ROW");
			$jj++;
		}

		// Render pagination if needed
		if (!empty($pagination)) {

			$totalitems = Cot::$db->query("SELECT fp.* FROM $db_forum_posts AS fp $sql_cond")->rowCount();

			$url_area = defined('COT_PLUG') ? 'plug' : Cot::$env['ext'];

			if (defined('COT_LIST')) {
				global $list_url_path;
				$url_params = $list_url_path;
			}
			elseif (defined('COT_PAGES')) {
				global $al, $id, $pag;
				$url_params = empty($al) ? array('c' => $pag['page_cat'], 'id' => $id) :  array('c' => $pag['page_cat'], 'al' => $al);
			}
			elseif(defined('COT_USERS')) {
				global $m;
				$url_params = empty($m) ? array() :  array('m' => $m);
			}
			elseif (defined('COT_ADMIN')) {
				$url_area = 'admin';
				global $m, $p, $a;
				$url_params = array('m' => $m, 'p' => $p, 'a' => $a);
			}
			else
				$url_params = array();

			$url_params[$pagination] = $durl;

			if ((Cot::$cfg['turnajax'] == 1) && (Cot::$cfg['plugin']['forman']['ajax'] == 1) && !empty($ajax_block)) {
				$ajax_mode = true;
				$ajax_plug = 'plug';
				if (Cot::$cfg['plugin']['forman']['encrypt_ajax_urls'] == 1)
					$ajax_plug_params = "r=forman&h=$h";
				else
					$ajax_plug_params = "r=forman&tpl=$tpl&items=$items&order=$order&extra=$extra&group=$group&offset=$offset&pagination=$pagination&ajax_block=$ajax_block&cache_name=$cache_name&cache_ttl=$cache_ttl";
			}
			else {
				$ajax_mode = false;
				$ajax_plug = $ajax_plug_params = '';
			}

			$pagenav = cot_pagenav($url_area, $url_params, $d, $totalitems, $items, $pagination, '', $ajax_mode, $ajax_block, $ajax_plug, $ajax_plug_params);

			// Assign pagination tags
			$t->assign(array(
				'PAGE_TOP_PAGINATION'  => $pagenav['main'],
				'PAGE_TOP_PAGEPREV'    => $pagenav['prev'],
				'PAGE_TOP_PAGENEXT'    => $pagenav['next'],
				'PAGE_TOP_FIRST'       => $pagenav['first'],
				'PAGE_TOP_LAST'        => $pagenav['last'],
				'PAGE_TOP_CURRENTPAGE' => $pagenav['current'],
				'PAGE_TOP_TOTALLINES'  => $totalitems,
				'PAGE_TOP_MAXPERPAGE'  => $items,
				'PAGE_TOP_TOTALPAGES'  => $pagenav['total']
			));
		}

		// Assign service tags
		if (!empty($cache_name) && (Cot::$usr['maingrp'] == 5)) {
			$t->assign(array(
				'PAGE_TOP_QUERY' => $query,
				'PAGE_TOP_RES' => $res,
			));
		}

		if ($jj==1)
			$t->parse("MAIN.NONE");

		/* === Hook === */
		foreach (cot_getextplugins('forman.tags') as $pl) {
			include $pl;
		}
		/* ===== */

		$t->parse();
		$output = $t->text();

		if (Cot::$cache && ($jj > 1) && empty($pagination) && !empty($cache_name) && !empty($cache_ttl) && ($cache_ttl > 0))
		Cot::$cache->db->store($cache_name, $output, SEDBY_FORMAN_REALM, (int)$cache_ttl);
	}
	return $output;
}
