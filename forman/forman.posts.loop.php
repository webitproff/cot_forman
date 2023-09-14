<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.loop
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

$db_forum_topics = Cot::$db->forum_topics;
$topic_title = Cot::$db->query("SELECT ft_title FROM $db_forum_topics WHERE ft_id = ?", $row['fp_topicid'])->fetchColumn();

$t->assign(array(
  'FORUMS_POSTS_TOPIC' => $topic_title,
));
