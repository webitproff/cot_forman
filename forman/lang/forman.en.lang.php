<?php
/**
* [SEDBY] Forman Plugin / EN Locale
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');

/**
 * Plugin Info
 */

$L['info_name'] = '[SEDBY] Forman';
$L['info_desc'] = 'Additional functionality for the forums';
$L['info_notes'] = 'Functions for topic & post list widgets, linear forum, and forum stats';

/**
 * Plugin Config
 */

 $L['cfg_useajax'] = 'Использование AJAX:';
 $L['cfg_ajax'] = 'Использовать AJAX для паджинации';
 $L['cfg_ajax_hint'] = 'Работает только при использовании аргумента $ajax_block и $cfg[\'turnajax\']';
 $L['cfg_encrypt_ajax_urls'] = 'Шифровать URLы AJAX-паджинации';
 $L['cfg_encrypt_ajax_urls_hint'] = 'Работает только при включенной AJAX-паджинации, рекомендуется для действующих сайтов в т. ч. при использовании аргумента $extra с AJAX';
 $L['cfg_encrypt_key'] = 'Ключ шифрования';
 $L['cfg_encrypt_iv'] = 'Вектор исполнения';

 $L['cfg_gentags'] = 'Генерация тегов:';
 $L['cfg_usertags'] = 'Создавать теги для модуля Users';
 $L['cfg_thanks'] = 'Создавать теги для плагина Thanks';
 $L['cfg_thanks_hint'] = 'Только функция sedby_postlist()';

 $L['cfg_misc'] = 'Разное:';
 $L['cfg_flatview'] = 'Линейный вид форумов';
 $L['cfg_flatview_hint'] = 'На главной страние форумов вместо списка разделов выводится список топиков по дате обновления';

/**
 * Plugin Admin
 */



/**
 * Plugin Globals
 */

$L['forman_flatview_desc'] = 'Линейные форумы &ndash; это такое представление главной страницы формумов, при котором вместо разделов выводится список тем с сортировкой по обновлению (дате публикации последнего поста в топике)';

$L['forman_by'] = 'by';
$L['forman_topauthors'] = 'Top Authors';
$L['forman_forumstats'] = 'Forum Stats';
$L['forman_lastreply'] = 'Last reply from';
$L['forman_mostrecentpost'] = 'Most recent post';
$L['forman_posts'] = 'post,posts';
$L['forman_re'] = 'Re: ';
$L['forman_recentposts'] = 'Recent posts';

$L['forman_switch_to_linear'] = 'Линейный вид';
$L['forman_switch_to_table'] = 'Табличный вид';

$L['forman_features_title'] = 'Forman plugin &ndash; additional functions and minihacks for the forums:';
$L['forman_features_item1'] = 'linear forums home page &ndash; list topics sorted by date via custom template;';
$L['forman_features_item2'] = 'Recent Posts location &ndash; list topics sorted by date via custom template;';
$L['forman_features_item3'] = 'sedby_topiclist() function &ndash; generate topic lists by conditions;';
$L['forman_features_item4'] = 'sedby_postlist() function &ndash; generate post lists by conditions;';
$L['forman_features_item5'] = 'sedby_forman_count() function &ndash; count topics, posts and users;';
$L['forman_features_item6'] = 'sedby_forman_topusers() function &ndash; list users by posts activity;';
$L['forman_features_item7'] = 'Bootstrap 5.3 ready markup.';
