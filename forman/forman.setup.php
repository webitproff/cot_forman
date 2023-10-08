<?php
/* ====================
[BEGIN_COT_EXT]
Code=forman
Name=[SEDBY] Forman
Category=navigation-structure
Description=Extra functions for Cotonti-powered forums
Version=1.00b
Date=2023-09-06
Author=Dmitri Beliavski
Copyright=&copy; 2023 Seditio.By
Notes=Functions for topic, post list and forum stats widgets, linear forum mode
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=forums
Requires_plugins=cotlib
Recommends_modules=
Recommends_plugins=
[END_COT_EXT]
[BEGIN_COT_EXT_CONFIG]

useajax=00:separator:::
ajax=01:radio::0:Use AJAX
encrypt_ajax_urls=02:radio::0:Encrypt ajax URLs
encrypt_key=03:string::1234567890123456:Secret Key
encrypt_iv=04:string::1234567890123456:Initialization Vector

gentags=10:separator:::
usertags=11:radio::0:Generate User tags
thanks=12:radio::0:Generate Thanks tags

misc=20:separator:::
flatview=21:radio::0:Flatview forums sections page
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
