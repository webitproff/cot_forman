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
require_once cot_incfile('pagelist', 'plug', 'functions.extra');

/**
 * Generates Topic list widget
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
function sedby_topiclist($tpl = 'forman.topiclist', $items = 0, $order = '', $extra = '', $group = 0, $offset = 0, $pagination = '', $ajax_block = '', $cache_name = '', $cache_ttl = '') {

  $enableAjax = $enableCache = $enablePagination = false;

  // Condition shortcut
  if (Cot::$cache && !empty($cache_name) && ((int)$cache_ttl > 0) && (Cot::$usr['id'] == 0)) {
    $enableCache = true;
    $cache_name = str_replace(' ', '_', $cache_name);
  }

 	if ($enableCache && Cot::$cache->db->exists($cache_name, SEDBY_FORMAN_REALM)) {
    $output = Cot::$cache->db->get($cache_name, SEDBY_FORMAN_REALM);
  } else {

    // Begin: Work on cats view permissions
		$black_cats = sedby_black_cats();
		if (!empty($black_cats)) {
			$black_cats = "ft_cat NOT IN ($black_cats)";
			$extra = empty($extra) ? $black_cats : $extra . " AND " . $black_cats;
		}
		// End: Work on cats view permissions

    /* === Hook === */
    foreach (cot_getextplugins('topiclist.first') as $pl) {
      include $pl;
    }
    /* ===== */

    // Condition shortcuts
    if ((Cot::$cfg['turnajax']) && (Cot::$cfg['plugin']['forman']['ajax']) && !empty($ajax_block)) {
      $enableAjax = true;
    }

    if (!empty($pagination) && ((int)$items > 0)) {
      $enablePagination = true;
    }

    // DB tables shortcuts
    $db_forum_topics = Cot::$db->forum_topics;

    // Display the items
    (!isset($tpl) || empty($tpl)) && $tpl = 'forman.topiclist';
    $t = new XTemplate(cot_tplfile($tpl, 'plug'));

    // Get pagination if necessary
    if ($enablePagination) {
      list($pg, $d, $durl) = cot_import_pagenav($pagination, $items);
    }
    else {
      $d = 0;
    }

    // Compile items number
    ((int)$offset <= 0) && $offset = 0;
    $d = $d + (int)$offset;
    $sql_limit = ($items > 0) ? "LIMIT $d, $items" : "";

    // Compile order
    $sql_order = empty($order) ? "ORDER BY ft_updated DESC" : " ORDER BY $order";

    // Compile group
		$sql_group = ($group == 1) ? "ft.ft_id = (SELECT MAX(ft_id) FROM " . $db_forum_topics . " AS ft2 WHERE ft2.ft_cat = ft.ft_cat)" : '';

    // Compile extra SQL condition
    $sql_extra = (empty($extra)) ? "" : $extra;

    $sql_cond = sedby_twocond($sql_group, $sql_extra);

    $topiclist_join_columns = "";
    $topiclist_join_tables = "";

    /* === Hook === */
		foreach (cot_getextplugins('topiclist.query') as $pl) {
			include $pl;
		}
		/* ===== */

    $query = "SELECT ft.* $topiclist_join_columns FROM $db_forum_topics AS ft $topiclist_join_tables $sql_cond $sql_order $sql_limit";
    $res = Cot::$db->query($query);
    $jj = 1;

    /* === Hook - Part 1 === */
    $extp = cot_getextplugins('topiclist.loop');
    /* ===== */

    while ($row = $res->fetch()) {
      $row['ft_icon'] = 'posts';
      $row['ft_postisnew'] = FALSE;
      $row['ft_pages'] = '';

      $row['ft_title'] = ($row['ft_mode'] == 1) ? "# ".$row['ft_title'] : $row['ft_title'];

      if ($row['ft_movedto'] > 0) {
    		$row['ft_url'] = cot_url('forums', "m=posts&q=".$row['ft_movedto']);
        $row['ft_icon_type'] = 'posts_moved';
    		$row['ft_icon'] = Cot::$R['forums_icon_posts_moved'];
        $row['ft_title']= Cot::$L['Moved'].": ".$row['ft_title'];
    		$row['ft_postcount'] = Cot::$R['forums_code_post_empty'];
    		$row['ft_replycount'] = Cot::$R['forums_code_post_empty'];
    		$row['ft_viewcount'] = Cot::$R['forums_code_post_empty'];
    		$row['ft_lastpostername'] = Cot::$R['forums_code_post_empty'];
    		$row['ft_lastposturl'] = cot_url('forums', "m=posts&q=".$row['ft_movedto']."&n=last", "#bottom");
    		$row['ft_lastpostlink'] = cot_rc_link($row['ft_lastposturl'], Cot::$R['icon_follow'], 'rel="nofollow"') .Cot::$L['Moved'];
      } else {
    		$row['ft_url'] = cot_url('forums', "m=posts&q=".$row['ft_id']);
        if ($row['ft_updated'] > Cot::$usr['lastvisit'] && Cot::$usr['id']>0) {
    			$row['ft_icon'] .= '_new';
    			$row['ft_postisnew'] = TRUE;
    		}
    		if ($row['ft_postcount'] >= Cot::$cfg['forums']['hottopictrigger'] && !$row['ft_state'] && !$row['ft_sticky']) {
    			$row['ft_icon'] = ($row['ft_postisnew']) ? 'posts_new_hot' : 'posts_hot';
    		} else {
    			$row['ft_icon'] .= ($row['ft_sticky']) ? '_sticky' : '';
    			$row['ft_icon'] .=  ($row['ft_state']) ? '_locked' : '';
    		}
    		$row['ft_icon_type'] = $row['ft_icon'];
    		$row['ft_icon'] = cot_rc('forums_icon_topic', array('icon' => $row['ft_icon']));
        $row['ft_replycount'] = $row['ft_postcount'] - 1;
    		$row['ft_lastposturl'] = (Cot::$usr['id'] > 0 && $row['ft_updated'] > Cot::$usr['lastvisit']) ? cot_url('forums', "m=posts&q=".$row['ft_id']."&n=unread", "#unread") : cot_url('forums', "m=posts&q=".$row['ft_id']."&n=last", "#bottom");
    		$row['ft_lastpostlink'] = cot_rc_link($row['ft_lastposturl'], Cot::$R['icon_unread'], 'rel="nofollow"').cot_date('datetime_short', $row['ft_updated']);
    	}

      if ($row['ft_postcount'] > Cot::$cfg['forums']['maxpostsperpage'] && !$row['ft_movedto']) {
    		$pn_q = $row['ft_movedto'] > 0 ? $row['ft_movedto'] : $row['ft_id'];
    		$pn = cot_pagenav('forums', 'm=posts&q='.$pn_q, 0, $row['ft_postcount'], Cot::$cfg['forums']['maxpostsperpage'], 'd');
        if (!isset($pn['first'])) {
          $pn['first'] = '';
        }
    		$row['ft_pages'] = cot_rc('forums_code_topic_pages', array('main' => $pn['main'], 'first' => $pn['first'], 'last' => $pn['last']));
    	}

    	$row['ft_icon_type_ex'] = $row['ft_icon_type'];
    	if (!empty($row['ft_user_posted'])) {
    		$row['ft_icon_type_ex'] .= '_posted';
    	}

      $ft_path = cot_forums_buildpath(htmlspecialchars($row['ft_cat']));
      array_shift2($ft_path);

      $t->assign(array(
        'PAGE_ROW_ODDEVEN' => cot_build_oddeven($jj),
        'PAGE_ROW_NUM'     => $jj,

        'PAGE_ROW_ID' => $row['ft_id'],
        'PAGE_ROW_STATE' => $row['ft_state'],

        'PAGE_ROW_ICON' => $row['ft_icon'],
        'PAGE_ROW_ICON_TYPE' => $row['ft_icon_type'],
        'PAGE_ROW_ICON_TYPE_EX' => $row['ft_icon_type_ex'],

        'PAGE_ROW_TITLE' => htmlspecialchars($row['ft_title']),
        'PAGE_ROW_DESC' => htmlspecialchars($row['ft_desc']),
        'PAGE_ROW_CRUMBS' => cot_breadcrumbs($ft_path, false, false),

        'PAGE_ROW_CREATIONDATE' => cot_date('datetime_short', $row['ft_creationdate']),
        'PAGE_ROW_CREATIONDATE_STAMP' => $row['ft_creationdate'],

        'PAGE_ROW_UPDATEDURL' => $row['ft_lastposturl'],
        'PAGE_ROW_UPDATED' => $row['ft_lastpostlink'],
        'PAGE_ROW_UPDATED_STAMP' => $row['ft_updated'],

        'PAGE_ROW_MOVED' => ($row['ft_movedto'] > 0) ? 1 : 0,
        'PAGE_ROW_TIMEAGO' => cot_build_timegap($row['ft_updated']),

        'PAGE_ROW_POSTCOUNT' => $row['ft_postcount'],
        'PAGE_ROW_REPLYCOUNT' => $row['ft_replycount'],
        'PAGE_ROW_VIEWCOUNT' => $row['ft_viewcount'],

        'PAGE_ROW_FIRSTPOSTER' => cot_build_user($row['ft_firstposterid'], $row['ft_firstpostername']),
        'PAGE_ROW_LASTPOSTER' => cot_build_user($row['ft_lastposterid'], $row['ft_lastpostername']),

        'PAGE_ROW_USER_POSTED' => isset($row['ft_user_posted']) ? (int) $row['ft_user_posted'] : '',

        'PAGE_ROW_URL' => $row['ft_url'],

        'PAGE_ROW_PAGES' => $row['ft_pages'],

        'PAGE_ROW_PREVIEW' => $row['ft_preview'],
        'PAGE_ROW_PREVIEW_PLAIN' => strip_tags($row['ft_preview']),
      ));

      if (!empty(Cot::$extrafields[Cot::$db->forum_topics])) {
        foreach (Cot::$extrafields[Cot::$db->forum_topics] as $exfld) {
          $tag = mb_strtoupper($exfld['field_name']);
          $exfld_title = cot_extrafield_title($exfld, 'forums_topic_');
          $t->assign(array(
            'FORUMS_TOPICS_ROW_' . $tag . '_TITLE' => $exfld_title,
            'FORUMS_TOPICS_ROW_' . $tag => cot_build_extrafields_data('forums', $exfld, $row['ft_' . $exfld['field_name']],
              (Cot::$cfg['forums']['markup'] && Cot::$cfg['forums']['cat_' . $s]['allowbbcodes'])),
            'FORUMS_TOPICS_ROW_' . $tag . '_VALUE' => $row['ft_' . $exfld['field_name']]
          ));
        }
      }

      /* === Hook - Part 2 === */
      foreach ($extp as $pl) {
        include $pl;
      }
      /* ===== */

      $t->parse("MAIN.PAGE_ROW");
      $jj++;
    };

    // Render pagination if needed
		if ($enablePagination) {
			$totalitems = Cot::$db->query("SELECT ft.* FROM $db_forum_topics AS ft $sql_cond")->rowCount();

      $url_area = sedby_geturlarea();
			$url_params = sedby_geturlparams();
			$url_params[$pagination] = $durl;

			if ($enableAjax) {
				$ajax_mode = true;
				$ajax_plug = 'plug';
				if (Cot::$cfg['plugin']['forman']['encrypt_ajax_urls']) {
          $h = $tpl . ',' . $items . ',' . $order . ',' . $extra . ',' . $group . ',' . $offset . ',' . $pagination . ',' . $ajax_block . ',' . $cache_name . ',' . $cache_ttl . ',topics';
          $h = sedby_encrypt_decrypt('encrypt', $h, Cot::$cfg['plugin']['forman']['encrypt_key'], Cot::$cfg['plugin']['forman']['encrypt_iv']);
          $h = str_replace('=', '', $h);
          $ajax_plug_params = "r=forman&h=$h";
        } else {
          $ajax_plug_params = "r=forman&tpl=$tpl&items=$items&order=$order&extra=$extra&group=$group&offset=$offset&pagination=$pagination&ajax_block=$ajax_block&cache_name=$cache_name&cache_ttl=$cache_ttl&area=topics";
        }
			} else {
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
    if ((!$enableCache) && (Cot::$usr['maingrp'] == 5)) {
      $t->assign(array(
        'PAGE_TOP_QUERY' => $query,
        'PAGE_TOP_RES' => $res,
      ));
    }

    ($jj==1) && $t->parse("MAIN.NONE");

    /* === Hook === */
    foreach (cot_getextplugins('topiclist.tags') as $pl) {
      include $pl;
    }
    /* ===== */

    $t->parse();
    $output = $t->text();

    if ($enableCache && !$enablePagination && ($jj > 1)) {
      Cot::$cache->db->store($cache_name, $output, SEDBY_FORMAN_REALM, $cache_ttl);
    }
  }
  return $output;
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
function sedby_postlist($tpl = 'forman.postlist', $items = 0, $order = '', $extra = '', $group = 0, $offset = 0, $pagination = '', $ajax_block = '', $cache_name = '', $cache_ttl = '') {

  $enableAjax = $enableCache = $enablePagination = false;

  // Condition shortcut
  if (Cot::$cache && !empty($cache_name) && ((int)$cache_ttl > 0) && (Cot::$usr['id'] == 0)) {
    $enableCache = true;
    $cache_name = (!empty($cache_name)) ? str_replace(' ', '_', $cache_name) : '';
  }

	if ($enableCache && Cot::$cache->db->exists($cache_name, SEDBY_FORMAN_REALM)) {
    $output = Cot::$cache->db->get($cache_name, SEDBY_FORMAN_REALM);
  } else {

    global $L;
    require_once cot_langfile('forman');

    // Begin: Work on cats view permissions
    $black_cats = sedby_black_cats();
    if (!empty($black_cats)) {
      $black_cats = "fp_cat NOT IN ($black_cats)";
      $extra = empty($extra) ? $black_cats : $extra . " AND " . $black_cats;
    }
    // End: Work on cats view permissions

    /* === Hook === */
		foreach (cot_getextplugins('postlist.first') as $pl) {
			include $pl;
		}
		/* ===== */

    // Condition shortcuts
    if ((Cot::$cfg['turnajax']) && (Cot::$cfg['plugin']['forman']['ajax']) && !empty($ajax_block)) {
      $enableAjax = true;
    }

    if (!empty($pagination) && ((int)$items > 0)) {
      $enablePagination = true;
    }

    // DB tables shortcuts
		$db_forum_posts = Cot::$db->forum_posts;
		$db_forum_topics = Cot::$db->forum_topics;

		// Display the items
    (!isset($tpl) || empty($tpl)) && $tpl = 'forman.postlist';
		$t = new XTemplate(cot_tplfile($tpl, 'plug'));

    // Get pagination if necessary
		if ($enablePagination) {
      list($pg, $d, $durl) = cot_import_pagenav($pagination, $items);
    } else {
      $d = 0;
    }

		// Compile items number
		((int)($offset) <= 0) && $offset = 0;
		$d = $d + (int)$offset;
		$sql_limit = ($items > 0) ? "LIMIT $d, $items" : "";

		// Compile order
		$sql_order = empty($order) ? "ORDER BY fp_updated DESC" : " ORDER BY $order";

		// Compile group
		$sql_group = ($group == 1) ? "fp.fp_id = (SELECT MAX(fp_id) FROM " . $db_forum_posts . " AS fp2 WHERE fp2.fp_topicid = fp.fp_topicid)" : '';

		// Compile extra
		$sql_extra = (empty($extra)) ? "" : $extra;

    $sql_cond = sedby_twocond($sql_group, $sql_extra);

		$postlist_join_columns = "";
		$postlist_join_tables = "";

		// Users Module Support
		if (Cot::$cfg['plugin']['forman']['usertags']) {
			$db_users = Cot::$db->users;
			$postlist_join_columns .= " , u.* ";
			$postlist_join_tables .= "LEFT JOIN $db_users AS u ON (u.user_id = fp.fp_posterid)";
		}

		/* === Hook === */
		foreach (cot_getextplugins('postlist.query') as $pl) {
			include $pl;
		}
		/* ===== */

		$query = "SELECT fp.* $postlist_join_columns FROM $db_forum_posts AS fp $postlist_join_tables $sql_cond $sql_order $sql_limit";
		$res = Cot::$db->query($query);
		$jj = 1;

		/* === Hook - Part 1 === */
		$extp = cot_getextplugins('postlist.loop');
		/* ===== */

		while ($row = $res->fetch()) {
			(Cot::$cfg['plugin']['forman']['usertags']) && $t->assign(cot_generate_usertags($row, 'PAGE_ROW_USER_'));

			$topic_title = Cot::$db->query("SELECT ft_title FROM $db_forum_topics WHERE ft_id = ? LIMIT 1", $row['fp_topicid'])->fetchColumn();
			$post_author = htmlspecialchars($row['fp_postername']);

      $post_prefix = "SELECT fp_id FROM $db_forum_posts WHERE fp_topicid = " . $row['fp_topicid'] . " ORDER BY fp_creation ASC LIMIT 1";
      $post_prefix = Cot::$db->query($post_prefix)->fetchColumn();
      $post_prefix = ($post_prefix == $row['fp_id']) ? "" : $L['forman_re'];

			$t->assign(array(
				'PAGE_ROW_NUM'     => $jj,
				'PAGE_ROW_ODDEVEN' => cot_build_oddeven($jj),
				'PAGE_ROW_RAW'     => $row,

				'PAGE_ROW_CAT_TITLE'	=> Cot::$structure['forums'][$row['fp_cat']]['title'],
				'PAGE_ROW_CAT_URL'		=> cot_url('forums', 'm=topics&s=' . $row['fp_cat']),

				'PAGE_ROW_TOPIC_TITLE'	=> $topic_title,
				'PAGE_ROW_TOPIC_URL'		=> cot_url('forums', 'm=posts&q=' . $row['fp_topicid']),

				'PAGE_ROW_TOPICID'		=> $row['fp_topicid'],
				'PAGE_ROW_CAT'				=> $row['fp_cat'],

        'PAGE_ROW_CRUMBS'			=> cot_breadcrumbs(cot_forums_buildpath($row['fp_cat'], false), false, false),

        'PAGE_ROW_PREFIX'		  => $post_prefix,

        'PAGE_ROW_ID'					=> $row['fp_id'],
        'PAGE_ROW_URL'        => cot_url('forums', 'm=posts&q=' . $row['fp_topicid'] . '&d=' . $durl, "#" . $row['fp_id']),
        'PAGE_ROW_IDURL'      => cot_url('forums', 'm=posts&id=' . $row['fp_id']),

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
				$avatar_link = (Cot::$cfg['plugin']['forman']['usertags']) ? $row['user_avatar'] : Cot::$db->query("SELECT user_avatar FROM " . Cot::$db->users . " WHERE user_id = ?", $row['fp_posterid'])->fetchColumn();
				$t->assign(array(
					'PAGE_ROW_AVATAR' => (empty($avatar_link)) ? cot_rc('forman_default_avatar') : cot_rc('forman_avatar', array('src' => $avatar_link, 'user' => $post_author)),
					'PAGE_ROW_AUTHOR' => cot_build_user($row['fp_posterid'], $post_author),
				));
			} else {
				require_once cot_incfile('forman', 'plug', 'rc');
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
		if ($enablePagination) {
			$totalitems = Cot::$db->query("SELECT fp.* FROM $db_forum_posts AS fp $sql_cond")->rowCount();

      $url_area = sedby_geturlarea();
			$url_params = sedby_geturlparams();
			$url_params[$pagination] = $durl;

			if ($enableAjax) {
				$ajax_mode = true;
				$ajax_plug = 'plug';
				if (Cot::$cfg['plugin']['forman']['encrypt_ajax_urls']) {
          $h = $tpl . ',' . $items . ',' . $order . ',' . $extra . ',' . $group . ',' . $offset . ',' . $pagination . ',' . $ajax_block . ',' . $cache_name . ',' . $cache_ttl . ',posts';
    			$h = sedby_encrypt_decrypt('encrypt', $h, Cot::$cfg['plugin']['forman']['encrypt_key'], Cot::$cfg['plugin']['forman']['encrypt_iv']);
    			$h = str_replace('=', '', $h);
          $ajax_plug_params = "r=forman&h=$h";
        } else {
          $ajax_plug_params = "r=forman&tpl=$tpl&items=$items&order=$order&extra=$extra&group=$group&offset=$offset&pagination=$pagination&ajax_block=$ajax_block&cache_name=$cache_name&cache_ttl=$cache_ttl&area=posts";
        }
			} else {
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
		if ((!$enableCache) && (Cot::$usr['maingrp'] == 5)) {
			$t->assign(array(
				'PAGE_TOP_QUERY' => $query,
				'PAGE_TOP_RES' => $res,
			));
		}

		($jj==1) && $t->parse("MAIN.NONE");

		/* === Hook === */
		foreach (cot_getextplugins('postlist.tags') as $pl) {
			include $pl;
		}
		/* ===== */

		$t->parse();
		$output = $t->text();

		if ($enableCache && !$enablePagination && ($jj > 1)) {
      Cot::$cache->db->store($cache_name, $output, SEDBY_FORMAN_REALM, $cache_ttl);
    }
	}
	return $output;
}

function sedby_forman_count($area = 'topics') {
  if ($area == 'topics') {
		$db_forum_topics = Cot::$db->forum_topics;
    $out = Cot::$db->countRows($db_forum_topics);
  } elseif ($area == 'posts') {
    $db_forum_posts = Cot::$db->forum_posts;
    $out = Cot::$db->countRows($db_forum_posts);
  } elseif ($area == 'users') {
    $db_users = Cot::$db->users;
    $out = Cot::$db->countRows($db_users);
  }
  return $out;
}

function sedby_forman_topusers($tpl = 'forman.topusers', $items = 0, $order = 'user_postcount DESC', $extra = '', $zerocount = 0, $offset = 0, $pagination = '', $ajax_block = '', $cache_name = '', $cache_ttl = '') {

  $enableAjax = $enableCache = $enablePagination = false;

  // Condition shortcut
  if (Cot::$cache && !empty($cache_name) && ((int)$cache_ttl > 0)) {
    $enableCache = true;
    $cache_name = (!empty($cache_name)) ? str_replace(' ', '_', $cache_name) : '';
  }

  if ($enableCache && Cot::$cache->db->exists($cache_name, SEDBY_FORMAN_REALM)) {
    $output = Cot::$cache->db->get($cache_name, SEDBY_FORMAN_REALM);
  } else {

    global $L, $Ls;
    require_once cot_langfile('forman');

    /* === Hook === */
		foreach (cot_getextplugins('topusers.first') as $pl) {
			include $pl;
		}
		/* ===== */

    // Condition shortcuts
    if ((Cot::$cfg['turnajax']) && (Cot::$cfg['plugin']['forman']['ajax']) && !empty($ajax_block)) {
      $enableAjax = true;
    }

    if (!empty($pagination) && ((int)$items > 0)) {
      $enablePagination = true;
    }

    // DB tables shortcuts
		$db_users = Cot::$db->users;

    $fmax = Cot::$db->query("SELECT MAX(user_postcount) FROM $db_users")->fetchColumn();

    // Display the items
    (!isset($tpl) || empty($tpl)) && $tpl = 'forman.topusers';
		$t = new XTemplate(cot_tplfile($tpl, 'plug'));

    // Get pagination if necessary
		if ($enablePagination) {
      list($pg, $d, $durl) = cot_import_pagenav($pagination, $items);
    } else {
      $d = 0;
    }

		// Compile items number
		((int)($offset) <= 0) && $offset = 0;
		$d = $d + (int)$offset;
		$sql_limit = ($items > 0) ? "LIMIT $d, $items" : "";

		// Compile order
		$sql_order = empty($order) ? "ORDER BY user_postcount DESC" : " ORDER BY $order";

    // Include users with zero posts
    $sql_zerocount = (empty($zerocount) || ($zerocount == 0)) ? "user_postcount != 0" : "";

    // Compile extra
		$sql_extra = (empty($extra)) ? "" : $extra;

    $sql_cond = sedby_twocond($sql_zerocount, $sql_extra);

    /* === Hook === */
    foreach (cot_getextplugins('topusers.query') as $pl) {
      include $pl;
    }
    /* ===== */

    $query = "SELECT user_id, user_name, user_postcount FROM $db_users $sql_cond $sql_order $sql_limit";
    $res = Cot::$db->query($query);
    $jj = 1;

    /* === Hook - Part 1 === */
    $extp = cot_getextplugins('topusers.loop');
    /* ===== */

    while ($row = $res->fetch()) {
      $t->assign(cot_generate_usertags($row, 'PAGE_ROW_USER_'));
      $t->assign(array(
        'PAGE_ROW_NUM'     => $jj,
        'PAGE_ROW_ODDEVEN' => cot_build_oddeven($jj),
        'PAGE_ROW_RAW'     => $row,

        'PAGE_ROW_PERCENT' => $row['user_postcount'] / $fmax * 100,
        'PAGE_ROW_POSTS'   => cot_declension($row['user_postcount'], $L['forman_posts']),
      ));

      /* === Hook - Part 2 === */
      foreach ($extp as $pl) {
        include $pl;
      }
      /* ===== */

      $t->parse("MAIN.PAGE_ROW");
      $jj++;
    }

    // Render pagination if needed
		if ($enablePagination) {
			$totalitems = Cot::$db->query("SELECT user_id FROM $db_users $sql_cond")->rowCount();

      $url_area = sedby_geturlarea();
			$url_params = sedby_geturlparams();
			$url_params[$pagination] = $durl;

			if ($enableAjax) {
				$ajax_mode = true;
				$ajax_plug = 'plug';
				if (Cot::$cfg['plugin']['forman']['encrypt_ajax_urls']) {
          $h = $tpl . ',' . $items . ',' . $order . ',' . $extra . ',' . $zerocount . ',' . $offset . ',' . $pagination . ',' . $ajax_block . ',' . $cache_name . ',' . $cache_ttl . ',topusers';
    			$h = sedby_encrypt_decrypt('encrypt', $h, Cot::$cfg['plugin']['forman']['encrypt_key'], Cot::$cfg['plugin']['forman']['encrypt_iv']);
    			$h = str_replace('=', '', $h);
          $ajax_plug_params = "r=forman&h=$h";
        } else {
          $ajax_plug_params = "r=forman&tpl=$tpl&items=$items&order=$order&extra=$extra&zerocount=$zerocount&offset=$offset&pagination=$pagination&ajax_block=$ajax_block&cache_name=$cache_name&cache_ttl=$cache_ttl&area=topusers";
        }
			} else {
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
		if ((!$enableCache) && (Cot::$usr['maingrp'] == 5)) {
			$t->assign(array(
				'PAGE_TOP_QUERY' => $query,
				'PAGE_TOP_RES' => $res,
			));
		}

		($jj==1) && $t->parse("MAIN.NONE");

		/* === Hook === */
		foreach (cot_getextplugins('topusers.tags') as $pl) {
			include $pl;
		}
		/* ===== */

		$t->parse();
		$output = $t->text();

		if ($enableCache && !$enablePagination && ($jj > 1)) {
      Cot::$cache->db->store($cache_name, $output, SEDBY_FORMAN_REALM, $cache_ttl);
    }

  }
  return $output;
}
