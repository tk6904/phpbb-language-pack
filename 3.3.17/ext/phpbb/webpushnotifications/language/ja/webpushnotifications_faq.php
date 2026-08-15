<?php
/**
 *
 * phpBB Browser Push Notifications. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, phpBB Limited <https://www.phpbb.com>
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
	'HELP_FAQ_WPN'					=> 'ブラウザー向けWeb Push通知',
	'HELP_FAQ_WPN_WHAT_QUESTION'	=> 'Web Push通知とは何ですか？',
	'HELP_FAQ_WPN_WHAT_ANSWER'		=> 'Web Push通知はphpBBの通知システムを拡張し、フォーラムを閲覧していないときでも、デスクトップやモバイルデバイスへリアルタイムで通知を直接送信できます。これらの通知はアプリのアラートのように機能し、プライベートメッセージ、投稿への反応、モデレーション操作などの最新情報をすぐに知らせます。',
	'HELP_FAQ_WPN_HOW_QUESTION'		=> 'パソコンまたはモバイルデバイスでフォーラムの通知アラートを受け取るにはどうすればよいですか？',
	'HELP_FAQ_WPN_HOW_ANSWER'		=> 'UCP（ユーザーコントロールパネル）の「通知オプション」に移動し、「Web Push通知を有効にする」をクリックしてください。ブラウザーから通知の送信許可を求められる場合がありますので、許可してください。それでも通知を受け取れない場合は、デバイスのシステム設定を確認し、ブラウザーの通知が有効になっていることを確認してください。iPhoneやiPadなどのモバイルデバイスでは、Web Push通知を機能させるためにフォーラムサイトをホーム画面に追加する必要がある場合があります。これにより、サイトがスタンドアロンのWebアプリとして動作します。Web Push通知を有効にする方法については、モバイルデバイスの手順に従って、<a href="https://www.xda-developers.com/how-enable-safari-notifications-iphone/" target="_blank">iPhone/iPad</a>または<a href="https://support.google.com/chrome/answer/3220216?hl=en&co=GENIE.Platform%3DAndroid&oco=0" target="_blank">Android</a>を参照してください。',
	'HELP_FAQ_WPN_SESSION_QUESTION'	=> 'ログアウトしている場合でも通知を受け取れますか？',
	'HELP_FAQ_WPN_SESSION_ANSWER'	=> 'はい、ログアウトしていても通知を受け取り続けます。',
	'HELP_FAQ_WPN_SUBBING_QUESTION'	=> '「プッシュ通知を有効にする」ボタンが無効になっているのはなぜですか？',
	'HELP_FAQ_WPN_SUBBING_ANSWER'	=> '「プッシュ通知を有効にする」ボタンが表示されているのにクリックできない場合は、ブラウザーまたはデバイスがプッシュ通知に対応していない可能性があります。この機能に対応した別のブラウザーまたはデバイスをお試しください。',
	'HELP_FAQ_WPN_GENERAL_QUESTION'	=> '通知を受け取る際にまだ問題がある場合はどうすればよいですか？',
	'HELP_FAQ_WPN_GENERAL_ANSWER'	=> 'ブラウザーの設定で、このフォーラムによる通知の送信が許可されていることを確認してください。また、デバイスのシステム設定で、Webブラウザーまたはアプリからの通知が許可されていることも確認してください。ブラウザーによっては、閉じていても通知を配信するものがありますが、ブラウザーを開いている場合にのみ配信するものもあります。<a href="https://caniuse.com/push-api" target="_blank">ブラウザーの対応状況については、この表を参照してください。</a>最後に、広告ブロッカーを使用している場合は、プッシュ通知をブロックする設定になっていないか確認してください。',
]);
