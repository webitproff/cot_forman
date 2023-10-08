<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.sections.main
[END_COT_EXT]
==================== */

/**
* Forman Plugin / Forum Sections
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

include_once cot_langfile('forman');
include_once cot_langfile('forums', 'module');

Resources::linkFileFooter($cfg['plugins_dir'].'/forman/inc/forman.css', 'css');

$flatview = &$_SESSION['flatview'];

if ($a == 'recent') {
  $t = new XTemplate(cot_tplfile('forman.recent.full', 'plug'));
  $recentCrumbs[] = array(cot_url('forums'), Cot::$L['Forums']);
  $recentCrumbs[] = Cot::$L['forman_recentposts'];
  $t->assign('FORMAN_RECENT_BREADCRUMBS', cot_breadcrumbs($recentCrumbs, Cot::$cfg['homebreadcrumb']));
}

if ($a == 'toggle_flatview') {
  if ($flatview == false) {
    $flatview = true;
  } else {
    $flatview = false;
  }
  cot_redirect(cot_url('forums'));
}

if ($flatview == false) {
  $flat_view_text = $L['forman_switch_to_linear'];
} else {
  $flat_view_text = $L['forman_switch_to_table'];
  $t = new XTemplate(cot_tplfile('forman.flatview', 'plug'));
}

$t->assign('FORMAN_FLATVIEW_TOGGLE', cot_rc_link(cot_url('forums', 'a=toggle_flatview'), $flat_view_text, 'class="btn btn-primary btn-sm"'));
