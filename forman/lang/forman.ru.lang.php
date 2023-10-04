<?php
/**
* [SEDBY] Forman Plugin / RU Locale
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
$L['info_desc'] = 'Дополнительный функционал для форума';
$L['info_notes'] = 'Функции для создания виджетов списков тем и постов, линейный форум и статистика для форумов.';

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

$L['forman_by'] = 'от';
$L['forman_topauthors'] = 'Топ авторов';
$L['forman_forumstats'] = 'Статистика форумов';
$L['forman_lastreply'] = 'Последний ответ от';
$L['forman_mostrecentpost'] = 'Последнее сообщение';
$L['forman_posts'] = 'пост,поста,постов';
$L['forman_re'] = 'Re: ';
$L['forman_recentposts'] = 'Последние посты';

$L['forman_switch_to_linear'] = 'Линейный вид';
$L['forman_switch_to_table'] = 'Табличный вид';

$L['forman_features_title'] = 'Плагин Forman &ndash; дополнительный функционал и минихаки для форумов:';
$L['forman_features_item1'] = 'линейный вид главной страницы форумов &ndash; вывод топиков по убыванию даты через свой шаблон;';
$L['forman_features_item2'] = 'локация Recent Posts &ndash; вывод постов по убыванию даты через свой шаблон;';
$L['forman_features_item3'] = 'функция sedby_topiclist() &ndash; формирование списка топиков по условиям;';
$L['forman_features_item4'] = 'функция sedby_postlist() &ndash; формирование списка постов по условиям;';
$L['forman_features_item5'] = 'функция sedby_forman_count() &ndash; подсчет топиков, постов и пользователей;';
$L['forman_features_item6'] = 'функция sedby_forman_topusers() &ndash; вывод списка пользователей по активности постов;';
$L['forman_features_item7'] = 'разметка оптимизирована под Bootstrap 5.3';
