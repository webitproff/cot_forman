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

(Cot::$cfg['plugin']['forman']['flatview']) && $t = new XTemplate(cot_tplfile('forman.flatview', 'plug'));

if ($a == 'recent') {
  $t = new XTemplate(cot_tplfile('forman.recent', 'plug'));
  $recentCrumbs[] = array(cot_url('forums'), Cot::$L['Forums']);
  $recentCrumbs[] = Cot::$L['forman_recentposts'];
  $t->assign('FORMAN_RECENT_BREADCRUMBS', cot_breadcrumbs($recentCrumbs, Cot::$cfg['homebreadcrumb']));
}
