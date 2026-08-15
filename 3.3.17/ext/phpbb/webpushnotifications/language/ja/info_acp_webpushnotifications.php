<?php
/**
 *
 * phpBB Browser Push Notifications. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2023, phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'ACP_WEBPUSH_EXT_SETTINGS'			=> 'Web Push設定',
	'ACP_WEBPUSH_PWA_SETTINGS'			=> 'PWA設定',
	'ACP_WEBPUSH_REMOVE_WARNING'		=> 'Web Push通知はphpBBに組み込まれました',
	'ACP_WEBPUSH_REMOVE_NOTICE'			=> '拡張機能「phpBB Browser Push Notifications」は不要になったため、アンインストールして削除してください。<br>拡張機能をアンインストールすると、拡張機能に関連付けられたすべての設定とユーザー設定が、phpBB組み込みのプッシュ通知へ移行されます。',
	'LOG_CONFIG_WEBPUSH'				=> '<strong>Web Push設定を変更しました</strong>',
	'LOG_WEBPUSH_MESSAGE_FAIL'			=> '<strong>Web Pushメッセージを送信できませんでした：</strong><br>» %s',
	'LOG_WEBPUSH_SUBSCRIPTION_REMOVED'	=> '<strong>Web Pushの購読を削除しました：</strong><br>» %s',
	'LOG_WEBPUSH_ICON_DIR_FAIL'			=> '<strong>Web Push Notifications拡張機能は、phpBBのimagesディレクトリ内の次の項目を移行できませんでした：</strong><br>» %1$s » %2$s',
	'LOG_WEBPUSH_ICON_DIR_SUCCESS'		=> '<strong>Web Push Notifications拡張機能が次のディレクトリを追加しました：</strong><br>» <samp>%s</samp>',
	'LOG_WEBPUSH_ICON_COPY_SUCCESS'		=> '<strong>Web Push Notifications拡張機能が既存のPWAタッチアイコンを次へコピーしました：</strong><br>» <samp>%s</samp>',
]);
