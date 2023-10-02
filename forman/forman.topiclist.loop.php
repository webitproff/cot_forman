<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=topiclist.loop
[END_COT_EXT]
==================== */

/**
* Forman Plugin / First & last poster avatars, part 2
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

$fpa = empty($row['firstposter_avatar']) ? 'datas/defaultav/default.png' : $row['firstposter_avatar'];
$lpa = empty($row['lastposter_avatar']) ? 'datas/defaultav/default.png' : $row['lastposter_avatar'];

$t->assign(array(
  'PAGE_ROW_FIRSTPOSTER_AVATAR' => cot_rc('userimg_img', array('src' => $fpa, 'alt' => \Cot::$L['Avatar'], 'class' => 'img-fluid')),
  'PAGE_ROW_LASTPOSTER_AVATAR'  => cot_rc('userimg_img', array('src' => $lpa, 'alt' => \Cot::$L['Avatar'], 'class' => 'img-fluid')),
));
