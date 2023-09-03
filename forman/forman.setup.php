<?php
/* ====================
[BEGIN_COT_EXT]
Code=forman
Name=Forum Lists Widgets
Category=navigation-structure
Description=Generates custom topics / posts lists
Version=1.00b
Date=2023-09-02
Author=Dmitri Beliavski
Copyright=&copy; 2023 Seditio.By
Notes=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=forums
[END_COT_EXT]
[BEGIN_COT_EXT_CONFIG]

useajax=00:separator:::
ajax=01:radio::0:Use AJAX
encrypt_ajax_urls=02:radio::0:Encrypt ajax URLs
encrypt_key=03:string::1234567890123456:Secret Key
encrypt_iv=04:string::1234567890123456:Initialization Vector

gentags=20:separator:::
usertags=23:radio::0:Generate User tags
[END_COT_EXT_CONFIG]
==================== */

/**
* Forman Plugin / Setup
*
* @package forman
* @author Dmitri Beliavski
* @copyright (c) 2023 seditio.by
*/

defined('COT_CODE') or die('Wrong URL');
