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

(Cot::$cfg['plugin']['forman']['flatview']) && $t = new XTemplate(cot_tplfile('forman.flatview', 'plug'));
