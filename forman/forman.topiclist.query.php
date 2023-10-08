<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=topiclist.query
[END_COT_EXT]
==================== */

/**
* Forman Plugin / First & last poster avatars, part 1
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

$db_users = Cot::$db->users;
$topiclist_join_columns .= " , firstposter.user_avatar as firstposter_avatar, lastposter.user_avatar as lastposter_avatar ";
$topiclist_join_tables .= " LEFT JOIN $db_users AS firstposter ON (firstposter.user_id = ft.ft_firstposterid) ";
$topiclist_join_tables .= " LEFT JOIN $db_users AS lastposter ON (lastposter.user_id = ft.ft_lastposterid) ";
