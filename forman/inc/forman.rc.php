<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=rc
[END_COT_EXT]
==================== */

/**
* Forman Plugin / Resources (misc)
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

// Redefine Avatars
$R['forman_avatar'] = '<img src="{$src}" alt="{$user}" class="avatar img-fluid" />';
$R['forman_default_avatar'] = '<img src="datas/defaultav/default.png" alt="'.$L['Avatar'].'" class="avatar img-fluid" />';

// Post Status
$R['post_update_status'] = '<span class="text-lowercase ms-2">({$status})</span>';
