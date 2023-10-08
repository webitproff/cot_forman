<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=postlist.loop
[END_COT_EXT]
==================== */

/**
* Forman Plugin / AJAX for cot_postlist
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

if (Cot::$cfg['plugin']['forman']['thanks'] && cot_plugin_active('thanks')) {
	require_once cot_incfile('thanks', 'plug', 'api');
	$t->assign(array(
		'PAGE_ROW_THANKS_COUNT' => thanks_count('forums', $row['fp_id']),
	));
}
