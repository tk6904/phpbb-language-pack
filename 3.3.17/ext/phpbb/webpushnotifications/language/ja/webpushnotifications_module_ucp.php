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
	'NOTIFICATION_METHOD_PHPBB_WPN_WEBPUSH'	=> 'Web Push',
	'NOTIFY_WEBPUSH_NOTIFICATIONS'	=> 'Web Push通知',
	'NOTIFY_WEBPUSH_DISABLE'		=> 'プッシュ通知を無効にする',
	'NOTIFY_WEBPUSH_ENABLE'			=> 'プッシュ通知を有効にする',
	'NOTIFY_WEBPUSH_ENABLE_SLIDER'	=> 'プッシュ通知を有効にする',
	'NOTIFY_WEBPUSH_ENABLE_EXPLAIN'	=> 'プッシュ通知を有効にすると、このデバイスでのみ通知が有効になります。通知は、ブラウザーの設定または上のボタンからいつでも無効にできます。また、下でWeb Push通知の種類を1つも選択していない場合、Web Push通知は届きません。',
	'NOTIFY_WEBPUSH_SUBSCRIBE'		=> '有効にして購読',
	'NOTIFY_WEBPUSH_UNSUBSCRIBE'	=> '無効にして購読を解除',
	'NOTIFY_WEBPUSH_DROPDOWN_TITLE'	=> '通知設定に移動して、受け取りたいプッシュ通知を設定してください。',
	'NOTIFY_WEBPUSH_DENIED'			=> 'このサイトからの通知を拒否しました。プッシュ通知を有効にするには、ブラウザーの設定でこのサイトからの通知を許可してください。',
	'NOTIFY_WEBPUSH_NOT_SUPPORTED'	=> 'プッシュ通知に対応していません',
	'NOTIFY_WEBPUSH_POPUP_TITLE'	=> 'ブラウザー通知を許可',
	'NOTIFY_WEBPUSH_POPUP_MESSAGE'	=> '返信、プライベートメッセージ、その他の関連するフォーラム活動について、ブラウザー通知を送信します。任意で設定でき、これらの設定はいつでも管理できます。',
	'NOTIFY_WEBPUSH_POPUP_ALLOW'	=> '許可',
	'NOTIFY_WEBPUSH_POPUP_DENY'		=> '拒否',
	'NOTIFY_WEBPUSH_POPUP_DISABLE'	=> '通知リマインダーのポップアップ',
	'NOTIFY_WEBPUSH_POPUP_DISABLE_EXPLAIN'	=> 'ブラウザー通知が現在有効になっていない場合、または有効かどうかを検出できない場合に、ブラウザー通知を許可するよう求めるリマインダーを表示します。これを無効にすると、すべてのデバイスでこれらのリマインダーのポップアップが表示されなくなります。無効にした場合、将来ブラウザー通知が機能しなくなっても警告されません。',
	'NOTIFY_WEBPUSH_POPUP_ENABLER'	=> 'リマインダーを有効にする',
	'NOTIFY_WEBPUSH_POPUP_DISABLER'	=> 'リマインダーを無効にする',
]);
